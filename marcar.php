<?php
require_once 'conexao.php';
header('Content-Type: application/json');

$idUsuario   = (int) $_SESSION['usuario_id'];
$idExercicio = (int) ($_POST['id_exercicio'] ?? 0);
$concluido   = (isset($_POST['concluido']) && $_POST['concluido'] == '1') ? 1 : 0;

if ($idExercicio <= 0) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Exercício inválido.']);
    exit;
}

$hoje = date('Y-m-d');

$stmt = $conexao->prepare("
    SELECT id FROM atividade_usuario
    WHERE id_usuario = ? AND id_exercicio = ? AND data_atividade = ?
    LIMIT 1
");
$stmt->bind_param("iis", $idUsuario, $idExercicio, $hoje);
$stmt->execute();
$registro = $stmt->get_result()->fetch_assoc();

if ($registro) {
    $stmt = $conexao->prepare("UPDATE atividade_usuario SET concluido = ? WHERE id = ?");
    $stmt->bind_param("ii", $concluido, $registro['id']);
    $sucesso = $stmt->execute();
} else {
    $stmt = $conexao->prepare("
        INSERT INTO atividade_usuario (data_atividade, concluido, id_usuario, id_exercicio)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("siii", $hoje, $concluido, $idUsuario, $idExercicio);
    $sucesso = $stmt->execute();
}

if ($sucesso) {
    echo json_encode(['sucesso' => true, 'concluido' => (bool) $concluido]);
} else {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao salvar no banco de dados: ' . $conexao->error]);
}