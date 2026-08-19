<?php
// Inicia a sessão para permitir login em todo o site
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$db   = 'bd_vitallis';
$user = 'root';
$pass = ''; 

// Oculta avisos nativos do PHP para lidarmos com o erro manualmente
mysqli_report(MYSQLI_REPORT_OFF);

// Cria a conexão com o MySQL
$conexao = @new mysqli($host, $user, $pass, $db);

// Verifica se houve falha na conexão
if ($conexao->connect_error) {
    die("Erro ao conectar ao banco de dados: " . $conexao->connect_error);
}
?>
