<?php
// Arquivo temporário para debug da API
ob_start(); // Capturar qualquer saída não intencional

require_once 'conexao.php';

// Verificar se há output antes do JSON
$output_before = ob_get_contents();
ob_end_clean();

echo "<h1>🔍 Debug da API de Agenda</h1>";

if (!empty($output_before)) {
    echo "<div style='background: #ffcccc; padding: 10px; margin: 10px 0;'>";
    echo "<h3>⚠️ Output indesejado encontrado antes do JSON:</h3>";
    echo "<pre>" . htmlspecialchars($output_before) . "</pre>";
    echo "</div>";
}

echo "<h2>📄 Conteúdo da API:</h2>";
echo "<iframe src='api_agenda.php' style='width: 100%; height: 400px; border: 1px solid #ccc;'></iframe>";

echo "<h2>🔗 Teste direto:</h2>";
echo "<p><a href='api_agenda.php' target='_blank'>🚀 Abrir API em nova aba</a></p>";

echo "<h2>📋 Verificar estrutura:</h2>";
try {
    $stmt = $pdo->query("SHOW TABLES");
    $tabelas = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<p><strong>Tabelas encontradas:</strong> " . implode(', ', $tabelas) . "</p>";
    
    if (in_array('Servico', $tabelas)) {
        $stmt = $pdo->query("DESCRIBE Servico");
        $colunas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h3>Estrutura da tabela Servico:</h3>";
        echo "<ul>";
        foreach ($colunas as $coluna) {
            echo "<li><strong>{$coluna['Field']}</strong> - {$coluna['Type']}</li>";
        }
        echo "</ul>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Erro ao verificar estrutura: " . $e->getMessage() . "</p>";
}

echo "<p><a href='../HTML/agenda.php'>📅 Voltar para Agenda</a></p>";
?>
