<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <link rel="icon" href="../IMG/logo.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GreenSea Controle de Pragas</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <div class="login-container">
        <img src="../IMG/LOGO GREENSEA.png" alt="Logo da Empresa" class="logo">
        <h2>Acesso ao Sistema</h2>

        <div class="input-container">
            <input type="text" id="usuario" placeholder="Usuário">
        </div>

        <div class="input-container">
            <input type="password" id="senha" placeholder="Senha">
            <span class="toggle-password" onclick="toggleSenha()">👁️</span>
        </div>

        <div class="remember-container">
            <label>
                <input type="checkbox" id="lembrarMe"> Lembrar-me
            </label>
        </div>

        <button class="btn-login" onclick="fazerLogin()">Entrar</button>
        <p class="mensagem" id="mensagem"></p>

        <!-- SEÇÃO ADICIONADA -->
        <div class="links-adicionais">
            <a href="/HTML/Sobrenos.php">Sobre nós</a>
            <a href="/HTML/primeirocadastro.php">Criar conta</a>
        </div>
        <!-- FIM DA SEÇÃO ADICIONADA -->

    </div>
    <script src="../js/login.js"></script>
</body>

</html>