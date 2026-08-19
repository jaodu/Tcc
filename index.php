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
        <a href="index.php" class="vt">
            <img src="imagens/LOGO.8.svg" class="logonav" alt="Logo Vitallis">
            <span class="brand-name">Vitallis</span>
        </a>

        <nav>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="index.php" class="nav-link">
                        <div class="icon">
                            <span class="material-symbols-outlined">home</span>
                        </div>
                        <span class="label">Início</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="login.php" class="nav-link">
                        <div class="icon">
                            <span class="material-symbols-outlined">exercise</span>
                        </div>
                        <span class="label">Exercícios</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="login.php" class="nav-link">
                        <div class="icon">
                            <span class="material-symbols-outlined">calendar_month</span>
                        </div>
                        <span class="label">Cronograma</span>
                    </a>
                </li>

                <!-- Botão 'Entrar' sem dropdown -->
                <li class="nav-item">
                    <a href="login.php" class="nav-link btn-entrar-nav">
                        <div class="icon">
                            <span class="material-symbols-outlined">login</span>
                        </div>
                        <span class="label">Entrar</span>
                    </a>
                </li>
            </ul>
        </nav>
    </header>
</div>

    <!-- SEÇÃO HERO -->
    <section id="hero" class="hero-section">
        <img src="imagens/fundobranco.svg" class="fundo-hero" alt="background image">
        <div class="hero-content">
            <h1>A reabilitação não precisa esperar, <br>
                volte a viver sem dor.</h1>
            <p>O Vitallis nasceu para tornar a reabilitação mais acessível, você inicia sua recuperação 
                quando e onde quiser. A plataforma oferece exercícios personalizados, acompanhamento 
                da evolução e orientações para que você possa voltar às suas atividades com mais 
                segurança, autonomia e qualidade de vida, sem depender da disponibilidade de terceiros.</p>
            
            <!-- Botão modificado para linkar direto para login.php -->
            <a href="login.php" class="btn-hero" id="btn-hero">Comece agora</a>
        </div> 
    </section>

    <!-- RODAPÉ -->
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
                        <li><a href="login.php"><span class="material-symbols-outlined">health_and_safety</span>Exercícios</a></li>
                        <li><a href="login.php"><span class="material-symbols-outlined">calendar_month</span>Cronograma</a></li>
                        <li><a href="login.php"><span class="material-symbols-outlined">login</span>Entrar</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Institucional</h4>
                    <ul class="footer-links">
                        <li><a href="sobre.html"><span class="material-symbols-outlined">info</span>Sobre nós</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Contato</h4>
                    <ul class="footer-links">
                        <li><a href="mailto:contato@vitallis.com"><span class="material-symbols-outlined">mail</span>contato@vitallis.com</a></li>
                        <li><a href="tel:+5516999999999"><span class="material-symbols-outlined">call</span>(16) 99999-9999</a></li>
                    </ul>
                    <div class="social-row">
                        <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
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
