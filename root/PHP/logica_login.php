<?php
session_start();

// Inclui a conexão com o banco de dados
require_once 'conexao.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    // Validação básica
    if (empty($usuario) || empty($senha)) {
        $_SESSION['mensagem_erro'] = 'Por favor, preencha todos os campos.';
        header('Location: ../index.php');
        exit;
    }
    
    try {
        // Conecta ao banco de dados
        $pdo = conectarBanco();
        
        // Prepara a consulta para buscar o usuário
        $stmt = $pdo->prepare("SELECT IDusuario, nome_completo, Login, Senha, idCargo FROM usuario WHERE Login = ? LIMIT 1");
        $stmt->execute([$usuario]);
        $usuarioEncontrado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // DEBUG: Verificar se encontrou o usuário
        if (!$usuarioEncontrado) {
            $_SESSION['mensagem_erro'] = 'Usuário não encontrado no banco de dados.';
            header('Location: ../index.php');
            exit;
        }
        
        // Verifica se o usuário existe e se a senha está correta
        if ($usuarioEncontrado && password_verify($senha, $usuarioEncontrado['Senha'])) {
            // Login bem-sucedido - define as variáveis de sessão
            $_SESSION['usuario_logado'] = true;
            $_SESSION['usuario_id'] = $usuarioEncontrado['IDusuario'];
            $_SESSION['usuario_nome'] = $usuarioEncontrado['nome_completo'];
            $_SESSION['usuario_login'] = $usuarioEncontrado['Login'];
            $_SESSION['usuario_tipo'] = $usuarioEncontrado['idCargo'];
            
            // Remove mensagens de erro anteriores
            unset($_SESSION['mensagem_erro']);
            
            // Redireciona para o dashboard
            header('Location: ../HTML/dashboard.php');
            exit;
            
        } else {
            // Login falhou
            $_SESSION['mensagem_erro'] = 'Usuário ou senha incorretos.';
            header('Location: ../index.php');
            exit;
        }
        
    } catch (PDOException $e) {
        // Erro de conexão ou consulta
        $_SESSION['mensagem_erro'] = 'Erro interno. Tente novamente mais tarde.';
        header('Location: ../index.php');
        exit;
    }
    
} else {
    // Se não foi uma requisição POST, redireciona para o login
    header('Location: ../index.php');
    exit;
}
?>
