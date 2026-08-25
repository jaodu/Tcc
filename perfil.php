<?php
require_once 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$idUsuario = (int) $_SESSION['usuario_id'];
$mensagem_sucesso = "";
$mensagem_erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome   = trim($_POST['nome'] ?? '');
    $senha  = trim($_POST['senha'] ?? '');
    $idade  = (int) ($_POST['idade'] ?? 0);
    $altura = (float) ($_POST['altura'] ?? 0);
    $peso   = (float) ($_POST['peso'] ?? 0);
    $dores  = array_filter(array_map('intval', explode(',', $_POST['dores'] ?? '')));

    if ($nome === '' || $email === '') {
        $mensagem_erro = "Nome e e-mail são obrigatórios.";
    } else {
        $dataNascimento = (date('Y') - $idade) . '-01-01';

        $conexao->begin_transaction();
        try {
            if ($senha !== '') {
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                $stmt = $conexao->prepare("UPDATE usuario SET email = ?, senha = ? WHERE id = ?");
                $stmt->bind_param("ssi", $email, $senhaHash, $idUsuario);
            } else {
                $stmt = $conexao->prepare("UPDATE usuario SET email = ? WHERE id = ?");
                $stmt->bind_param("si", $email, $idUsuario);
            }
            $stmt->execute();

            $stmt = $conexao->prepare("
                INSERT INTO perfil (nome, data_nascimento, peso, altura, id_usuario)
                VALUES (?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE nome = VALUES(nome), data_nascimento = VALUES(data_nascimento),
                    peso = VALUES(peso), altura = VALUES(altura)
            ");
            $stmt->bind_param("ssddi", $nome, $dataNascimento, $peso, $altura, $idUsuario);
            $stmt->execute();

            $stmt = $conexao->prepare("UPDATE registro_dor SET ativa = 0 WHERE id_usuario = ?");
            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();

            $stmt = $conexao->prepare("
                INSERT INTO registro_dor (data_registro, ativa, id_usuario, id_regiao_corpo)
                VALUES (CURDATE(), 1, ?, ?)
                ON DUPLICATE KEY UPDATE ativa = 1
            ");
            foreach ($dores as $idRegiao) {
                $stmt->bind_param("ii", $idUsuario, $idRegiao);
                $stmt->execute();
            }

            $conexao->commit();
            $mensagem_sucesso = "Informações salvas com sucesso!";
        } catch (Exception $e) {
            $conexao->rollback();
            $mensagem_erro = "Erro ao salvar. Tente novamente.";
        }
    }
}

$stmt = $conexao->prepare("SELECT email FROM usuario WHERE id = ?");
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

$stmt = $conexao->prepare("SELECT nome, data_nascimento, peso, altura FROM perfil WHERE id_usuario = ?");
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$perfil = $stmt->get_result()->fetch_assoc();

$nomeAtual   = $perfil['nome'] ?? '';
$pesoAtual   = $perfil['peso'] ?? '';
$alturaAtual = $perfil['altura'] ?? '';
$idadeAtual  = !empty($perfil['data_nascimento'])
    ? (new DateTime())->diff(new DateTime($perfil['data_nascimento']))->y
    : 0;

$regioes = $conexao->query("SELECT id, nome FROM regiao_corpo ORDER BY id")->fetch_all(MYSQLI_ASSOC);

$stmt = $conexao->prepare("SELECT id_regiao_corpo FROM registro_dor WHERE id_usuario = ? AND ativa = 1");
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$doresAtivas = array_column($stmt->get_result()->fetch_all(MYSQLI_ASSOC), 'id_regiao_corpo');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vitallis — Perfil</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/script.js" defer></script>
    <style> 
body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    font-family: 'Inter', sans-serif;
    background-color: var(--fundo);
}
</style>
</head>
<body>
    <div class="body-header">
        <header class="header">
            <a href="index.php" class="vt">
                <img src="imagens/logoobranca.svg" class="logonav" alt="">
            </a>
            <nav>
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="inicio.php" class="nav-link">
                            <div class="icon"><span class="material-symbols-outlined">home</span></div>
                            <span class="label">Início</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="exercicio.php" class="nav-link">
                            <div class="icon"><span class="material-symbols-outlined">exercise</span></div>
                            <span class="label">Exercícios</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="cronograma.php" class="nav-link">
                            <div class="icon"><span class="material-symbols-outlined">calendar_month</span></div>
                            <span class="label">Cronograma</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown-item" id="perfilItem">
                        <a href="#" class="nav-link" id="perfilToggle">
                            <span class="material-symbols-outlined">account_circle</span>
                        </a>
                        <ul class="dropdown-menu" id="perfilDropdown">
                            <li><a href="perfil.php" class="dropdown-link">
                                <span class="material-symbols-outlined">person</span><span>Ver perfil</span>
                            </a></li>
                            <li><a href="logout.php" class="dropdown-link" id="logoutBtn">
                                <span class="material-symbols-outlined">logout</span><span>Sair</span>
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </header>
    </div>

    <main class="perfil-main">
        <form method="post" class="perfil-form">

            <div class="perfil-card perfil-header-card">
                <div class="perfil-avatar">
                    <span class="material-symbols-outlined">account_circle</span>
                </div>
                <div class="perfil-header-info">
                    <h2><?= htmlspecialchars($nomeAtual ?: 'Nome Completo') ?></h2>
                    <p><?= $idadeAtual ?> anos</p>
                </div>
                <button type="button" class="btn-editar-perfil" id="btnEditarPerfil">Editar perfil</button>
            </div>

            <div class="perfil-card">
                <h3 class="perfil-section-title">Editar Informações</h3>

                <div class="perfil-input-locked">
                    <input type="text" name="nome" value="<?= htmlspecialchars($nomeAtual) ?>" placeholder="Nome Completo" disabled>
                    <span class="material-symbols-outlined">lock</span>
                </div>
                
                <div class="perfil-input-locked">
                    <input type="password" name="senha" placeholder="Nova senha (deixe em branco para manter)" disabled>
                    <span class="material-symbols-outlined">lock</span>
                </div>

                <div class="perfil-steppers">
                    <div class="stepper-group">
                        <label>Idade</label>
                        <div class="stepper">
                            <button type="button" class="step-btn minus" data-target="idade">−</button>
                            <input type="number" id="idade" name="idade" value="<?= $idadeAtual ?>" disabled>
                            <button type="button" class="step-btn plus" data-target="idade">+</button>
                        </div>
                    </div>
                    <div class="stepper-group">
                        <label>Altura (cm)</label>
                        <div class="stepper">
                            <button type="button" class="step-btn minus" data-target="altura">−</button>
                            <input type="number" id="altura" name="altura" value="<?= htmlspecialchars($alturaAtual) ?>" disabled>
                            <button type="button" class="step-btn plus" data-target="altura">+</button>
                        </div>
                    </div>
                    <div class="stepper-group">
                        <label>Peso (kg)</label>
                        <div class="stepper">
                            <button type="button" class="step-btn minus" data-target="peso" data-step="0.1">−</button>
                            <input type="number" step="0.1" id="peso" name="peso" value="<?= htmlspecialchars($pesoAtual) ?>" disabled>
                            <button type="button" class="step-btn plus" data-target="peso" data-step="0.1">+</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="perfil-card">
                <h3 class="perfil-section-title">Principais dores</h3>
                <div class="dores-pills">
                    <?php foreach ($regioes as $regiao): ?>
                        <button
                            type="button"
                            class="dor-pill <?= in_array($regiao['id'], $doresAtivas) ? 'selected' : '' ?>"
                            data-id="<?= $regiao['id'] ?>"
                            disabled>
                            <?= htmlspecialchars($regiao['nome']) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="dores" id="doresInput" value="<?= implode(',', $doresAtivas) ?>">
            </div>

            <div class="perfil-salvar-wrap">
                <button type="submit" class="btn-salvar-perfil" id="btnSalvarPerfil" disabled>Salvar informações</button>
            </div>
        </form>
    </main>

    <script>
        <?php if ($mensagem_sucesso): ?>
            Swal.fire({ icon: 'success', title: 'Sucesso!', text: '<?= addslashes($mensagem_sucesso) ?>', confirmButtonColor: '#178f8d' });
        <?php elseif ($mensagem_erro): ?>
            Swal.fire({ icon: 'error', title: 'Ops...', text: '<?= addslashes($mensagem_erro) ?>', confirmButtonColor: '#178f8d' });
        <?php endif; ?>
    </script>

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
