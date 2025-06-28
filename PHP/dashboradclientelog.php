<?php
require_once 'conexao.php';  

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$idCliente = $_GET['id'] ?? null;
if (!$idCliente) {
    die("ID do cliente não informado.");
}

$msgSucesso = "";
$msgErro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['clienteNome'] ?? '';
    $cnpjcpf = $_POST['clienteCnpjCpf'] ?? '';
    $tipoContrato = $_POST['clienteTipoContrato'] ?? '';
    $dataInicio = $_POST['clienteDataInicio'] ?? null;
    $rua = $_POST['clienteRua'] ?? '';
    $bairro = $_POST['clienteBairro'] ?? '';
    $cidade = $_POST['clienteCidade'] ?? '';
    $estado = $_POST['clienteEstado'] ?? '';
    $cep = $_POST['clienteCep'] ?? '';
    $administradoraNome = $_POST['administradoraNome'] ?? '';
    $administradoraContato = $_POST['administradoraContato'] ?? '';
    $supervisorNome = $_POST['supervisorNome'] ?? '';
    $supervisorContato = $_POST['supervisorContato'] ?? '';
    $faturamento = $_POST['clienteFaturamento'] ?? 0;
    $formaPagamento = $_POST['clienteFormaPagamento'] ?? '';

    $sql = "UPDATE Cliente SET 
        Nome = ?, 
        CPFCNPJ = ?, 
        TipoContrato = ?, 
        DataInicio = ?, 
        Rua = ?, Bairro = ?, Cidade = ?, Estado = ?, CEP = ?, 
        AdministradoraNome = ?, AdministradoraContato = ?, SupervisorNome = ?, SupervisorContato = ?,
        ValorFaturamento = ?, FormaPagamento = ?
        WHERE ID_Cliente = ?";

    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([
            $nome, $cnpjcpf, $tipoContrato, $dataInicio,
            $rua, $bairro, $cidade, $estado, $cep,
            $administradoraNome, $administradoraContato, $supervisorNome, $supervisorContato,
            $faturamento, $formaPagamento,
            $idCliente
        ]);
        $msgSucesso = "Dados atualizados com sucesso!";
    } catch (PDOException $e) {
        $msgErro = "Erro ao atualizar cliente: " . $e->getMessage();
    }
}

$sql = "SELECT * FROM Cliente WHERE ID_Cliente = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$idCliente]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    die("Cliente não encontrado.");
}
