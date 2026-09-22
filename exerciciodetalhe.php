<?php
require_once 'conexao.php';

// Proteção da página: se o usuário não estiver logado, manda de volta para o login.php
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$idExercicio = (int) ($_GET['id'] ?? 0);

if ($idExercicio <= 0) {
    header("Location: exercicio.php");
    exit;
}

$stmt = $conexao->prepare("SELECT id, titulo, repeticao, cuidados, como_fazer, descricao FROM exercicio WHERE id = ?");
$stmt->bind_param("i", $idExercicio);
$stmt->execute();
$exercicio = $stmt->get_result()->fetch_assoc();

// Se o id não existir no banco, volta pra lista em vez de mostrar página quebrada
if (!$exercicio) {
    header("Location: exercicio.php");
    exit;
}

/**
 * Transforma um texto com linhas numeradas ("1. texto\n2. texto") em um array simples
 * de itens (sem a numeração). Usado nos campos "repeticao" e "cuidados".
 */
function parseListaSimples(string $texto): array
{
    $linhas = preg_split('/\r\n|\r|\n/', trim($texto));
    $itens = [];
    foreach ($linhas as $linha) {
        $linha = trim($linha);
        if ($linha === '') continue;
        $linha = preg_replace('/^\d+\.\s*/', '', $linha);
        $itens[] = $linha;
    }
    return $itens;
}

/**
 * Transforma o texto de "como_fazer" em blocos, cada um com um título opcional
 * (ex: "Posição inicial", "Movimento 'Gato'") e uma lista numerada de passos.
 * Segue o padrão já usado no banco: linhas sem número = título do bloco,
 * linhas "1. texto" = passo dentro do bloco atual.
 */
function parseComoFazer(string $texto): array
{
    $linhas = preg_split('/\r\n|\r|\n/', trim($texto));
    $blocos = [];
    $atual = null;

    foreach ($linhas as $linha) {
        $linha = trim($linha);
        if ($linha === '') continue;

        if (preg_match('/^\d+\.\s*(.+)/', $linha, $m)) {
            if ($atual === null) {
                $atual = ['titulo' => null, 'itens' => []];
            }
            $atual['itens'][] = $m[1];
        } else {
            if ($atual !== null) {
                $blocos[] = $atual;
            }
            $atual = ['titulo' => $linha, 'itens' => []];
        }
    }
    if ($atual !== null) {
        $blocos[] = $atual;
    }
    return $blocos;
}

/**
 * Procura imagens do exercício em imagens/exercicios/ seguindo o padrão {id}_{n}.{ext}
 * (ex: imagens/exercicios/5_1.png, imagens/exercicios/5_2.png).
 * Assim que vocês criarem a coluna/tabela de imagens no banco, é só trocar o corpo
 * desta função por uma leitura direta dos dados do exercício — o resto da página
 * continua funcionando igual, porque só espera receber um array de caminhos.
 */
function buscarImagensExercicio(int $id): array
{
    $extensoes = ['png', 'jpg', 'jpeg', 'webp'];
    $imagens = [];
    for ($i = 1; $i <= 3; $i++) {
        foreach ($extensoes as $ext) {
            $caminho = "imagens/exercicios/{$id}_{$i}.{$ext}";
            if (file_exists($caminho)) {
                $imagens[] = $caminho;
                break;
            }
        }
    }
    return $imagens;
}

$blocosComoFazer  = parseComoFazer($exercicio['como_fazer']);
$listaRepeticao   = parseListaSimples($exercicio['repeticao']);
$listaCuidados    = parseListaSimples($exercicio['cuidados']);
$imagensExercicio = buscarImagensExercicio($exercicio['id']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vitallis — <?= htmlspecialchars($exercicio['titulo']) ?></title>
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

    <main class="exercicio-main">
        <a href="exercicio.php" class="exercicio-voltar">
            <span class="material-symbols-outlined">arrow_back</span>
            Voltar para exercícios
        </a>

        <h1 class="exercicio-titulo">Exercício - <span><?= htmlspecialchars($exercicio['titulo']) ?></span></h1>

        <div class="exercicio-card exercicio-card-soft">
            <h3>Para que serve?</h3>
            <p><?= nl2br(htmlspecialchars($exercicio['descricao'])) ?></p>
        </div>

        <div class="exercicio-card">
            <h3>Como fazer</h3>
            <div class="como-fazer-wrap">
                <div class="como-fazer-texto">
                    <?php foreach ($blocosComoFazer as $bloco): ?>
                        <?php if ($bloco['titulo']): ?>
                            <h4><?= htmlspecialchars($bloco['titulo']) ?></h4>
                        <?php endif; ?>
                        <?php if (!empty($bloco['itens'])): ?>
                            <ol>
                                <?php foreach ($bloco['itens'] as $item): ?>
                                    <li><?= htmlspecialchars($item) ?></li>
                                <?php endforeach; ?>
                            </ol>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <div class="como-fazer-imagens">
                    <?php if (count($imagensExercicio) > 0): ?>
                        <?php foreach ($imagensExercicio as $img): ?>
                            <img src="<?= htmlspecialchars($img) ?>" alt="Ilustração do exercício <?= htmlspecialchars($exercicio['titulo']) ?>">
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="sem-imagem">
                            <span class="material-symbols-outlined">image</span>
                            Ilustração em breve
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="exercicio-card exercicio-card-soft">
            <h3>Repetições</h3>
            <ul class="exercicio-lista">
                <?php foreach ($listaRepeticao as $item): ?>
                    <li><?= htmlspecialchars($item) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="exercicio-card exercicio-card-soft">
            <h3>Cuidados</h3>
            <ul class="exercicio-lista">
                <?php foreach ($listaCuidados as $item): ?>
                    <li><?= htmlspecialchars($item) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </main>
    </div>

    <footer class="site-footer">
        <div class="footer-wrap">
            <div class="footer-top">

                <div class="footer-col">
                    <div class="footer-brand">
                        <span class="dot"></span>
                        <span>Vitallis</span>
                    </div>
                    <p class="lede">
                        Plataforma digital de apoio à reabilitação e fisioterapia,
                        feita para quem não tem tempo ou condições de manter
                        acompanhamento profissional frequente.
                    </p>
                </div>

                <div class="footer-col">
                    <h4>Navegação</h4>
                    <ul class="footer-links">
                        <li><a href="index.php"><span class="material-symbols-outlined">home</span>Início</a></li>
                        <li><a href="exercicio.php"><span class="material-symbols-outlined">health_and_safety</span>Exercícios</a></li>
                        <li><a href="cronograma.php"><span class="material-symbols-outlined">calendar_month</span>Cronograma</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Institucional</h4>
                    <ul class="footer-links">
                        <li><a href="sobre.php"><span class="material-symbols-outlined">info</span>Sobre nós</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Contato</h4>
                    <ul class="footer-links">
                        <li><a href="mailto:contato@vitallis.com"><span class="material-symbols-outlined">mail</span>contato@vitallis.com</a></li>
                        <li><a href="tel:+5516999999999"><span class="material-symbols-outlined">call</span>(16) 99999-9999</a></li>
                    </ul>
                    <div class="social-row">
                        <a href="#" aria-label="Instagram" class="icon-footer"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" aria-label="WhatsApp" class="icon-footer"><i class="fa-brands fa-whatsapp"></i></a>
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
