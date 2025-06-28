<?php
require_once 'conexao.php';  
// Ativa exibição de erros para debug (remova em produção)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verifica se foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cnpjcpf = trim($_POST['cnpjcpf'] ?? '');
    $nome = trim($_POST['nome'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($cnpjcpf) || empty($nome)) {
        die("CNPJ/CPF e Nome são obrigatórios.");
    }

    // Verifica duplicidade de CNPJ/CPF
    $sql_check = "SELECT COUNT(*) FROM Cliente WHERE CPFCNPJ = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$cnpjcpf]);
    if ($stmt_check->fetchColumn() > 0) {
        die("Erro: Já existe um cliente cadastrado com esse CNPJ/CPF.");
    }

    // Insere o cliente
    $sql = "INSERT INTO Cliente (CPFCNPJ, Nome, Telefone, Email) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([$cnpjcpf, $nome, $telefone, $email]);
        // Redireciona para a página de clientes após salvar
        header("Location: ../HTML/clientes.php");
        exit;
    } catch (PDOException $e) {
        die("Erro ao salvar cliente: " . $e->getMessage());
    }
} else {
    die("Acesso inválido.");
}
