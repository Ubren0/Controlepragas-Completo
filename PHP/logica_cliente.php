<?php
require_once 'conexao.php';  // inclusão da conexão externa

// Exibir erros para debug (remova em produção)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verifica se foi solicitada exclusão
if (isset($_GET['acao']) && $_GET['acao'] === 'excluir' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM cliente WHERE ID_Cliente = ?");
    $stmt->execute([$id]);

    header("Location: ../HTML/clientes.php");
    exit;
}

// Consulta os dados da tabela cliente
try {
    $sql = "SELECT * FROM cliente ORDER BY Nome";
    $clientes_stmt = $pdo->prepare($sql);
    $clientes_stmt->execute();
} catch (PDOException $e) {
    die("Erro ao consultar os clientes: " . $e->getMessage());
}
