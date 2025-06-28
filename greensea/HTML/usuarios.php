<?php
// Conexão com o banco
$conn = new mysqli("localhost", "usuario", "senha", "greensea");
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Consulta para listar todos os usuários
$sql = "SELECT U.IDusuario, U.Login, C.Descricao AS Cargo
        FROM Usuario U
        LEFT JOIN Cargo C ON U.idCargo = C.IDcargo";
$result = $conn->query($sql);

// Total de usuários
$totalUsuarios = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <link rel="icon" href="../IMG/logo.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários - Controle de Pragas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/base.css">
</head>

<body>
    <div>
        <nav class="sidebar">
            <h2>Controle de Pragas</h2>
            <ul>
                <li onclick="window.location.href='dashboard.php'"><i class="bi bi-house-door-fill"></i> Dashboard</li>
                <li onclick="window.location.href='clientes.php'"><i class="bi bi-buildings-fill"></i> Clientes</li>
                <li onclick="window.location.href='servico.php'"><i class="bi bi-bug-fill"></i> Serviços</li>
                <li onclick="window.location.href='agenda.php'"><i class="bi bi-calendar-event-fill"></i> Agenda</li>
                <li class="config-toggle" onclick="toggleSubmenu()"><i class="bi bi-gear-fill"></i> Configurações <span id="seta">▼</span></li>
                <ul class="submenu" id="submenu">
                    <li onclick="window.location.href='usuarios.php'"><i class="bi bi-person-fill"></i> Usuários</li>
                    <li onclick="window.location.href='tipoServico.php'"><i class="bi bi-box-fill"></i> Tipo de Serviço</li>
                </ul>
                <li onclick="logout()"><i class="bi bi-box-arrow-right"></i> Sair</li>
            </ul>
        </nav>
    </div>

    <div class="content">
        <h1>Gerenciar Usuários</h1>
        <div class="card p-3 mb-4">
            <h3>📊 Estatísticas dos Usuários</h3>
            <p><strong>Total de Usuários:</strong> <span id="usuariosCount"><?= $totalUsuarios ?></span></p>
        </div>

        <!-- Botão para cadastrar novo usuário -->
        <a href="cadastroUsuario.php" class="btn btn-primary mb-3">Cadastrar Novo Usuário</a>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Login</th>
                    <th>Cargo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['IDusuario'] ?></td>
                    <td><?= htmlspecialchars($row['Login']) ?></td>
                    <td><?= $row['Cargo'] ?? 'Não definido' ?></td>
                    <td>
                        <a href="editarUsuario.php?id=<?= $row['IDusuario'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Editar</a>
                        <a href="excluirUsuario.php?id=<?= $row['IDusuario'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir?')"><i class="bi bi-trash-fill"></i> Excluir</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="../js/base.js"></script>
</body>

</html>

