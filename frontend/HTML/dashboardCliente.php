<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <link rel="icon" type="image/x-icon" href="../IMG/logo.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliente Dashboard - Controle de Pragas</title>
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
    <div class="content">
        <h1>Dashboard do Cliente</h1>
        <div class="tabs">
            <button onclick="openTab('informacoes')">📄 Informações Gerais</button>
            <button onclick="openTab('endereco')">🏠 Endereço</button>
            <button onclick="openTab('contato')">📞 Contato</button>
            <button onclick="openTab('faturamento')">💰 Faturamento</button>
        </div>

        <!-- Seção Informações Gerais -->
        <div id="informacoes" class="tab-content active">
            <h3>Informações Gerais</h3>
            <label>ID:</label>
            <input id="clienteId" disabled readonly> <!-- ID agora é readonly -->
            <label>Nome:</label>
            <input id="clienteNome" disabled>
            <label>CNPJ/CPF:</label>
            <input id="clienteCnpjCpf" disabled>
            <label>Tipo de Contrato:</label>
            <select id="clienteTipoContrato" disabled>
                <option value="Fixo">Fixo</option>
                <option value="Avulso">Avulso</option>
            </select>
            <label>Data de Início:</label>
            <input type="date" id="clienteDataInicio" disabled>
        </div>

        <!-- Seção Endereço -->
        <div id="endereco" class="tab-content">
            <h3>Endereço</h3>
            <label>Rua:</label>
            <input id="clienteRua" disabled>
            <label>Bairro:</label>
            <input id="clienteBairro" disabled>
            <label>Cidade:</label>
            <input id="clienteCidade" disabled>
            <label>Estado:</label>
            <input id="clienteEstado" disabled>
            <label>CEP:</label>
            <input id="clienteCep" disabled>
        </div>

        <!-- Seção Contato -->
        <div id="contato" class="tab-content">
            <h3>Contato</h3>
            <label>Nome Administradora:</label>
            <input id="administradoraNome" disabled>
            <label>Contato Administradora:</label>
            <input id="administradoraContato" disabled>
            <label>Nome Supervisor:</label>
            <input id="supervisorNome" disabled>
            <label>Contato Supervisor:</label>
            <input id="supervisorContato" disabled>
        </div>

        <!-- Seção Faturamento -->
        <div id="faturamento" class="tab-content">
            <h3>Faturamento</h3>
            <label>Valor Faturamento:</label>
            <input id="clienteFaturamento" disabled>
            <label>Forma de Pagamento:</label>
            <select id="clienteFormaPagamento" disabled>
                <option value="Boleto">Boleto</option>
                <option value="Cartão">Cartão</option>
                <option value="Transferência">Transferência</option>
            </select>
            <label>Tipo de Contrato:</label>
            <select id="clienteTipoContratoFaturamento" disabled>
                <option value="Fixo">Fixo</option>
                <option value="Avulso">Avulso</option>
            </select>
        </div>

        <!-- Botões de Ação -->
        <button class="btn" onclick="habilitarEdicao()">✏️ Editar</button>
        <button id="btnSalvar" class="btn" style="display:none;" onclick="salvarEdicao()">💾 Salvar</button>
        <button id="btnCancelar" class="btn" style="display:none;" onclick="cancelarEdicao()">❌ Cancelar</button>
    </div>
    <script src="../JS/dashboardCliente.js"></script>
    <script src="../JS/base.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>