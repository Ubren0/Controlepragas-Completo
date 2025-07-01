<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <link rel="icon" href="../IMG/logo.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Controle de Pragas</title>
    <link rel="stylesheet" href="../CSS/login.css">
</head>

<body>
    <form class="login-container" action="../PHP/logica_login.php" method="POST">
        <img src="../IMG/LOGO GREENSEA.png" alt="Logo da Empresa" class="logo">
        <h2>Login</h2>

        <?php if (isset($_SESSION['mensagem_erro'])) : ?>
            <div class="mensagem erro">
                <?php
                echo $_SESSION['mensagem_erro'];
                unset($_SESSION['mensagem_erro']); // Limpa a mensagem após exibir
                ?>
            </div>
        <?php endif; ?>

        <div class="input-container">
            <input type="text" id="usuario" name="usuario" placeholder="Usuário" required>
        </div>

        <div class="input-container">
            <input type="password" id="senha" name="senha" placeholder="Senha" required>
            <span class="toggle-password" onclick="toggleSenha()">👁️</span>
        </div>

        <div class="remember-container">
            <label>
                <input type="checkbox" id="lembrarMe"> Lembrar-me
            </label>
        </div>

        <button type="submit" class="btn-login">Entrar</button>
        
        <div class="links-adicionais">
            <a href="../HTML/primeirocadastro.php">Primeiro Cadastro</a>
            <a href="../HTML/Sobrenos.php">Sobre Nós</a>
        </div>
    </form>
    <script src="../JS/base.js"></script>
    <script src="../JS/login.js"></script>
</body>

</html>