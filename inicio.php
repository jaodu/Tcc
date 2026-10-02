<?php
require_once 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$idUsuario = (int) $_SESSION['usuario_id'];
$hoje = date('Y-m-d');

$diasSemanaCompletos = [1 => 'Domingo', 2 => 'Segunda-feira', 3 => 'Terça-feira', 4 => 'Quarta-feira', 5 => 'Quinta-feira', 6 => 'Sexta-feira', 7 => 'Sábado'];
$letrasSemana        = [0 => 'D', 1 => 'S', 2 => 'T', 3 => 'Q', 4 => 'Q', 5 => 'S', 6 => 'S']; 
$diaHojeBanco        = ((int) date('w')) + 1; 
$dataFormatada        = $diasSemanaCompletos[$diaHojeBanco] . ', ' . date('d/m/Y');

$stmt = $conexao->prepare("SELECT nome FROM perfil WHERE id_usuario = ?");
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$perfil = $stmt->get_result()->fetch_assoc();
$nomeExibicao = $perfil['nome'] ?? null;

if (!$nomeExibicao) {
    $stmt = $conexao->prepare("SELECT email FROM usuario WHERE id = ?");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $usuario = $stmt->get_result()->fetch_assoc();
    $nomeExibicao = $usuario ? explode('@', $usuario['email'])[0] : 'visitante';
}

$stmt = $conexao->prepare("
    SELECT rc.id
    FROM registro_dor rd
    JOIN regiao_corpo rc ON rc.id = rd.id_regiao_corpo
    WHERE rd.id_usuario = ? AND rd.ativa = 1
");
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$idsRegioes = array_column($stmt->get_result()->fetch_all(MYSQLI_ASSOC), 'id');

$exerciciosHoje = [];
if (!empty($idsRegioes)) {
    $limitarUmPorRegiao = count($idsRegioes) > 1;
    $placeholders = implode(',', array_fill(0, count($idsRegioes), '?'));
    $tiposParam   = str_repeat('i', count($idsRegioes)) . 'i';
    $parametros   = array_merge($idsRegioes, [$diaHojeBanco]);

    $sql = "
        SELECT e.id, e.titulo, c.id_regiao_corpo, rc.nome AS regiao_nome
        FROM cronograma c
        JOIN exercicio e ON e.id = c.id_exercicio
        JOIN regiao_corpo rc ON rc.id = c.id_regiao_corpo
        WHERE c.id_regiao_corpo IN ($placeholders) AND c.dia = ?
        ORDER BY c.id_regiao_corpo ASC, e.titulo ASC
    ";
    $stmtHoje = $conexao->prepare($sql);
    $stmtHoje->bind_param($tiposParam, ...$parametros);
    $stmtHoje->execute();
    $linhasHoje = $stmtHoje->get_result()->fetch_all(MYSQLI_ASSOC);

    $porRegiaoHoje = [];
    foreach ($linhasHoje as $linha) {
        $porRegiaoHoje[(int) $linha['id_regiao_corpo']][] = $linha;
    }
    foreach ($porRegiaoHoje as $exerciciosDaRegiao) {
        $selecionados = $limitarUmPorRegiao ? array_slice($exerciciosDaRegiao, 0, 1) : $exerciciosDaRegiao;
        foreach ($selecionados as $ex) {
            $exerciciosHoje[] = $ex;
        }
    }
}

$stmt = $conexao->prepare("
    SELECT au.id_exercicio
    FROM atividade_usuario au
    INNER JOIN (
        SELECT id_exercicio, MAX(id) AS ultimo_id
        FROM atividade_usuario
        WHERE id_usuario = ? AND data_atividade = ?
        GROUP BY id_exercicio
    ) ultimo ON ultimo.id_exercicio = au.id_exercicio AND ultimo.ultimo_id = au.id
    WHERE au.concluido = 1
");
$stmt->bind_param("is", $idUsuario, $hoje);
$stmt->execute();
$concluidosHoje = array_column($stmt->get_result()->fetch_all(MYSQLI_ASSOC), 'id_exercicio');

$totalHoje      = count($exerciciosHoje);
$totalConcluido = count(array_intersect(array_column($exerciciosHoje, 'id'), $concluidosHoje));

$dataInicioSemana = date('Y-m-d', strtotime('-6 days'));
$stmt = $conexao->prepare("
    SELECT data_atividade, COUNT(*) AS total
    FROM atividade_usuario
    WHERE id_usuario = ? AND concluido = 1 AND data_atividade BETWEEN ? AND ?
    GROUP BY data_atividade
");
$stmt->bind_param("iss", $idUsuario, $dataInicioSemana, $hoje);
$stmt->execute();
$linhasSemana = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$porData = [];
foreach ($linhasSemana as $linha) {
    $porData[$linha['data_atividade']] = (int) $linha['total'];
}
$totalSemana = array_sum($porData);

$diasDaSemanaGrid = [];
for ($i = 6; $i >= 0; $i--) {
    $data = date('Y-m-d', strtotime("-$i day"));
    $diasDaSemanaGrid[] = [
        'letra' => $letrasSemana[(int) date('w', strtotime($data))],
        'ativo' => !empty($porData[$data]),
        'hoje'  => $data === $hoje,
    ];
}

$opcoesBemEstar = [
    'otimo'   => ['emoji' => '😄', 'label' => 'Ótimo',   'progresso' => 100],
    'bom'     => ['emoji' => '🙂', 'label' => 'Bom',      'progresso' => 75],
    'regular' => ['emoji' => '😐', 'label' => 'Regular',  'progresso' => 50],
    'ruim'    => ['emoji' => '☹️', 'label' => 'Ruim',     'progresso' => 25],
];

// Verifica se o usuário já fez o check-in de hoje
$stmt = $conexao->prepare("SELECT bem_estar FROM evolucao WHERE id_usuario = ? AND data_registro = ? LIMIT 1");
$stmt->bind_param("is", $idUsuario, $hoje);
$stmt->execute();
$evolucaoHoje = $stmt->get_result()->fetch_assoc();
$bemEstarHojeLabel = $evolucaoHoje['bem_estar'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vitallis</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <div class="body-header">
    <header class="header">
        <a href="#" class="vt">
            <img src="imagens/logoobranca.svg" class="logonav" alt="">
        </a>

        <nav>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="inicio.php" class="nav-link">
                        <div class="icon">
                            <span class="material-symbols-outlined">
                            home
                            </span>
                        </div>
                        <span class="label">Início</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="exercicio.php" class="nav-link">
                        <div class="icon">
                            <span class="material-symbols-outlined">
                            exercise
                            </span>
                        </div>
                        <span class="label">Exercícios</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="cronograma.php" class="nav-link">
                        <div class="icon">
                            <span class="material-symbols-outlined">
                            calendar_month
                            </span>
                        </div>
                        <span class="label">Cronograma</span>
                    </a>
                </li>

                <li class="nav-item dropdown-item" id="perfilItem">
                
                    <a href="#" class="nav-link" id="perfilToggle">
                        <span class="material-symbols-outlined">
                            account_circle
                        </span>
                    </a>
                    <ul class="dropdown-menu" id="perfilDropdown">
                        <li>
                            <a href="perfil.php" class="dropdown-link">
                                <span class="material-symbols-outlined">person</span>
                                <span>Ver perfil</span>
                            </a>
                        </li>
                        <li>
                            <a href="logout.php" class="dropdown-link" id="logoutBtn">
                                <span class="material-symbols-outlined">logout</span>
                                <span>Sair</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>
    <main class="inicio-main">

        <section class="inicio-saudacao">
            <p class="inicio-data"><?= $dataFormatada ?></p>
            <h1>Olá, <?= htmlspecialchars(ucfirst($nomeExibicao)) ?>!</h1>
            <p class="inicio-sub">Um passo de cada vez. Confira o que te espera hoje.</p>
        </section>

        <section class="inicio-atalhos">
            <a href="exercicio.php" class="atalho-card">
                <span class="material-symbols-outlined">fitness_center</span>
                <h3>Meus Exercícios</h3>
                <p>Veja a lista completa e busque por nome.</p>
            </a>
            <a href="cronograma.php" class="atalho-card">
                <span class="material-symbols-outlined">calendar_month</span>
                <h3>Meu Cronograma</h3>
                <p>Organize sua semana de reabilitação.</p>
            </a>
            <a href="perfil.php" class="atalho-card">
                <span class="material-symbols-outlined">person</span>
                <h3>Meu Perfil</h3>
                <p>Atualize seus dados e suas dores.</p>
            </a>
        </section>

        <div class="inicio-grid">

            <section class="inicio-card">
                <h2>Exercícios de hoje</h2>

                <?php
                    $pendentesHojeInicio = array_values(array_filter(
                        $exerciciosHoje,
                        fn($ex) => !in_array((int) $ex['id'], $concluidosHoje, true)
                    ));
                ?>

                <?php if (empty($idsRegioes)): ?>
                    <p class="cronograma-sem-exercicio">
                        Marque as regiões com dor no seu perfil pra gente montar
                        os exercícios de hoje pra você.
                    </p>
                <?php elseif ($totalHoje === 0): ?>
                    <p class="cronograma-sem-exercicio">Nenhum exercício cadastrado para hoje. Aproveite pra descansar!</p>
                <?php else: ?>
                    <div class="progresso-hoje" data-concluido="<?= $totalConcluido ?>" data-total="<?= $totalHoje ?>">
                        <div class="barra-progresso-wrap">
                            <div class="barra-progresso-fill" style="width: <?= round($totalConcluido / $totalHoje * 100) ?>%"></div>
                        </div>
                        <p class="progresso-texto"><?= $totalConcluido ?> de <?= $totalHoje ?> concluídos hoje</p>
                    </div>

                    <div class="exercicios-lista-itens">
                        <?php if (empty($pendentesHojeInicio)): ?>
                            <p class="cronograma-sem-exercicio">Tudo feito por hoje!</p>
                        <?php else: ?>
                            <?php foreach ($pendentesHojeInicio as $ex): ?>
                                <div class="cronograma-exercicio-item" data-id-exercicio="<?= $ex['id'] ?>">
                                    <span class="material-symbols-outlined icon-exercicio-mini">fitness_center</span>
                                    <div class="cronograma-exercicio-info">
                                        <a href="exerciciodetalhe.php?id=<?= $ex['id'] ?>" class="cronograma-exercicio-nome">
                                            <?= htmlspecialchars($ex['titulo']) ?>
                                        </a>
                                        <span class="cronograma-tag"><?= htmlspecialchars($ex['regiao_nome']) ?></span>
                                    </div>
                                    <div class="cronograma-exercicio-acoes">
                                        <button type="button" class="btn-concluir" title="Marcar como concluído hoje">
                                            <span class="material-symbols-outlined">check_circle</span>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </section>

            <section class="inicio-card">
                <h2>Sua semana</h2>
                <div class="semana-dots">
                    <?php foreach ($diasDaSemanaGrid as $dia): ?>
                        <div class="dot-dia<?= $dia['ativo'] ? ' ativo' : '' ?><?= $dia['hoje'] ? ' hoje' : '' ?>">
                            <span class="dot-bolinha"></span>
                            <span class="dot-letra"><?= $dia['letra'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <p class="inicio-semana-resumo">
                    <?= $totalSemana ?> exercício<?= $totalSemana === 1 ? '' : 's' ?> concluído<?= $totalSemana === 1 ? '' : 's' ?> nos últimos 7 dias
                </p>
            </section>

            <section class="inicio-card" id="cardBemEstar">
                <h2>Como você está hoje?</h2>
                <div class="bemestar-opcoes">
                    <?php foreach ($opcoesBemEstar as $chave => $opcao): ?>
                        <button type="button"
                                class="bemestar-btn<?= $bemEstarHojeLabel === $opcao['label'] ? ' selecionado' : '' ?>"
                                data-chave="<?= $chave ?>">
                            <span class="bemestar-emoji"><?= $opcao['emoji'] ?></span>
                            <span class="bemestar-label"><?= $opcao['label'] ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
                <p class="inicio-bemestar-resumo" id="bemEstarResumo">
                    <?= $bemEstarHojeLabel ? 'Check-in de hoje: ' . htmlspecialchars($bemEstarHojeLabel) : 'Você ainda não fez o check-in de hoje.' ?>
                </p>
            </section>

        </div>
    </main>
</div>

<footer class="site-footer">
        <div class="footer-wrap">
            <div class="footer-top">

                <div class="footer-col">
                    <p class="lede">
                        Plataforma digital de apoio à reabilitação e fisioterapia,
                        feita para quem não tem tempo ou condições de manter
                        acompanhamento profissional frequente.
                    </p>
                </div>

                <div class="footer-col">
                    <h4>Navegação</h4>
                    <ul class="footer-links">
                        <li><a href="inicio.php"><span class="material-symbols-outlined">home</span>Início</a></li>
                        <li><a href="exercicio.php"><span class="material-symbols-outlined">health_and_safety</span>Exercícios</a></li>
                        <li><a href="cronograma.php"><span class="material-symbols-outlined">calendar_month</span>Cronograma</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Contato</h4>
                    <ul class="footer-links">
                        <li><a href="mailto:yumisperes@gmail.com" ><span class="material-symbols-outlined">mail</span>yumisperes@gmail.com</a></li>
                        <li><a href="tel:+5516997423129"><span class="material-symbols-outlined">call</span>(16) 99742-3120</a></li>
                    </ul>
                    <div class="social-row">
                        <a href="https://www.instagram.com/aliceespinelli_/" aria-label="Instagram" class="icon-footer"><i class="fa-brands fa-instagram"></i></a>
                        <a href="https://wa.me/5516997016732" aria-label="WhatsApp" class="icon-footer"><i class="fa-brands fa-whatsapp"></i></a>
                    </div>
                </div>

            </div>

            <div class="footer-bottom">
                <span>© 2026 Vitallis — Projeto de Trabalho de Conclusão de Curso</span>
                <span>Desenvolvido por Alice, João e Yumi</span>
            </div>
        </div>
    </footer>
</body>
</html>
