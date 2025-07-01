<?php
header('Content-Type: application/json');
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Método não permitido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['id']) || !isset($input['status'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados obrigatórios não fornecidos']);
    exit;
}

try {
    $sql = "UPDATE Servico SET StatusServico = ? WHERE IDservico = ?";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$input['status'], $input['id']]);
    
    if ($result) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Agendamento atualizado com sucesso']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar agendamento']);
    }
    
} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro: ' . $e->getMessage()]);
}
?>
