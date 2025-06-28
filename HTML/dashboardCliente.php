<?php
require_once '../PHP/conexao.php'; // conexão PDO

if (!isset($_GET['id'])) {
    die("ID do cliente não especificado.");
}

$idCliente = (int) $_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM Cliente WHERE ID_Cliente = ?");
    $stmt->execute([$idCliente]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cliente) {
        die("Cliente não encontrado.");
    }
} catch (PDOException $e) {
    die("Erro ao buscar cliente: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <link rel="icon" type="image/x-icon" href="../IMG/logo.ico">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cliente Dashboard - Controle de Pragas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="../CSS/base.css" />
</head>

<body>
    <!-- Barra lateral de navegação -->
    <div>
        <nav class="sidebar">
            <h2>Controle de Pragas</h2>
            <ul>
                <li onclick="window.location.href='dashboard.php'"><i class="bi bi-house-door-fill"></i> Dashboard</li>
                <li onclick="window.location.href='clientes.php'"><i class="bi bi-buildings-fill"></i> Clientes</li>
                <li onclick="window.location.href='servico.php'"><i class="bi bi-bug-fill"></i> Serviços</li>
                <li onclick="window.location.href='agenda.php'"><i class="bi bi-calendar-event-fill"></i> Agenda</li>
                <li class="config-toggle" onclick="toggleSubmenu()" aria-expanded="false" aria-controls="submenu">
                    <i class="bi bi-gear-fill"></i> Configurações <span id="seta">▼</span>
                </li>
                <ul class="submenu" id="submenu" role="menu" aria-label="Submenu de configurações">
                    <li onclick="window.location.href='usuarios.php'"><i class="bi bi-person-fill"></i> Usuários</li>
                    <li onclick="window.location.href='tipoServico.php'"><i class="bi bi-box-fill"></i> Tipo de Serviço</li>
                </ul>
                <li onclick="logout()"><i class="bi bi-box-arrow-right"></i> Sair</li>
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

        <form id="formCliente" method="POST" action="salvar_cliente.php">
            <!-- Informações Gerais -->
            <div id="informacoes" class="tab-content active">
                <h3>Informações Gerais</h3>
                <label>ID:</label>
                <input id="clienteId" name="clienteId" readonly disabled>
                <label>Nome:</label>
                <input id="clienteNome" name="clienteNome" disabled required>
                <label>CNPJ/CPF:</label>
                <input id="clienteCnpjCpf" name="clienteCnpjCpf" disabled required>
                <label>Tipo de Contrato:</label>
                <select id="clienteTipoContrato" name="clienteTipoContrato" disabled required>
                    <option value="Fixo">Fixo</option>
                    <option value="Avulso">Avulso</option>
                </select>
                <label>Data de Início:</label>
                <input type="date" id="clienteDataInicio" name="clienteDataInicio" disabled required>
            </div>

            <!-- Endereço -->
            <div id="endereco" class="tab-content">
                <h3>Endereço</h3>
                <label>Rua:</label>
                <input id="clienteRua" name="clienteRua" disabled>
                <label>Bairro:</label>
                <input id="clienteBairro" name="clienteBairro" disabled>
                <label>Cidade:</label>
                <input id="clienteCidade" name="clienteCidade" disabled>
                <label>Estado:</label>
                <input id="clienteEstado" name="clienteEstado" disabled>
                <label>CEP:</label>
                <input id="clienteCep" name="clienteCep" disabled>
            </div>

            <!-- Contato -->
            <div id="contato" class="tab-content">
                <h3>Contato</h3>
                <label>Nome Administradora:</label>
                <input id="administradoraNome" name="administradoraNome" disabled>
                <label>Contato Administradora:</label>
                <input id="administradoraContato" name="administradoraContato" disabled>
                <label>Nome Supervisor:</label>
                <input id="supervisorNome" name="supervisorNome" disabled>
                <label>Contato Supervisor:</label>
                <input id="supervisorContato" name="supervisorContato" disabled>
            </div>

            <!-- Faturamento -->
            <div id="faturamento" class="tab-content">
                <h3>Faturamento</h3>
                <label>Valor Faturamento:</label>
                <input id="clienteFaturamento" name="clienteFaturamento" disabled>
                <label>Forma de Pagamento:</label>
                <select id="clienteFormaPagamento" name="clienteFormaPagamento" disabled>
                    <option value="Boleto">Boleto</option>
                    <option value="Cartão">Cartão</option>
                    <option value="Transferência">Transferência</option>
                </select>
                <label>Tipo de Contrato:</label>
                <select id="clienteTipoContratoFaturamento" name="clienteTipoContratoFaturamento" disabled>
                    <option value="Fixo">Fixo</option>
                    <option value="Avulso">Avulso</option>
                </select>
            </div>

            <!-- Botões de ação -->
            <button type="button" class="btn" onclick="habilitarEdicao()">✏️ Editar</button>
            <button type="submit" id="btnSalvar" class="btn" style="display:none;">💾 Salvar</button>
            <button type="button" id="btnCancelar" class="btn" style="display:none;" onclick="cancelarEdicao()">❌ Cancelar</button>
        </form>
    </div>

    <script>
        // Injetar dados do PHP no JS para preencher o formulário
        document.addEventListener("DOMContentLoaded", function () {
            const cliente = <?= json_encode($cliente) ?>;

            document.getElementById("clienteId").value = cliente.ID_Cliente || "";
            document.getElementById("clienteNome").value = cliente.Nome || "";
            document.getElementById("clienteCnpjCpf").value = cliente.CPFCNPJ || "";
            document.getElementById("clienteTipoContrato").value = cliente.TipoContrato || "";
            document.getElementById("clienteDataInicio").value = cliente.DataInicio || "";
            document.getElementById("clienteRua").value = cliente.Rua || "";
            document.getElementById("clienteBairro").value = cliente.Bairro || "";
            document.getElementById("clienteCidade").value = cliente.Cidade || "";
            document.getElementById("clienteEstado").value = cliente.Estado || "";
            document.getElementById("clienteCep").value = cliente.CEP || "";
            document.getElementById("administradoraNome").value = cliente.AdministradoraNome || "";
            document.getElementById("administradoraContato").value = cliente.AdministradoraContato || "";
            document.getElementById("supervisorNome").value = cliente.SupervisorNome || "";
            document.getElementById("supervisorContato").value = cliente.SupervisorContato || "";
            document.getElementById("clienteFaturamento").value = cliente.ValorFaturamento || "";
            document.getElementById("clienteFormaPagamento").value = cliente.FormaPagamento || "";
            document.getElementById("clienteTipoContratoFaturamento").value = cliente.TipoContrato || "";
        });

        function openTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
        }

        function habilitarEdicao() {
            document.querySelectorAll("#formCliente input, #formCliente select").forEach(el => {
                if (el.id !== "clienteId") el.disabled = false;
            });
            document.getElementById("btnSalvar").style.display = "inline-block";
            document.getElementById("btnCancelar").style.display = "inline-block";
        }

        function cancelarEdicao() {
            location.reload();
        }
    </script>

    <script src="../JS/base.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
