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
    <!-- Script de autenticação deve executar primeiro -->
    <script src="../js/auth.js"></script>
    
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
                <li class="config-toggle active" onclick="toggleSubmenu()" aria-expanded="true" aria-controls="submenu">
                    <i class="bi bi-gear-fill"></i> Configurações <span id="seta">▲</span>
                </li>
                <ul class="submenu" id="submenu" style="display: block;" role="menu" aria-label="Submenu de configurações">
                    <li class="active-submenu" onclick="window.location.href='usuarios.php'">
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

    <div class="content">
        <h1>Gerenciar Usuários</h1>

        <div id="mensagens"></div>

        <div class="card">
            <h3>📊 Estatísticas dos Usuários</h3>
            <p><strong>Total de Usuários:</strong> <span id="usuariosCount">0</span></p>
        </div>

        <!-- Adicionar Usuário -->
        <button onclick="window.location.href='cadastroUsuario.php'" class="btn-cadastro">Cadastrar Novo Usuário
        </button>

        <table class="tabela-usuarios">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Login</th>
                    <th>Cargo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="tabelaUsuarios">
                <tr>
                    <td colspan="4" style="text-align: center;">Carregando usuários...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <script src="../js/base.js"></script>
    <script src="../js/usuarios.js"></script>

</body>

</html>