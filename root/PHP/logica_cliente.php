<?php
/**
 * Logica_Cliente.php
 * 
 * Arquivo responsável por todas as operações CRUD relacionadas aos clientes
 * Contém funções para criar, ler, atualizar e deletar registros de clientes
 */

// Configurações de desenvolvimento - remover em produção
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui a conexão com o banco de dados
require_once 'conexao.php';

// Inicia a sessão se ainda não foi iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Lista todos os clientes cadastrados no sistema
 * 
 * @param PDO $pdo - Conexão com o banco de dados
 * @return PDOStatement - Resultado da consulta para iteração
 * @throws PDOException - Em caso de erro na consulta
 */
function listaClientes($pdo) {
    $query = "SELECT ID_Cliente, Nome, TipoCliente, Telefone, Email FROM Cliente ORDER BY Nome";
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt;
    } catch (PDOException $e) {
        error_log("Erro ao buscar clientes: " . $e->getMessage());
        die("Erro ao buscar clientes: " . $e->getMessage());
    }
}

/**
 * Insere um novo cliente no sistema
 * 
 * @param PDO $pdo - Conexão com o banco de dados
 * @param string $nome - Nome completo do cliente
 * @param string $tipoCliente - Tipo: 'Pessoa Física' ou 'Pessoa Jurídica'
 * @param string $cpf_cnpj - Documento CPF ou CNPJ do cliente
 * @param string $telefone - Número de telefone para contato
 * @param string $email - Endereço de email do cliente
 * @param string $rua - Endereço: nome da rua e número
 * @param string $bairro - Bairro do endereço
 * @param string $cidade - Cidade do endereço
 * @param string $estado - Estado/UF do endereço
 * @param string $cep - Código postal do endereço
 * @return boolean - True se inserido com sucesso, False caso contrário
 */
function insereCliente($pdo, $nome, $tipoCliente, $cpf_cnpj, $telefone, $email, $rua, $bairro, $cidade, $estado, $cep) { 
    $query = "INSERT INTO Cliente (Nome, TipoCliente, CPFCNPJ, Telefone, Email, Rua, Bairro, Cidade, Estado, CEP) VALUES (:nome, :tipo, :cpf_cnpj, :telefone, :email, :rua, :bairro, :cidade, :estado, :cep)";
    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':tipo', $tipoCliente);
        $stmt->bindParam(':cpf_cnpj', $cpf_cnpj);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':rua', $rua);
        $stmt->bindParam(':bairro', $bairro);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':cep', $cep);
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Erro ao inserir cliente: " . $e->getMessage());
        return false;
    }
}

/**
 * Busca um cliente específico pelo seu ID
 * 
 * @param PDO $pdo - Conexão com o banco de dados
 * @param int $id - ID único do cliente a ser buscado
 * @return array|false - Array associativo com dados do cliente ou false se não encontrado
 */
function buscaClientePorId($pdo, $id) {
    $query = "SELECT * FROM Cliente WHERE ID_Cliente = :id";
    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erro ao buscar cliente por ID: " . $e->getMessage());
        return false;
    }
}

/**
 * Atualiza os dados de um cliente existente
 * 
 * @param PDO $pdo - Conexão com o banco de dados
 * @param int $id - ID do cliente a ser atualizado
 * @param string $nome - Novo nome do cliente
 * @param string $tipoCliente - Novo tipo de cliente
 * @param string $cpf_cnpj - Novo documento do cliente
 * @param string $telefone - Novo telefone do cliente
 * @param string $email - Novo email do cliente
 * @param string $rua - Nova rua do endereço
 * @param string $bairro - Novo bairro do endereço
 * @param string $cidade - Nova cidade do endereço
 * @param string $estado - Novo estado do endereço
 * @param string $cep - Novo CEP do endereço
 * @return boolean - True se atualizado com sucesso, False caso contrário
 */
function atualizaCliente($pdo, $id, $nome, $tipoCliente, $cpf_cnpj, $telefone, $email, $rua, $bairro, $cidade, $estado, $cep) {
    $query = "UPDATE Cliente SET Nome = :nome, TipoCliente = :tipo, CPFCNPJ = :cpf_cnpj, Telefone = :telefone, Email = :email, Rua = :rua, Bairro = :bairro, Cidade = :cidade, Estado = :estado, CEP = :cep WHERE ID_Cliente = :id";
    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':tipo', $tipoCliente);
        $stmt->bindParam(':cpf_cnpj', $cpf_cnpj);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':rua', $rua);
        $stmt->bindParam(':bairro', $bairro);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':cep', $cep);
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Erro ao atualizar cliente: " . $e->getMessage());
        return false;
    }
}

/**
 * Remove um cliente do sistema permanentemente
 * 
 * @param PDO $pdo - Conexão com o banco de dados
 * @param int $id - ID do cliente a ser excluído
 * @return boolean - True se excluído com sucesso, False caso contrário
 */
function excluiCliente($pdo, $id) {
    $query = "DELETE FROM Cliente WHERE ID_Cliente = :id";
    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Erro ao excluir cliente: " . $e->getMessage());
        return false;
    }
}

// --- ROTEAMENTO DAS AÇÕES ---

$acao = $_REQUEST['acao'] ?? ''; // Usar $_REQUEST para pegar tanto GET quanto POST

// Processa o cadastro de um novo cliente (via POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $acao === 'cadastrar') {
    $nome = $_POST['nome'] ?? null;
    $tipoCliente = $_POST['tipoCliente'] ?? null;
    $cpf_cnpj = $_POST['cpfcnpj'] ?? $_POST['cpf_cnpj'] ?? null; // Aceita ambos os nomes
    $telefone = $_POST['telefone'] ?? null;
    $email = $_POST['email'] ?? null;
    $rua = $_POST['rua'] ?? null;
    $bairro = $_POST['bairro'] ?? null;
    $cidade = $_POST['cidade'] ?? null;
    $estado = $_POST['estado'] ?? null;
    $cep = $_POST['cep'] ?? null;

    // Validação mais específica dos campos obrigatórios
    if (empty($nome) || empty($tipoCliente) || empty($cpf_cnpj)) {
        $camposFaltantes = [];
        if (empty($nome)) $camposFaltantes[] = 'Nome';
        if (empty($tipoCliente)) $camposFaltantes[] = 'Tipo de Cliente';
        if (empty($cpf_cnpj)) $camposFaltantes[] = 'CPF/CNPJ';
        
        $_SESSION['mensagem_erro'] = "Campos obrigatórios não preenchidos: " . implode(', ', $camposFaltantes);
        header('Location: ../HTML/cadastroCliente.php');
        exit();
    }

    if (insereCliente($pdo, $nome, $tipoCliente, $cpf_cnpj, $telefone, $email, $rua, $bairro, $cidade, $estado, $cep)) {
        $_SESSION['mensagem_sucesso'] = "Cliente cadastrado com sucesso!";
        header('Location: ../HTML/clientes.php');
        exit();
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao cadastrar o cliente.";
        header('Location: ../HTML/cadastroCliente.php');
        exit();
    }
}

// Processa a atualização de um cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $acao === 'editar') {
    $id = $_POST['idCliente'] ?? null;
    $nome = $_POST['nome'] ?? null;
    $tipoCliente = $_POST['tipoCliente'] ?? null;
    $cpf_cnpj = $_POST['cpfcnpj'] ?? null;
    $telefone = $_POST['telefone'] ?? null;
    $email = $_POST['email'] ?? null;
    $rua = $_POST['rua'] ?? null;
    $bairro = $_POST['bairro'] ?? null;
    $cidade = $_POST['cidade'] ?? null;
    $estado = $_POST['estado'] ?? null;
    $cep = $_POST['cep'] ?? null;

    if ($id && $nome && $tipoCliente && $cpf_cnpj) {
        if (atualizaCliente($pdo, $id, $nome, $tipoCliente, $cpf_cnpj, $telefone, $email, $rua, $bairro, $cidade, $estado, $cep)) {
            $_SESSION['mensagem_sucesso'] = "Cliente atualizado com sucesso!";
        } else {
            $_SESSION['mensagem_erro'] = "Erro ao atualizar o cliente.";
        }
    } else {
        $_SESSION['mensagem_erro'] = "Dados incompletos para atualização.";
    }
    header('Location: ../HTML/clientes.php');
    exit();
}

// Processa a exclusão de um cliente
if ($acao === 'excluir') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        if (excluiCliente($pdo, $id)) {
            $_SESSION['mensagem_sucesso'] = "Cliente excluído com sucesso!";
        } else {
            $_SESSION['mensagem_erro'] = "Erro ao excluir o cliente.";
        }
    } else {
        $_SESSION['mensagem_erro'] = "ID do cliente não fornecido.";
    }
    header('Location: ../HTML/clientes.php');
    exit();
}

// Se nenhuma ação específica for chamada, a ação padrão é listar os clientes.
// Esta variável será usada na página clientes.php
$clientes_stmt = listaClientes($pdo); // Correção: $conexao -> $pdo
?>
