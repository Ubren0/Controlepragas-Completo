<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <!-- Ícone que aparece na aba do navegador -->
    <link rel="icon" type="IMG/x-icon" href="../IMG/logo.ico">

    <!-- Configurações de codificação e responsividade -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Título da página -->
    <title>Clientes - Controle de Pragas</title>

    <!-- Link para o arquivo de estilos CSS específico da tela de clientes -->
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

    <!-- Área principal de conteúdo da página -->
    <div class="content">
        <h1>Clientes Cadastrados</h1>

        <!-- Card com os dados e ações relacionadas aos clientes -->
        <div class="card">
            <h3>🏢 Lista de Clientes</h3>

            <!-- Campo de busca para filtrar clientes -->
            <input type="text" id="searchClientes" placeholder="🔍 Buscar cliente..." onkeyup="filtrarClientes()">

            <!-- Contêiner para os botões de ação do card -->
            <div class="card-actions">
                <button class="btn" onclick="window.location.href='cadastroCliente.php'">➕ Adicionar Cliente</button>
                <button class="btn" onclick="carregarClientes()">🔄 Atualizar</button>
            </div>

            <!-- Tabela onde os clientes serão listados -->
            <table border="1" width="100%" id="tabelaClientes">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>E-mail</th>
                        <th>Endereço</th>
                        <th>Ações</th> <!-- Aqui podem entrar botões de editar/excluir -->
                    </tr>
                </thead>
                <tbody>
                    <!-- As linhas com os dados dos clientes serão inseridas aqui via JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Link para o script JavaScript que gerencia o comportamento da tela -->
    <script src="../js/base.js"></script>
    <script src="../js/cliente.js"></script>
</body>

</html>