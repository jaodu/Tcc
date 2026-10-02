<?php
require_once 'conexao.php';

// Proteção da página: se o usuário não estiver logado, manda de volta para o login.php
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$idUsuario = (int) $_SESSION['usuario_id'];

// Busca as regiões do corpo em que o usuário marcou dor ativa no perfil
$stmt = $conexao->prepare("
    SELECT rc.id, rc.nome
    FROM registro_dor rd
    JOIN regiao_corpo rc ON rc.id = rd.id_regiao_corpo
    WHERE rd.id_usuario = ? AND rd.ativa = 1
    ORDER BY rc.nome ASC
");
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$regioes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Busca quais exercícios o usuário já marcou como concluídos HOJE.
// Usa o registro mais recente de cada exercício (MAX(id)) pra não se confundir
// caso existam linhas duplicadas antigas na atividade_usuario.
$hoje = date('Y-m-d');
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

// Nosso banco usa 1 = Domingo ... 7 = Sábado (mesmo padrão do DAYOFWEEK do MySQL)
$diasSemana = [
    1 => 'Domingo',
    2 => 'Segunda',
    3 => 'Terça',
    4 => 'Quarta',
    5 => 'Quinta',
    6 => 'Sexta',
    7 => 'Sábado',
];
$diaHojeBanco = ((int) date('w')) + 1; // date('w') = 0 (dom) .. 6 (sáb)

// Monta UM cronograma só: junta os exercícios de TODAS as regiões com dor
// ativa, agrupados por dia da semana (ex: domingo puxa exercícios da cabeça
// e dos pés juntos, se o usuário tiver dor nas duas regiões).
//
// Pra não ficar gigante quando o usuário marca várias dores: se só tem 1
// região ativa, mostra todos os exercícios daquele dia; se tem mais de uma,
// mostra só 1 exercício de cada região por dia.
$porDia = [];
if (!empty($regioes)) {
    $idsRegioes    = array_column($regioes, 'id');
    $limitarUmPorRegiao = count($idsRegioes) > 1;
    $placeholders  = implode(',', array_fill(0, count($idsRegioes), '?'));
    $tiposParam    = str_repeat('i', count($idsRegioes));

    $sql = "
        SELECT c.dia, c.id_regiao_corpo, e.id, e.titulo, rc.nome AS regiao_nome
        FROM cronograma c
        JOIN exercicio e ON e.id = c.id_exercicio
        JOIN regiao_corpo rc ON rc.id = c.id_regiao_corpo
        WHERE c.id_regiao_corpo IN ($placeholders)
        ORDER BY c.dia ASC, c.id_regiao_corpo ASC, e.titulo ASC
    ";
    $stmtExercicios = $conexao->prepare($sql);
    $stmtExercicios->bind_param($tiposParam, ...$idsRegioes);
    $stmtExercicios->execute();
    $linhas = $stmtExercicios->get_result()->fetch_all(MYSQLI_ASSOC);

    // Agrupa primeiro por dia + região, depois decide quantos exercícios
    // de cada região entram na lista final daquele dia.
    $porDiaRegiao = [];
    foreach ($linhas as $linha) {
        $porDiaRegiao[(int) $linha['dia']][(int) $linha['id_regiao_corpo']][] = $linha;
    }

    foreach ($porDiaRegiao as $numDia => $porRegiao) {
        foreach ($porRegiao as $exerciciosDaRegiao) {
            $selecionados = $limitarUmPorRegiao ? array_slice($exerciciosDaRegiao, 0, 1) : $exerciciosDaRegiao;
            foreach ($selecionados as $ex) {
                $porDia[$numDia][] = $ex;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vitallis — Cronograma</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="js/script.js" defer></script>

    <style>
body {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background: #ebebeb;
}
    </style>

</head>
<body>
    <div class="body-header">
    <header class="header">
        <a href="index.php" class="vt">
            <img src="imagens/logoobranca.svg" class="logonav" alt="Logo Vitallis">
        </a>

        <nav>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="inicio.php" class="nav-link">
                        <div class="icon">
                            <span class="material-symbols-outlined">home</span>
                        </div>
                        <span class="label">Início</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="exercicio.php" class="nav-link">
                        <div class="icon">
                            <span class="material-symbols-outlined">exercise</span>
                        </div>
                        <span class="label">Exercícios</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="cronograma.php" class="nav-link">
                        <div class="icon">
                            <span class="material-symbols-outlined">calendar_month</span>
                        </div>
                        <span class="label">Cronograma</span>
                    </a>
                </li>

                <li class="nav-item dropdown-item" id="perfilItem">
                    <a href="#" class="nav-link" id="perfilToggle">
                        <span class="material-symbols-outlined">account_circle</span>
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

    <main class="cronograma-main">
        <h1 class="titulo-pagina">Seu Cronograma</h1>
        <p class="subtitulo-pagina">
            Exercícios organizados por dia da semana, de acordo com as regiões de dor
            marcadas no seu perfil. Clique em um dia para expandir.
        </p>

        <?php if (empty($regioes)): ?>
            <div class="cronograma-vazio">
                <span class="material-symbols-outlined">event_busy</span>
                <p>Você ainda não marcou nenhuma região com dor no seu perfil, por isso não
                    temos exercícios para montar seu cronograma.</p>
                <a href="perfil.php" class="btn-hero">Ir para o perfil</a>
            </div>
        <?php else: ?>
            <div class="cronograma-board">
                <?php foreach ($diasSemana as $numDia => $nomeDia): ?>
                    <?php
                        $ehHoje = $numDia === $diaHojeBanco;
                        $exerciciosDoDia = $porDia[$numDia] ?? [];
                        $totalDoDia = count($exerciciosDoDia);

                        if ($ehHoje) {
                            $pendentesHoje = array_values(array_filter(
                                $exerciciosDoDia,
                                fn($ex) => !in_array((int) $ex['id'], $concluidosHoje, true)
                            ));
                            $concluidosContagem = $totalDoDia - count($pendentesHoje);
                        }
                    ?>
                    <div class="cronograma-dia<?= $ehHoje ? ' cronograma-dia-hoje' : '' ?>">
                        <button type="button" class="cronograma-dia-header">
                            <span><?= $nomeDia ?><?= $ehHoje ? ' · hoje' : '' ?></span>
                            <span class="material-symbols-outlined cronograma-chevron">expand_circle_down</span>
                        </button>

                        <div class="cronograma-dia-lista">
                            <?php if ($ehHoje && $totalDoDia > 0): ?>
                                <div class="progresso-hoje" data-concluido="<?= $concluidosContagem ?>" data-total="<?= $totalDoDia ?>">
                                    <div class="barra-progresso-wrap">
                                        <div class="barra-progresso-fill" style="width: <?= round($concluidosContagem / $totalDoDia * 100) ?>%"></div>
                                    </div>
                                    <p class="progresso-texto"><?= $concluidosContagem ?> de <?= $totalDoDia ?> concluídos hoje</p>
                                </div>
                            <?php endif; ?>

                            <div class="exercicios-lista-itens">
                                <?php if ($totalDoDia === 0): ?>
                                    <p class="cronograma-sem-exercicio">Nenhum exercício cadastrado para este dia.</p>
                                <?php elseif ($ehHoje && empty($pendentesHoje)): ?>
                                    <p class="cronograma-sem-exercicio">Tudo feito por hoje!</p>
                                <?php else: ?>
                                    <?php foreach (($ehHoje ? $pendentesHoje : $exerciciosDoDia) as $ex): ?>
                                        <div class="cronograma-exercicio-item" data-id-exercicio="<?= $ex['id'] ?>">
                                            <span class="material-symbols-outlined icon-exercicio-mini">fitness_center</span>
                                            <div class="cronograma-exercicio-info">
                                                <a href="exerciciodetalhe.php?id=<?= $ex['id'] ?>" class="cronograma-exercicio-nome">
                                                    <?= htmlspecialchars($ex['titulo']) ?>
                                                </a>
                                                <span class="cronograma-tag"><?= htmlspecialchars($ex['regiao_nome']) ?></span>
                                            </div>
                                            <?php if ($ehHoje): ?>
                                                <div class="cronograma-exercicio-acoes">
                                                    <button type="button" class="btn-concluir" title="Marcar como concluído hoje">
                                                        <span class="material-symbols-outlined">check_circle</span>
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
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