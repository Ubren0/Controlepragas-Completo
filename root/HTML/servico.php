<?php
require_once '../PHP/conexao.php';

// Debug: verificar se existem clientes
$debug_stmt = $pdo->query("SELECT COUNT(*) FROM Cliente");
$total_clientes_debug = $debug_stmt->fetchColumn();

// Buscar tipos de serviço do banco
$tipos_servico_stmt = $pdo->query("SELECT IDtiposervico, Nome FROM TipoServico ORDER BY Nome");
$tipos_servico = $tipos_servico_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <!-- Definindo o ícone do site -->
    <link rel="icon" type="image/x-icon" href="../IMG/logo.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Serviço - Controle de Pragas</title>
    <!-- Link para o CSS da página -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/servico.css">
    <style>
        #secaoAgendarServico {
            display: none;
        }
    </style>
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

    <!-- Conteúdo principal da página -->
    <div id="divbuscaCliente" class="content">
        <h1 class="mb-4">Agendamento de Serviço</h1>

        <!-- Debug info -->
        <div class="alert alert-info">
            <strong>Debug:</strong> Existem <?= $total_clientes_debug ?> clientes no banco de dados.
        </div>

        <!-- Main Card Container for the entire process -->
        <div class="card p-4 shadow bg-white rounded">

            <!-- --- Passo 1: Buscar Cliente --- -->
            <div id="secaoBuscarCliente">
                <h3 class="mb-3">🔍 Buscar Cliente para Agendar</h3>
                <p>Digite o nome ou ID do cliente:</p>

                <div class="mb-3 d-flex align-items-end">
                    <div class="flex-grow-1 me-2">
                        <label for="pesquisaClienteInput" class="form-label">Buscar Cliente:</label>
                        <input type="text" id="pesquisaClienteInput" class="form-control"
                            placeholder="Digite: João, Maria, 1, 2, Empresa...">
                        <small class="form-text text-muted">
                            💡 Dica: Digite apenas 1 caractere. Busca por nome, ID ou CNPJ/CPF
                        </small>
                    </div>
                    <button type="button" class="btn btn-dark h-auto" onclick="pesquisarCliente()">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </div>

                <div id="resultadoPesquisaCliente" class="mb-3"></div>
            </div>

            <!-- --- Separator --- -->
            <hr class="my-4">

            <!-- --- Passo 2: Formulario de Agendamento --- -->
            <div id="secaoAgendarServico">
                <h3 class="mb-3">📅 Agendar Serviços para o Cliente</h3>

                <!-- Display Found Client Info -->
                <div class="mb-4 p-3 bg-light rounded border">
                    <h5>Cliente Selecionado:</h5>
                    <p id="clienteNomeAgendamento"><strong>Nome:</strong> <span
                            id="nomeClienteAgendamentoSpan"></span></p>
                    <p id="clienteEnderecoAgendamento" class="mb-0"><strong>Endereço:</strong> <span
                            id="enderecoClienteAgendamentoSpan"></span></p>
                    <input type="hidden" id="idClienteAgendamento">
                </div>

                <!-- Form for Scheduling Details -->
                <form id="agendamentoFormCompleto" novalidate>

                    <!-- Data do agendamento -->
                    <div class="mb-3">
                        <label for="dataAgendamentoInput" class="form-label">Data do Agendamento:</label>
                        <input type="date" id="dataAgendamentoInput" class="form-control" required>
                        <div class="invalid-feedback">Por favor, selecione uma data.</div>

                        <!-- Área para mostrar a disponibilidade após selecionar a data -->
                        <div id="disponibilidadeInfo"
                            class="mt-3 p-3 bg-info-subtle border border-info-subtle rounded"
                            style="display: none;">
                            <h6>Disponibilidade para <span id="dataSelecionadaSpan"></span>:</h6>
                            <div id="listaAgendamentosNaData">
                                <p class="text-muted">Carregando disponibilidade...</p>
                            </div>
                            <div id="mensagemDisponivel" style="display: none;">
                                <p class="text-success mb-0">✅ A data parece disponível para este cliente/região.</p>
                            </div>
                            <div id="mensagemErroDisponibilidade" style="display: none;">
                                <p class="text-danger mb-0">❌ Erro ao verificar disponibilidade.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Seleção e adição de serviços -->
                    <div class="mb-3">
                        <label for="servicoSelect" class="form-label">Adicionar Serviço:</label>
                        <div class="d-flex align-items-end">
                            <select id="servicoSelect" class="form-select me-2 flex-grow-1" required>
                                <option value="">Selecione um tipo de serviço...</option>
                                <?php foreach ($tipos_servico as $tipo): ?>
                                    <option value="<?= htmlspecialchars($tipo['IDtiposervico']) ?>">
                                        <?= htmlspecialchars($tipo['Nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-secondary h-auto"
                                onclick="adicionarServicoAoAgendamento()">Adicionar</button>
                        </div>
                    </div>

                    <!-- Lista de serviços adicionados -->
                    <div id="listaServicosAdicionados" class="mb-4">
                        <h5>Serviços a serem agendados:</h5>
                        <ul id="servicosAgendadosUL" class="list-group">
                            <!-- Added services will be listed here by JS -->
                        </ul>
                        <p id="mensagemSemServicos" class="text-muted mt-2">Nenhum serviço adicionado ainda.</p>
                    </div>

                    <!-- Botões de ação do formulário -->
                    <button type="submit" class="btn btn-success w-100">Agendar Serviço(s)</button>
                    <button type="button" class="btn btn-outline-secondary w-100 mt-2"
                        onclick="cancelarAgendamento()">Cancelar e Buscar Outro Cliente</button>
                </form>
            </div>

        </div> <!-- End of Main Card -->

    </div>

    <!-- Modal de sucesso ao agendar o serviço (Bootstrap) -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel">Sucesso!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center">✅ Serviço(s) agendado(s) com sucesso!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Link para os scripts JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/base.js"></script>
    <script src="../js/servico.js"></script>

</body>

</html>