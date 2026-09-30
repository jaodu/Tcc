<?php
require_once 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$idExercicio = (int) ($_GET['id'] ?? 0);

if ($idExercicio <= 0) {
    header("Location: exercicio.php");
    exit;
}

$stmt = $conexao->prepare("SELECT id, titulo, repeticao, cuidados, como_fazer, descricao, imagem FROM exercicio WHERE id = ?");
$stmt->bind_param("i", $idExercicio);
$stmt->execute();
$exercicio = $stmt->get_result()->fetch_assoc();

if (!$exercicio) {
    header("Location: exercicio.php");
    exit;
}

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

function buscarImagensExercicio(array $exercicio): array
{
    if (!empty($exercicio['imagem'])) {
        $caminho = "imagens/exercicios/" . $exercicio['imagem'];
        return [$caminho]; 
    }
    return [];
}

$blocosComoFazer  = parseComoFazer($exercicio['como_fazer']);
$listaRepeticao   = parseListaSimples($exercicio['repeticao']);
$listaCuidados    = parseListaSimples($exercicio['cuidados']);
$imagensExercicio = buscarImagensExercicio($exercicio);
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
        <a href="inicio.php" class="vt">
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
