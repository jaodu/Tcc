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
                    <a href="login.php" class="nav-link">
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

    <section id="hero" class="hero-section">
        <img src="imagens/fundobranco.svg" class="fundo-hero" alt="background image">
        <div class="hero-content">
            <h1>A reabilitação não precisa esperar, <br>
                volte a viver sem dor.</h1>
            <p>O Vitallis nasceu para tornar a reabilitação mais acessível, você inicia sua recuperação 
                quando e onde quiser. A plataforma oferece exercícios personalizados, acompanhamento 
                da evolução e orientações para que você possa voltar às suas atividades com mais 
                segurança, autonomia e qualidade de vida, sem depender da disponibilidade de terceiros.</p>
            
            <a href="login.php" class="btn-hero" id="btn-hero">Comece agora</a>
        </div> 
    </section>
    
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
