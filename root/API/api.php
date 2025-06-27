<?php
// api/api.php

/**
 * Ponto de entrada central para todas as requisições da API.
 * As requisições do front-end (JavaScript) devem ser direcionadas para este arquivo.
 * Ex: /api/api.php?acao=login
 */

// Inicia a sessão e inclui a conexão com o banco
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once '../includes/conexao.php'; // Usa '..' para voltar um diretório

// Pega a 'acao' da requisição (seja GET ou POST)
$acao = $_REQUEST['acao'] ?? null;

// Um roteador simples usando switch para direcionar a ação
switch ($acao) {
    case 'login':
        // Lógica de Login
        $login = $_POST['usuario'] ?? '';
        $senha = $_POST['senha'] ?? '';

        $stmt = $pdo->prepare("SELECT IDusuario, Nome, Senha FROM Usuario WHERE Login = ?");
        $stmt->execute([$login]);
        $usuario = $stmt->fetch();

        // Verifica se o usuário existe e se a senha está correta
        if ($usuario && password_verify($senha, $usuario['Senha'])) {
            $_SESSION['usuario_id'] = $usuario['IDusuario'];
            $_SESSION['usuario_nome'] = $usuario['Nome'];
            responder_json(true, "Login realizado com sucesso!");
        } else {
            responder_json(false, "Usuário ou senha inválidos.");
        }
        break;

    case 'logout':
        session_destroy();
        responder_json(true, "Logout realizado com sucesso.");
        break;
        
    case 'salvar_usuario':
        // Aqui entrará a lógica completa do `salvar_usuario.php` que te mostrei antes
        // Ex: Pega os dados do $_POST, valida, faz o INSERT na tabela Usuario...
        responder_json(false, "Ação 'salvar_usuario' ainda não implementada.");
        break;

    case 'listar_clientes':
        // Aqui entrará a lógica para buscar os clientes no banco
        // Ex: SELECT * FROM Cliente...
        // $clientes = $pdo->query("SELECT * FROM Cliente")->fetchAll();
        // responder_json(true, "Clientes listados.", $clientes);
        responder_json(false, "Ação 'listar_clientes' ainda não implementada.");
        break;
    
    // Adicione outros 'case' para cada ação que seu sistema precisar:
    // case 'salvar_cliente': ...
    // case 'buscar_eventos_agenda': ...
    // case 'deletar_usuario': ...

    default:
        // Ação padrão se nenhuma ação válida for fornecida
        responder_json(false, "Ação desconhecida ou não fornecida.");
        break;
}


/**
 * Função auxiliar para padronizar as respostas JSON.
 * @param bool $sucesso - TRUE se a operação foi bem-sucedida, FALSE caso contrário.
 * @param string $mensagem - Uma mensagem descritiva.
 * @param array $dados - Um array com dados a serem retornados (opcional).
 */
function responder_json($sucesso, $mensagem, $dados = []) {
    header('Content-Type: application/json');
    if (!$sucesso) {
        http_response_code(400); // Bad Request (ou outro código de erro apropriado)
    }
    echo json_encode([
        'sucesso' => $sucesso,
        'mensagem' => $mensagem,
        'dados' => $dados
    ]);
    exit();
}