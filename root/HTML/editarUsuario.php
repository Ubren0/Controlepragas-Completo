<?php
require_once '../PHP/logica_usuario.php';

// Pega o ID do usuário da URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Se o ID não for válido, redireciona para a lista
if (!$id) {
    header('Location: usuarios.php');
    exit();
}

// Busca os dados do usuário no banco
$usuario = buscaUsuarioPorId($pdo, $id);

// Se o usuário não for encontrado, redireciona
if (!$usuario) {
    $_SESSION['mensagem_erro'] = "Usuário não encontrado.";
    header('Location: usuarios.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário - GreenSea</title>
    <link rel="icon" href="../IMG/logo.ico">
    <link rel="stylesheet" href="../CSS/base.css">
    <link rel="stylesheet" href="../CSS/login.css"> <!-- Reutilizando estilos -->
</head>
<body>
    <div class="container-login">
        <div class="img-box"></div>
        <div class="content-box">
            <div class="form-box">
                <div class="logo-container">
                    <img src="../IMG/LOGO GREENSEA.png" alt="Logo GreenSea" class="logo-greensea">
                </div>
                <h2>Editar Usuário</h2>
                
                <form action="../PHP/logica_usuario.php" method="POST">
                    <input type="hidden" name="acao" value="editar_usuario">
                    <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($usuario['IDusuario']) ?>">
                    
                    <div class="input-box">
                        <span>Nome Completo</span>
                        <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome_completo']) ?>" required>
                    </div>

                    <div class="input-box">
                        <span>Login</span>
                        <input type="text" name="login" value="<?= htmlspecialchars($usuario['Login']) ?>" required>
                    </div>

                    <div class="input-box">
                        <span>Nova Senha (opcional)</span>
                        <input type="password" name="senha" placeholder="Deixe em branco para não alterar">
                    </div>

                    <div class="input-box">
                        <span>Cargo</span>
                        <select name="idCargo" required>
                            <option value="1" <?= ($usuario['idCargo'] == 1) ? 'selected' : '' ?>>Administrador</option>
                            <option value="2" <?= ($usuario['idCargo'] == 2) ? 'selected' : '' ?>>Usuário</option>
                        </select>
                    </div>

                    <div class="input-box">
                        <input type="submit" value="Salvar Alterações">
                    </div>

                    <div class="input-box-back">
                        <a href="usuarios.php" class="back-button">Cancelar e Voltar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
</html>
