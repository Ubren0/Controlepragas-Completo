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
</head>

<body>
    <!-- Barra lateral de navegação -->
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
        <h1>Cadastro de Usuário</h1>

        <div class="card">
            <h3>📝 Dados do Usuário</h3>

            <form id="formUsuario">
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
                    <input type="password" id="senha" name="senha" required>
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
                    <input type="text" id="telefone" name="telefone">
                </div>

                <div class="input-group">
                    <label for="cargo">Cargo</label>
                    <select id="cargo" name="cargo">
                        <option value="admin">Administrador</option>
                        <option value="tecnico">Técnico</option>
                        <option value="atendente">Atendente</option>
                    </select>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn-salvar">💾 Salvar</button>
                    <button type="button" class="btn-cancelar" onclick="window.location.href='usuarios.php'">❌
                        Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Popup de confirmação -->
    <div id="popup" class="popup">
        <div class="popup-content">
            <p>✅ Usuário cadastrado com sucesso!</p>
            <button onclick="fecharPopup()">OK</button>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../JS/base.js"></script>
</body>

</html>