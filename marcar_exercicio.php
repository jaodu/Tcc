<?php
require_once 'conexao.php';
header('Content-Type: application/json');

// Só usuários logados podem marcar exercícios como concluídos
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Você precisa estar logado.']);
    exit;
}

$idUsuario   = (int) $_SESSION['usuario_id'];
$idExercicio = (int) ($_POST['id_exercicio'] ?? 0);
$concluido   = (isset($_POST['concluido']) && $_POST['concluido'] == '1') ? 1 : 0;

if ($idExercicio <= 0) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Exercício inválido.']);
    exit;
}

$hoje = date('Y-m-d');

// Verifica se já existe um registro de hoje para esse usuário + exercício.
// Feito assim (SELECT, depois UPDATE ou INSERT) pra funcionar mesmo sem
// nenhuma UNIQUE KEY extra no banco.
$stmt = $conexao->prepare("
    SELECT id FROM atividade_usuario
    WHERE id_usuario = ? AND id_exercicio = ? AND data_atividade = ?
    LIMIT 1
");
$stmt->bind_param("iis", $idUsuario, $idExercicio, $hoje);
$stmt->execute();
$registro = $stmt->get_result()->fetch_assoc();

if ($registro) {
    // Já existe um registro de hoje: apenas atualiza o status
    $stmt = $conexao->prepare("UPDATE atividade_usuario SET concluido = ? WHERE id = ?");
    $stmt->bind_param("ii", $concluido, $registro['id']);
    $sucesso = $stmt->execute();
} else {
    // Ainda não existe: cria o registro de hoje
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