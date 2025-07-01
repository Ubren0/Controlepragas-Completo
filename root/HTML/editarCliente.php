<?php
session_start();
require_once('../PHP/conexao.php');
require_once('../PHP/logica_cliente.php');

// Verifica se o ID do cliente foi passado pela URL
if (isset($_GET['id'])) {
    $cliente_id = $_GET['id'];
    $cliente = buscaClientePorId($pdo, $cliente_id);

    // Se o cliente não for encontrado, exibe uma mensagem de erro
    if (!$cliente) {
        die("Cliente não encontrado.");
    }
} else {
    // Se nenhum ID foi fornecido, exibe uma mensagem de erro
    die("ID do cliente não fornecido.");
}
?>
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
        <form id="formCliente" action="../PHP/logica_cliente.php" method="POST">
            <input type="hidden" name="acao" value="editar">
            <input type="hidden" name="idCliente" value="<?php echo htmlspecialchars($cliente['ID_Cliente']); ?>">

            <h1>Dashboard do Cliente</h1>

            <!-- Seção Informações Gerais -->
            <div id="informacoes">
                <h3>Informações Gerais</h3>
                <label>ID:</label>
                <input id="clienteId" disabled readonly value="<?php echo htmlspecialchars($cliente['ID_Cliente']); ?>">
                
                <label>Nome:</label>
                <input id="clienteNome" name="nome" disabled value="<?php echo htmlspecialchars($cliente['Nome'] ?? ''); ?>">
                
                <label>CNPJ/CPF:</label>
                <input id="clienteCnpjCpf" name="cpfcnpj" disabled value="<?php echo htmlspecialchars($cliente['CPFCNPJ'] ?? ''); ?>">

                <label>Email:</label>
                <input id="clienteEmail" name="email" disabled value="<?php echo htmlspecialchars($cliente['Email'] ?? ''); ?>">

                <label>Telefone:</label>
                <input id="clienteTelefone" name="telefone" disabled value="<?php echo htmlspecialchars($cliente['Telefone'] ?? ''); ?>">
                
                <label>Tipo Cliente:</label>
                <select id="tipoCliente" name="tipoCliente" disabled>
                    <option value="F" <?php echo ($cliente['TipoCliente'] == 'F') ? 'selected' : ''; ?>>Pessoa Física</option>
                    <option value="J" <?php echo ($cliente['TipoCliente'] == 'J') ? 'selected' : ''; ?>>Pessoa Jurídica</option>
                </select>
            </div>

            <hr>

            <div id="endereco">
                <h3>Endereço</h3>
                <label>CEP:</label>
                <input id="clienteCep" name="cep" disabled value="<?php echo htmlspecialchars($cliente['CEP'] ?? ''); ?>">
                <label>Rua:</label>
                <input id="clienteRua" name="rua" disabled value="<?php echo htmlspecialchars($cliente['Rua'] ?? ''); ?>">
                <label>Bairro:</label>
                <input id="clienteBairro" name="bairro" disabled value="<?php echo htmlspecialchars($cliente['Bairro'] ?? ''); ?>">
                <label>Cidade:</label>
                <input id="clienteCidade" name="cidade" disabled value="<?php echo htmlspecialchars($cliente['Cidade'] ?? ''); ?>">
                <label>Estado:</label>
                <input id="clienteEstado" name="estado" disabled value="<?php echo htmlspecialchars($cliente['Estado'] ?? ''); ?>">
            </div>

            <!-- Botões de Ação -->
            <button type="button" class="btn" onclick="habilitarEdicao()">✏️ Editar</button>
            <button id="btnSalvar" type="submit" class="btn" style="display:none;">💾 Salvar</button>
            <button id="btnCancelar" type="button" class="btn" style="display:none;" onclick="cancelarEdicao()">❌ Cancelar</button>
        </form>
    </div>
    <script src="../JS/editarCliente.js"></script>
    <script src="../JS/base.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>