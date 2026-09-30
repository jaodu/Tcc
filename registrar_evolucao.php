<?php
require_once 'conexao.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Você precisa estar logado.']);
    exit;
}

$idUsuario = (int) $_SESSION['usuario_id'];
$chave     = $_POST['chave'] ?? '';

// Mapeamento fixo: o "progresso" (0-100) é derivado da carinha escolhida,
// nunca aceito direto do cliente, pra não deixar a pessoa mandar qualquer valor.
$opcoes = [
    'otimo'   => ['label' => 'Ótimo',   'progresso' => 100],
    'bom'     => ['label' => 'Bom',     'progresso' => 75],
    'regular' => ['label' => 'Regular', 'progresso' => 50],
    'ruim'    => ['label' => 'Ruim',    'progresso' => 25],
];

if (!isset($opcoes[$chave])) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Opção inválida.']);
    exit;
}

$bemEstar  = $opcoes[$chave]['label'];
$progresso = $opcoes[$chave]['progresso'];
$hoje      = date('Y-m-d');

// Verifica se já existe um check-in de hoje pra esse usuário
$stmt = $conexao->prepare("SELECT id FROM evolucao WHERE id_usuario = ? AND data_registro = ? LIMIT 1");
$stmt->bind_param("is", $idUsuario, $hoje);
$stmt->execute();
$registro = $stmt->get_result()->fetch_assoc();

if ($registro) {
    $stmt = $conexao->prepare("UPDATE evolucao SET bem_estar = ?, progresso = ? WHERE id = ?");
    $stmt->bind_param("sii", $bemEstar, $progresso, $registro['id']);
    $sucesso = $stmt->execute();
} else {
    $stmt = $conexao->prepare("
        INSERT INTO evolucao (data_registro, bem_estar, progresso, id_usuario)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("ssii", $hoje, $bemEstar, $progresso, $idUsuario);
    $sucesso = $stmt->execute();
}

if ($sucesso) {
    echo json_encode(['sucesso' => true, 'bem_estar' => $bemEstar]);
} else {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao salvar no banco de dados: ' . $conexao->error]);
}