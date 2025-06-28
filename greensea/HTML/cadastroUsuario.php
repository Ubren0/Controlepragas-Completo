<?php
session_start();
require_once 'db_config.php'; // Inclui a conexão e criação do banco

$error_message = '';
$success_message = '';

// --- LÓGICA PARA CADASTRAR UM NOVO USUÁRIO ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Coletar dados do formulário
    $nome = trim($_POST['nome']);
    $login = trim($_POST['login']);
    $senha = $_POST['senha'];
    $confirmarSenha = $_POST['confirmarSenha'];
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $idCargo = (int)$_POST['cargo'];

    // 2. Validações
    if ($senha !== $confirmarSenha) {
        $error_message = "As senhas não coincidem!";
    } else {
        // 3. Verificar se o login já existe
        $sql_check = "SELECT IDusuario FROM Usuario WHERE Login = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $login);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error_message = "O login '$login' já está em uso. Por favor, escolha outro.";
        } else {
            // 4. Criptografar a senha (NUNCA armazene senhas em texto puro)
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            // 5. Preparar e executar a inserção no banco
            $sql_insert = "INSERT INTO Usuario (Nome, Login, Senha, Email, Telefone, idCargo) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt_insert = $conn->prepare($sql_insert)) {
                $stmt_insert->bind_param("sssssi", $nome, $login, $senhaHash, $email, $telefone, $idCargo);
                
                if ($stmt_insert->execute()) {
                    $_SESSION['success_message'] = "Usuário cadastrado com sucesso!";
                    header("Location: usuarios.php"); // Redireciona para limpar o POST
                    exit();
                } else {
                    $error_message = "Erro ao cadastrar o usuário: " . $stmt_insert->error;
                }
                $stmt_insert->close();
            } else {
                $error_message = "Erro ao preparar a query de inserção: " . $conn->error;
            }
        }
        $stmt_check->close();
    }
}

// Lógica para exibir mensagens da sessão
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}


// --- LÓGICA PARA BUSCAR CARGOS E USUÁRIOS PARA EXIBIÇÃO ---
// Buscar cargos para o dropdown
$cargos = [];
$sql_cargos = "SELECT IDcargo, Descricao FROM Cargo ORDER BY Descricao";
$result_cargos = $conn->query($sql_cargos);
if ($result_cargos->num_rows > 0) {
    while($row = $result_cargos->fetch_assoc()) {
        $cargos[] = $row;
    }
}

// Buscar usuários existentes para a tabela
$usuarios = [];
$sql_usuarios = "SELECT u.Nome, u.Login, u.Email, c.Descricao AS Cargo FROM Usuario u LEFT JOIN Cargo c ON u.idCargo = c.IDcargo ORDER BY u.Nome";
$result_usuarios = $conn->query($sql_usuarios);
if ($result_usuarios->num_rows > 0) {
    while($row = $result_usuarios->fetch_assoc()) {
        $usuarios[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário - Controle de Pragas</title>
    <link rel="icon" href="../IMG/logo.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../CSS/base.css">
    <style>
        .error { color: #d9534f; font-size: 0.9em; margin-top: 5px; }
        .success { color: #5cb85c; background-color: #dff0d8; border: 1px solid #d6e9c6; padding: 15px; border-radius: 4px; margin-bottom: 20px;}
    </style>
</head>

<body>
    <!-- Barra lateral de navegação (seu código aqui) -->
    <div>
        <nav class="sidebar">
            <h2>Controle de Pragas</h2>
            <ul>
                <li onclick="window.location.href='dashboard.php'">
                    <i class="bi bi-house-door-fill"></i> Dashboard
                </li>
                <li onclick="window.location.href='clientes.php'">
                    <i class="bi bi-buildings-fill"></i> Clientes
                </li>
                <li onclick="window.location.href='servico.php'">
                    <i class="bi bi-bug-fill"></i> Serviços
                </li>
                <li onclick="window.location.href='agenda.php'">
                    <i class="bi bi-calendar-event-fill"></i> Agenda
                </li>
                <li class="config-toggle" onclick="toggleSubmenu()" aria-expanded="false" aria-controls="submenu">
                    <i class="bi bi-gear-fill"></i> Configurações <span id="seta">▼</span>
                </li>
                <ul class="submenu" id="submenu" role="menu" aria-label="Submenu de configurações">
                    <li onclick="window.location.href='usuarios.php'">
                        <i class="bi bi-person-fill"></i> Usuários
                    </li>
                    <li onclick="window.location.href='tipoServico.php'">
                        <i class="bi bi-box-fill"></i> Tipo de Serviço
                    </li>
                </ul>
                <li onclick="logout()">
                    <i class="bi bi-box-arrow-right"></i> Sair
                </li>
            </ul>
        </nav>
    </div>

    <!-- Conteúdo principal -->
    <div class="content">
        <h1>Gerenciamento de Usuários</h1>
        
        <?php if ($success_message): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="card">
            <h3>📝 Cadastrar Novo Usuário</h3>

            <form id="formUsuario" method="POST" action="usuarios.php">
                <div class="input-group">
                    <label for="nome">Nome Completo *</label>
                    <input type="text" id="nome" name="nome" required>
                </div>

                <div class="input-group">
                    <label for="login">Login *</label>
                    <input type="text" id="login" name="login" required>
                </div>

                <div class="input-group">
                    <label for="senha">Senha *</label>
                    <input type="password" id="senha" name="senha" required minlength="6">
                </div>

                <div class="input-group">
                    <label for="confirmarSenha">Confirmar Senha *</label>
                    <input type="password" id="confirmarSenha" name="confirmarSenha" required>
                </div>

                <div class="input-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email">
                </div>

                <div class="input-group">
                    <label for="telefone">Telefone</label>
                    <input type="tel" id="telefone" name="telefone" placeholder="(99) 99999-9999">
                </div>

                <div class="input-group">
                    <label for="cargo">Cargo *</label>
                    <select id="cargo" name="cargo" required>
                        <option value="">Selecione um cargo...</option>
                        <?php foreach ($cargos as $cargo): ?>
                            <option value="<?php echo $cargo['IDcargo']; ?>">
                                <?php echo htmlspecialchars($cargo['Descricao']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn-salvar">💾 Salvar</button>
                    <button type="button" class="btn-cancelar" onclick="window.location.href='usuarios.php'">❌
                        Cancelar</button>
                </div>
            </form>
        </div>

        <!-- Tabela de Usuários Cadastrados -->
        <div class="card">
            <h3>👥 Usuários Cadastrados</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Login</th>
                        <th>Email</th>
                        <th>Cargo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($usuarios)): ?>
                        <tr>
                            <td colspan="5">Nenhum usuário cadastrado.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['Nome']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['Login']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['Email']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['Cargo']); ?></td>
                            <td>
                                <button class="btn-icon" title="Editar"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn-icon" title="Excluir"><i class="bi bi-trash-fill"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="../JS/base.js"></script>
    <script>
        // Pequeno script para validar senhas no lado do cliente (melhora a experiência)
        const form = document.getElementById('formUsuario');
        const senha = document.getElementById('senha');
        const confirmarSenha = document.getElementById('confirmarSenha');

        form.addEventListener('submit', function(e) {
            if (senha.value !== confirmarSenha.value) {
                e.preventDefault(); // Impede o envio do formulário
                alert('Erro: As senhas não coincidem!');
                confirmarSenha.focus();
            }
        });
    </script>
</body>

</html>