

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <link rel="icon" href="../IMG/logo.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - GreenSea</title>
    <!-- Reaproveitando o mesmo CSS da página de login -->
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <div class="login-container">
        <img src="../IMG/LOGO GREENSEA.png" alt="Logo da Empresa" class="logo">
        <h2>Criação de Conta</h2>

        <div class="input-container">
            <input type="text" id="nomeCompleto" placeholder="Nome Completo">
        </div>

        <div class="input-container">
            <input type="text" id="usuario" placeholder="Nome de Usuário">
        </div>

        <div class="input-container">
            <input type="password" id="senha" placeholder="Senha">
        </div>

        <div class="input-container">
            <input type="password" id="confirmarSenha" placeholder="Confirmar Senha">
        </div>

        <button class="btn-login">Criar Conta</button>
        <p class="mensagem" id="mensagem"></p>

        <div class="links-adicionais">
            <!-- Link para voltar à página de login -->
            <a href="/index.php">Já tem uma conta? Faça login</a>
        </div>

    </div>
</body>

</html>