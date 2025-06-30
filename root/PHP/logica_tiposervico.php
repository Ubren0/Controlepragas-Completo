<?php
/**
 * Logica_TipoServico.php
 * 
 * Arquivo responsável por gerenciar todos os tipos de serviços oferecidos
 * Inclui operações CRUD para cadastro, edição, listagem e exclusão de tipos de serviço
 */

require_once 'conexao.php';

// Inicia a sessão se ainda não foi iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Lista todos os tipos de serviço cadastrados no sistema
 * 
 * @param PDO $pdo - Conexão com o banco de dados
 * @return PDOStatement|false - Resultado da consulta ou false em caso de erro
 */
function listaTiposServico($pdo) {
    $query = "SELECT IDtiposervico, Nome, ValorBase, TempoEstimado FROM TipoServico ORDER BY Nome";
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt;
    } catch (PDOException $e) {
        error_log("Erro ao buscar tipos de serviço: " . $e->getMessage());
        return false;
    }
}

/**
 * Insere um novo tipo de serviço no sistema
 * 
 * @param PDO $pdo - Conexão com o banco de dados
 * @param string $nome - Nome descritivo do tipo de serviço
 * @param float $valor - Valor base cobrado pelo serviço
 * @param string $tempo_estimado - Tempo estimado para execução do serviço
 * @return boolean - True se inserido com sucesso, False caso contrário
 */
function insereTipoServico($pdo, $nome, $valor, $tempo_estimado) {
    $query = "INSERT INTO TipoServico (Nome, ValorBase, TempoEstimado) VALUES (:nome, :valor, :tempo)";
    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':valor', $valor);
        $stmt->bindParam(':tempo', $tempo_estimado);
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Erro ao inserir tipo de serviço: " . $e->getMessage());
        return false;
    }
}

/**
 * Busca um tipo de serviço específico pelo seu ID
 * 
 * @param PDO $pdo - Conexão com o banco de dados
 * @param int $id - ID único do tipo de serviço
 * @return array|false - Array associativo com dados do tipo de serviço ou false se não encontrado
 */
function buscaTipoServicoPorId($pdo, $id) {
    $query = "SELECT * FROM TipoServico WHERE IDtiposervico = :id";
    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erro ao buscar tipo de serviço por ID: " . $e->getMessage());
        return false;
    }
}

/**
 * Atualiza os dados de um tipo de serviço existente
 * 
 * @param PDO $pdo - Conexão com o banco de dados
 * @param int $id - ID do tipo de serviço a ser atualizado
 * @param string $nome - Novo nome do tipo de serviço
 * @param float $valor - Novo valor base do serviço
 * @param string $tempo_estimado - Novo tempo estimado de execução
 * @return boolean - True se atualizado com sucesso, False caso contrário
 */
function atualizaTipoServico($pdo, $id, $nome, $valor, $tempo_estimado) {
    $query = "UPDATE TipoServico SET Nome = :nome, ValorBase = :valor, TempoEstimado = :tempo WHERE IDtiposervico = :id";
    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':valor', $valor);
        $stmt->bindParam(':tempo', $tempo_estimado);
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Erro ao atualizar tipo de serviço: " . $e->getMessage());
        return false;
    }
}

/**
 * Remove um tipo de serviço do sistema permanentemente
 * 
 * @param PDO $pdo - Conexão com o banco de dados
 * @param int $id - ID do tipo de serviço a ser excluído
 * @return boolean - True se excluído com sucesso, False caso contrário
 */
function excluiTipoServico($pdo, $id) {
    $query = "DELETE FROM TipoServico WHERE IDtiposervico = :id";
    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Erro ao excluir tipo de serviço: " . $e->getMessage());
        return false;
    }
}

// ===============================================
// ROTEAMENTO E PROCESSAMENTO DE REQUISIÇÕES  
// ===============================================

$acao = $_REQUEST['acao'] ?? '';

// Cadastro de novo tipo de serviço
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $acao === 'cadastrar_tiposervico') {
    $nome = $_POST['nome'] ?? null;
    $valor = $_POST['valor'] ?? null;
    $tempo_estimado = $_POST['tempo_estimado'] ?? null;

    if ($nome && $valor && $tempo_estimado) {
        if (insereTipoServico($pdo, $nome, $valor, $tempo_estimado)) {
            $_SESSION['mensagem_sucesso'] = "Tipo de serviço cadastrado com sucesso!";
        } else {
            $_SESSION['mensagem_erro'] = "Erro ao cadastrar tipo de serviço.";
        }
    } else {
        $_SESSION['mensagem_erro'] = "Por favor, preencha todos os campos obrigatórios.";
    }
    header('Location: ../HTML/tipoServico.php');
    exit();
}

// Edição de tipo de serviço
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $acao === 'editar_tiposervico') {
    $id = $_POST['id'] ?? null;
    $nome = $_POST['nome'] ?? null;
    $valor = $_POST['valor'] ?? null;
    $tempo_estimado = $_POST['tempo_estimado'] ?? null;

    if ($id && $nome && $valor && $tempo_estimado) {
        if (atualizaTipoServico($pdo, $id, $nome, $valor, $tempo_estimado)) {
            $_SESSION['mensagem_sucesso'] = "Tipo de serviço atualizado com sucesso!";
        } else {
            $_SESSION['mensagem_erro'] = "Erro ao atualizar tipo de serviço.";
        }
    } else {
        $_SESSION['mensagem_erro'] = "Dados incompletos para atualização.";
    }
    header('Location: ../HTML/tipoServico.php');
    exit();
}

// Exclusão de tipo de serviço
if ($acao === 'excluir_tiposervico') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        if (excluiTipoServico($pdo, $id)) {
            $_SESSION['mensagem_sucesso'] = "Tipo de serviço excluído com sucesso!";
        } else {
            $_SESSION['mensagem_erro'] = "Erro ao excluir tipo de serviço.";
        }
    } else {
        $_SESSION['mensagem_erro'] = "ID do tipo de serviço não fornecido.";
    }
    header('Location: ../HTML/tipoServico.php');
    exit();
}

// Lista padrão de tipos de serviço
$tipos_servico_stmt = listaTiposServico($pdo);
?>
