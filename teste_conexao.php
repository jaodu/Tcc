<?php
$host = 'localhost';
$db   = 'bd_vitallis'; // Nome do banco que você importou/criou no MySQL
$user = 'root';
$pass = ''; 

// Desativa o alerta visual padrão do PHP para lidarmos com o erro de forma amigável
mysqli_report(MYSQLI_REPORT_OFF);

$conexao = @new mysqli($host, $user, $pass, $db);

if ($conexao->connect_error) {
    echo "<h2 style='color: red;'>❌ Falha na conexão com o MySQL!</h2>";
    echo "<p><strong>Erro:</strong> " . $conexao->connect_error . "</p>";
} else {
    echo "<h2 style='color: green;'>✅ Conexão realizada com sucesso!</h2>";
    echo "<p>O PHP conseguiu se conectar ao banco de dados <strong>$db</strong>.</p>";
}

$conexao->close();
?>
