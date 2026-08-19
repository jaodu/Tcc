<?php
require_once 'conexao.php';

$mensagem_erro = "";
$mensagem_sucesso = "";

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    // --- LÓGICA DE LOGIN ---
    if ($acao === 'login') {
        $email = trim($_POST['email_login']);
        $senha = trim($_POST['senha_login']);

        if (!empty($email) && !empty($senha)) {
            $sql = "SELECT * FROM usuario WHERE email = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($usuario = $resultado->fetch_assoc()) {
                if (password_verify($senha, $usuario['senha'])) {
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['usuario_email'] = $usuario['email'];
                    
                    // Redireciona para a página inicio.php
                    header("Location: inicio.php"); 
                    exit;
                } else {
                    $mensagem_erro = "Senha incorreta!";
                }
            } else {
                $mensagem_erro = "E-mail não encontrado!";
            }
        } else {
            $mensagem_erro = "Preencha todos os campos do login!";
        }
    }

    // --- LÓGICA DE CADASTRO ---
    if ($acao === 'cadastro') {
        $email = trim($_POST['email_cad']);
        $senha = trim($_POST['senha_cad']);
        $confirma_senha = trim($_POST['confirma_senha_cad']);

        if (!empty($email) && !empty($senha) && !empty($confirma_senha)) {
            if ($senha !== $confirma_senha) {
                $mensagem_erro = "As senhas não coincidem!";
            } else {
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

                $sql = "INSERT INTO usuario (email, senha) VALUES (?, ?)";
                $stmt = $conexao->prepare($sql);
                $stmt->bind_param("ss", $email, $senha_hash);

                if ($stmt->execute()) {
                    $mensagem_sucesso = "Cadastro realizado com sucesso! Faça seu login.";
                } else {
                    $mensagem_erro = "Erro ao cadastrar. Talvez este e-mail já esteja cadastrado.";
                }
            }
        } else {
            $mensagem_erro = "Preencha todos os campos do cadastro!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Entrar | Cadastrar-se</title>

    <style>
        body {
            font-family: 'Inter', 'Gill Sans', Calibri, sans-serif;
            background-color: #ebebeb;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 1rem;
        }
    </style>
</head>

<body>

    <div class="container-geral">
        <div class="formContainer">

            <!-- FORMULÁRIO DE LOGIN -->
            <div id="loginContainer" class="loginContainer">
                <form method="post">
                    <input type="hidden" name="acao" value="login">
                    <img src="imagens/LOGO.8.svg" class="logo" alt="logo">
                    <h1 class="entrar">Entrar</h1>
                    <hr>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email_login" placeholder="Email" required>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="senha_login" placeholder="Senha" required>
                    </div>
                    <a href="#" class="esqueceu">Esqueceu sua senha?</a>
                    <hr>
                    <button class="loginButton" type="submit">Entrar</button>
                    <p class="semcadastro">
                        Não tem conta?
                        <button type="button" class="btnircadastro" id="ir-para-cadastro">Cadastre-se</button>
                    </p>
                </form>
            </div>

            <!-- FORMULÁRIO DE CADASTRO -->
            <div id="signContainer" class="signContainer">
                <form method="post">
                    <input type="hidden" name="acao" value="cadastro">
                    <img src="imagens/LOGO.8.svg" class="logo" alt="logo">
                    <h1 class="cadastro">Cadastro</h1>
                    <hr>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email_cad" placeholder="Email" required>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="senha_cad" placeholder="Senha" required>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="confirma_senha_cad" placeholder="Confirme sua senha" required>
                    </div>
                    <hr>
                    <button class="signButton" type="submit">Cadastre-se</button>
                    <p class="comcadastro">
                        Já tem conta?
                        <button type="button" class="btnirlogin" id="ir-para-login">Entrar</button>
                    </p>
                </form>
            </div>

            <div id="semloginContainer" class="semloginContainer">
                <div class="semlogin">
                    <h1 id="h1" class="naotem">Ainda não tem cadastro?</h1>
                    <p class="pcontainer" id="pcontainer">Registre-se com seus dados pessoais para usar todos os recursos do nosso site.</p>
                    <button class="newButton" id="newButton" type="button">Criar conta</button>
                </div>
            </div>

        </div>
    </div>

    <!-- Scripts JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/script.js"></script>

    <!-- Disparo dos Popups do SweetAlert2 via PHP -->
    <script>
        <?php if (!empty($mensagem_sucesso)): ?>
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: '<?php echo $mensagem_sucesso; ?>',
                confirmColor: '#4CAF50'
            });
        <?php endif; ?>

        <?php if (!empty($mensagem_erro)): ?>
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: '<?php echo $mensagem_erro; ?>',
                confirmColor: '#d33'
            });
        <?php endif; ?>
    </script>
</body>

</html>
