<?php
/**
 * conexao.php
 * 
 * Arquivo central para configuração e estabelecimento da conexão com o banco de dados.
 * Utiliza as melhores práticas com PDO para segurança e eficiência.
 */

// --- 1. CONFIGURAÇÕES DO BANCO DE DADOS (use constantes para segurança) ---
define('DB_HOST', '127.0.0.1'); // Usar 127.0.0.1 em vez de 'localhost' para evitar problemas de resolução de DNS
define('DB_PORT', '3306');      // Porta padrão do MySQL
define('DB_NAME', 'greensea');   // Nome do banco de dados
define('DB_USER', 'root');      // Usuário do banco de dados
define('DB_PASS', 'usbw');          // Senha do banco de dados (vazia para o padrão do USBWebServer)
define('DB_CHARSET', 'utf8mb4'); // Charset para suportar emojis e caracteres especiais

// --- 2. OPÇÕES DO PDO PARA UMA CONEXÃO OTIMIZADA ---
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,      // Lança exceções em caso de erro
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,            // Retorna os resultados como arrays associativos
    PDO::ATTR_EMULATE_PREPARES   => false,                       // Usa prepared statements nativos do DB, mais seguro
];

// --- 3. ESTABELECIMENTO DA CONEXÃO ---
try {
    // Criação do DSN (Data Source Name)
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    
    // Instancia o objeto PDO
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

} catch (PDOException $e) {
    // Em caso de falha, exibe uma mensagem de erro e encerra o script.
    // Em um ambiente de produção, o ideal seria logar o erro em um arquivo.
    error_log("Erro de Conexão com o Banco de Dados: " . $e->getMessage());
    die("❌ Ocorreu um erro ao conectar com o banco de dados. Por favor, tente novamente mais tarde.");
}
