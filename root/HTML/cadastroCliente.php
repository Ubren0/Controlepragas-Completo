<?php
// Pega o status da URL, se existir (ex: ?status=erro_campos)
$status = $_GET['status'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <link rel="icon" type="image/x-icon" href="../IMG/logo.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente - Controle de Pragas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../CSS/base.css">
</head>

<body>
    <!-- Script de autenticação deve executar primeiro -->
    <script src="../js/auth.js"></script>
    
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
        <h1>Cadastro de Cliente</h1>
        <div class="card">
            <h3>📝 Preencha os dados do Cliente</h3>
            
            <!-- Seção de mensagens para o usuário -->
            <?php if (isset($_SESSION['mensagem_sucesso'])): ?>
                <div class="alert alert-success">
                    <?php 
                        echo $_SESSION['mensagem_sucesso']; 
                        unset($_SESSION['mensagem_sucesso']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['mensagem_erro'])): ?>
                <div class="alert alert-danger">
                    <?php 
                        echo $_SESSION['mensagem_erro']; 
                        unset($_SESSION['mensagem_erro']);
                    ?>
                </div>
            <?php endif; ?>

            <form action="../PHP/logica_cliente.php" method="POST" id="formCadastro">
                <!-- Campo oculto para indicar a ação -->
                <input type="hidden" name="acao" value="cadastrar">

                <div class="input-group">
                    <label for="nome">Nome Completo / Razão Social*</label>
                    <input type="text" id="nome" name="nome" required>
                </div>

                <div class="input-group">
                    <label for="tipoCliente">Tipo de Cliente*</label>
                    <select id="tipoCliente" name="tipoCliente" required>
                        <option value="">Selecione...</option>
                        <option value="F">Pessoa Física</option>
                        <option value="J">Pessoa Jurídica</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="cpfcnpj">CPF / CNPJ*</label>
                    <input type="text" id="cpfcnpj" name="cpfcnpj" required>
                </div>

                <div class="input-group">
                    <label for="telefone">Telefone</label>
                    <input type="text" id="telefone" name="telefone">
                </div>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
                </div>

                <hr>
                <h3>Endereço</h3>

                <div class="input-group">
                    <label for="cep">CEP</label>
                    <input type="text" id="cep" name="cep">
                </div>

                <div class="input-group">
                    <label for="rua">Rua</label>
                    <input type="text" id="rua" name="rua">
                </div>

                <div class="input-group">
                    <label for="bairro">Bairro</label>
                    <input type="text" id="bairro" name="bairro">
                </div>

                <div class="input-group">
                    <label for="cidade">Cidade</label>
                    <input type="text" id="cidade" name="cidade">
                </div>

                <div class="input-group">
                    <label for="estado">Estado</label>
                    <input type="text" id="estado" name="estado">
                </div>

                <button type="submit" class="btn-salvar">💾 Salvar</button>
            </form>
        </div>
    </div>

    <script src="../JS/base.js"></script>
    <script src="../JS/cadastroCliente.js"></script>
</body>

</html>