<?php
header('Content-Type: application/json');
require_once 'conexao.php';

try {
    // Baseado na estrutura do greensea.sql, vamos usar a tabela Agendamento que é a correta
    $stmt = $pdo->query("SHOW TABLES LIKE 'Agendamento'");
    
    if ($stmt->rowCount() > 0) {
        // Usar a tabela Agendamento conforme definida no SQL
        $sql = "SELECT 
                    a.IDagendamento as id,
                    COALESCE(c.Nome, 'Cliente não informado') as cliente,
                    COALESCE(ts.Nome, s.Descricao, 'Serviço não informado') as servico,
                    DATE(a.DataHora) as data,
                    TIME(a.DataHora) as hora,
                    a.Status as status,
                    CONCAT(
                        COALESCE(c.Rua, ''), 
                        IF(c.Rua IS NOT NULL AND c.Rua != '', ', ', ''),
                        COALESCE(c.Bairro, ''), 
                        IF(c.Bairro IS NOT NULL AND c.Bairro != '', ', ', ''),
                        COALESCE(c.Cidade, ''), 
                        IF(c.Cidade IS NOT NULL AND c.Cidade != '', ' - ', ''),
                        COALESCE(c.Estado, '')
                    ) as endereco
                FROM Agendamento a
                LEFT JOIN Servico s ON a.idServico = s.IDservico
                LEFT JOIN Orcamento o ON s.idOrcamento = o.IDorcamento
                LEFT JOIN Cliente c ON o.idCliente = c.ID_Cliente
                LEFT JOIN TipoServico ts ON s.IDtiposervico = ts.IDtiposervico
                ORDER BY a.DataHora DESC
                LIMIT 50";
                
    } else {
        // Fallback: criar agendamentos fictícios usando dados existentes
        $stmt = $pdo->query("SHOW TABLES LIKE 'Cliente'");
        
        if ($stmt->rowCount() > 0) {
            $sql = "SELECT 
                        c.ID_Cliente as id,
                        c.Nome as cliente,
                        'Serviço a agendar' as servico,
                        CURDATE() as data,
                        '00:00:00' as hora,
                        'Aguardando agendamento' as status,
                        CONCAT(
                            COALESCE(c.Rua, ''), ', ',
                            COALESCE(c.Bairro, ''), ', ',
                            COALESCE(c.Cidade, ''), ' - ',
                            COALESCE(c.Estado, '')
                        ) as endereco
                    FROM Cliente c
                    ORDER BY c.ID_Cliente DESC
                    LIMIT 10";
        } else {
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Nenhuma tabela adequada encontrada. Execute o configurador do sistema.',
                'agendamentos' => []
            ]);
            exit;
        }
    }
    
    // Executar consulta
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'sucesso' => true,
        'total' => count($agendamentos),
        'agendamentos' => $agendamentos
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro: ' . $e->getMessage(),
        'agendamentos' => []
    ]);
}
?>
