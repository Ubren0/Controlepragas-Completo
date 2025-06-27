<?php
// conexao.php

// --- DADOS DO SEU BANCO DE DADOS ---
$host = 'localhost';
$db_nome = 'greensea';
$usuario = 'root';
$senha_db = 'serra'; // Nome diferente para não confundir com senha de usuário

/**
 * Tenta se conectar ao banco de dados.
 * Se conseguir, a variável $pdo estará pronta para ser usada.
 * Se falhar, o script para e exibe uma mensagem de erro.
 */
try {
    // Cria a conexão usando PDO (a forma moderna e correta)
    $pdo = new PDO("mysql:host=$host;dbname=$db_nome;charset=utf8mb4", $usuario, $senha_db);
    
    // Configura o PDO para reportar erros de forma clara
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Em caso de falha na conexão, encerra a execução e mostra o erro.
    die("❌ ERRO DE CONEXÃO COM O BANCO DE DADOS: " . $e->getMessage());
}