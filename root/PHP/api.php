<?php
header('Content-Type: application/json');
require_once 'conexao.php';

$acao = $_GET['acao'] ?? '';

if ($acao === 'buscar_cliente') {
    $termo = $_GET['termo'] ?? '';

    if (strlen($termo) < 1) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Digite pelo menos 1 caractere']);
        exit;
    }

    try {
        $sql = "SELECT ID_Cliente, Nome, TipoCliente, CPFCNPJ, Telefone, Email, Rua, Bairro, Cidade, Estado, CEP 
                FROM Cliente 
                WHERE Nome LIKE ? OR ID_Cliente = ? OR CPFCNPJ LIKE ?
                ORDER BY Nome 
                LIMIT 10";
        
        $stmt = $pdo->prepare($sql);
        $likeTermo = "%{$termo}%";
        $idTermo = is_numeric($termo) ? (int)$termo : 0;
        
        $stmt->execute([$likeTermo, $idTermo, $likeTermo]);
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($clientes) > 0) {
            echo json_encode([
                'sucesso' => true, 
                'clientes' => $clientes,
                'total_encontrados' => count($clientes),
                'termo_buscado' => $termo
            ]);
        } else {
            echo json_encode([
                'sucesso' => false, 
                'mensagem' => "Nenhum cliente encontrado com '{$termo}'."
            ]);
        }
        
    } catch (Exception $e) {
        echo json_encode([
            'sucesso' => false, 
            'mensagem' => 'Erro: ' . $e->getMessage()
        ]);
    }
    exit;
}

echo json_encode(['sucesso' => false, 'mensagem' => 'Ação inválida']);
?>
