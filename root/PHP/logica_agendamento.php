<?php
/**
 * Logica_Agendamento.php
 * 
 * Arquivo responsável por gerenciar todas as operações relacionadas aos agendamentos
 * Inclui criação, listagem e manipulação de agendamentos, orçamentos e serviços
 */

require_once 'conexao.php';

// Inicia a sessão se ainda não foi iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Cria um novo agendamento completo no sistema
 * 
 * Este método executa uma transação completa que:
 * 1. Cria um orçamento base
 * 2. Adiciona cada serviço solicitado
 * 3. Cria os agendamentos correspondentes
 * 4. Calcula e atualiza o valor total
 * 
 * @param PDO $pdo - Conexão com o banco de dados
 * @param int $cliente_id - ID do cliente que está agendando
 * @param string $data_hora - Data e hora do agendamento (formato: YYYY-MM-DD HH:MM:SS)
 * @param array $tipos_servico - Array com IDs dos tipos de serviço solicitados
 * @return boolean - True se criado com sucesso, False caso contrário
 */
function criarAgendamento($pdo, $cliente_id, $data_hora, $tipos_servico) {
    try {
        // Inicia transação para garantir integridade dos dados
        $pdo->beginTransaction();
        
        // Etapa 1: Criar orçamento base
        $stmt = $pdo->prepare("INSERT INTO Orcamento (ValorTotal, DataHora_Inicio, idCliente, Aprovacao) VALUES (0, :data_hora, :cliente_id, 1)");
        $stmt->bindParam(':data_hora', $data_hora);
        $stmt->bindParam(':cliente_id', $cliente_id);
        $stmt->execute();
        $orcamento_id = $pdo->lastInsertId();
        
        $valor_total = 0;
        
        // Etapa 2: Processar cada tipo de serviço solicitado
        foreach ($tipos_servico as $tipo_id) {
            // Buscar informações do tipo de serviço
            $stmt = $pdo->prepare("SELECT ValorBase FROM TipoServico WHERE IDtiposervico = :tipo_id");
            $stmt->bindParam(':tipo_id', $tipo_id);
            $stmt->execute();
            $tipo_servico = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($tipo_servico) {
                // Acumular valor total do orçamento
                $valor_total += $tipo_servico['ValorBase'];
                
                // Criar registro do serviço
                $stmt = $pdo->prepare("INSERT INTO Servico (Descricao, idOrcamento, IDtiposervico) VALUES ('Agendamento automático', :orcamento_id, :tipo_id)");
                $stmt->bindParam(':orcamento_id', $orcamento_id);
                $stmt->bindParam(':tipo_id', $tipo_id);
                $stmt->execute();
                $servico_id = $pdo->lastInsertId();
                
                // Criar agendamento específico para este serviço
                $stmt = $pdo->prepare("INSERT INTO Agendamento (DataHora, Status, IDusuario, idServico) VALUES (:data_hora, 'Agendado', 1, :servico_id)");
                $stmt->bindParam(':data_hora', $data_hora);
                $stmt->bindParam(':servico_id', $servico_id);
                $stmt->execute();
            }
        }
        
        // Etapa 3: Atualizar valor total calculado no orçamento
        $stmt = $pdo->prepare("UPDATE Orcamento SET ValorTotal = :valor_total WHERE IDorcamento = :orcamento_id");
        $stmt->bindParam(':valor_total', $valor_total);
        $stmt->bindParam(':orcamento_id', $orcamento_id);
        $stmt->execute();
        
        // Confirma todas as operações
        $pdo->commit();
        return true;
        
    } catch (PDOException $e) {
        // Desfaz todas as operações em caso de erro
        $pdo->rollBack();
        error_log("Erro ao criar agendamento: " . $e->getMessage());
        return false;
    }
}

/**
 * Lista todos os agendamentos de uma data específica
 * 
 * Retorna informações completas dos agendamentos incluindo:
 * - Dados do agendamento
 * - Nome do cliente
 * - Descrição do serviço
 * - Tipo de serviço
 * 
 * @param PDO $pdo - Conexão com o banco de dados
 * @param string $data - Data para buscar agendamentos (formato: YYYY-MM-DD)
 * @return array - Array com todos os agendamentos da data ou array vazio se não houver
 */
function listarAgendamentosPorData($pdo, $data) {
    $query = "SELECT a.*, s.Descricao as servico_descricao, c.Nome as cliente_nome, ts.Nome as tipo_servico_nome
              FROM Agendamento a 
              JOIN Servico s ON a.idServico = s.IDservico 
              JOIN TipoServico ts ON s.IDtiposervico = ts.IDtiposervico
              JOIN Orcamento o ON s.idOrcamento = o.IDorcamento 
              JOIN Cliente c ON o.idCliente = c.ID_Cliente 
              WHERE DATE(a.DataHora) = :data 
              ORDER BY a.DataHora";
    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':data', $data);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erro ao buscar agendamentos por data: " . $e->getMessage());
        return [];
    }
}

// ===============================================
// ROTEAMENTO E PROCESSAMENTO DE REQUISIÇÕES
// ===============================================

$acao = $_REQUEST['acao'] ?? '';

/**
 * Processamento de criação de agendamento via POST
 * Recebe dados do formulário e cria um novo agendamento
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $acao === 'criar_agendamento') {
    $cliente_id = $_POST['cliente_id'] ?? null;
    $data = $_POST['data'] ?? null;
    $tipos_servico = $_POST['tipos_servico'] ?? [];
    
    // Validação básica dos dados recebidos
    if ($cliente_id && $data && !empty($tipos_servico)) {
        // Converte data para formato datetime (horário padrão 09:00)
        $data_hora = $data . ' 09:00:00';
        
        if (criarAgendamento($pdo, $cliente_id, $data_hora, $tipos_servico)) {
            $_SESSION['mensagem_sucesso'] = "Agendamento criado com sucesso!";
        } else {
            $_SESSION['mensagem_erro'] = "Erro ao criar agendamento.";
        }
    } else {
        $_SESSION['mensagem_erro'] = "Dados incompletos para criar agendamento.";
    }
    
    // Redireciona de volta para a página de serviços
    header('Location: ../HTML/servico.php');
    exit();
}

/**
 * API para buscar agendamentos por data
 * Retorna dados em formato JSON para requisições AJAX
 */
if ($acao === 'buscar_agendamentos_data') {
    header('Content-Type: application/json');
    $data = $_GET['data'] ?? '';
    
    if ($data) {
        $agendamentos = listarAgendamentosPorData($pdo, $data);
        echo json_encode(['sucesso' => true, 'agendamentos' => $agendamentos]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Data não fornecida']);
    }
    exit();
}
?>
