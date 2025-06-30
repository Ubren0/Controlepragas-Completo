<?php
require_once '../PHP/conexao.php';

// Verificação básica de estrutura do banco (sem consulta complexa)
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'Cliente'");
    $temClientes = $stmt->rowCount() > 0;
} catch (Exception $e) {
    $temClientes = false;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda - Controle de Pragas</title>
    <link rel="shortcut icon" href="../IMG/logo.ico" type="image/x-icon">
    
    <!-- CSS Frameworks -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/agenda.css">
</head>

<body>
    <!-- Script de autenticação -->
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
                <li class="active" onclick="window.location.href='agenda.php'">
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

    <!-- Conteúdo Principal -->
    <div class="content">
        <header class="agenda-header">
            <h1>📅 Agenda de Serviços</h1>
            <div class="status-info">
                <strong>Status:</strong> <span id="statusCarregamento">Carregando dados...</span>
            </div>
        </header>

        <!-- Filtros e Ações -->
        <section class="filtros-section">
            <div class="card agenda-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="filtroData" class="form-label">Filtrar por Data:</label>
                            <input type="date" id="filtroData" class="form-control" onchange="AgendaController.filtrarAgendamentos()">
                        </div>
                        <div class="col-md-4">
                            <label for="filtroStatus" class="form-label">Status:</label>
                            <select id="filtroStatus" class="form-select" onchange="AgendaController.filtrarAgendamentos()">
                                <option value="">Todos</option>
                                <option value="Agendado">Agendado</option>
                                <option value="Em Andamento">Em Andamento</option>
                                <option value="Concluído">Concluído</option>
                                <option value="Cancelado">Cancelado</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn btn-primary me-2" onclick="window.location.href='servico.php'">
                                <i class="bi bi-plus-circle"></i> Novo Agendamento
                            </button>
                            <button class="btn btn-outline-secondary me-2" onclick="AgendaController.recarregarDados()">
                                <i class="bi bi-arrow-clockwise"></i> Atualizar
                            </button>
                            <button class="btn btn-outline-info" onclick="AgendaController.mostrarTodos()">
                                <i class="bi bi-list"></i> Mostrar Todos
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Lista de Agendamentos -->
        <section class="agendamentos-section">
            <div class="card agenda-card">
                <div class="card-header">
                    <h5><i class="bi bi-calendar-check"></i> Agendamentos</h5>
                </div>
                <div class="card-body">
                    <div id="listaAgendamentos">
                        <div class="loading-container text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Carregando...</span>
                            </div>
                            <p class="mt-2">Carregando agendamentos...</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Resumo Estatístico -->
        <section class="resumo-section">
            <div class="row">
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body text-center">
                            <h5 class="card-title">📋 Total</h5>
                            <h2 id="totalAgendamentos">0</h2>
                            <p class="card-text">Agendamentos</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body text-center">
                            <h5 class="card-title">⏳ Pendentes</h5>
                            <h2 id="agendamentosPendentes">0</h2>
                            <p class="card-text">Para hoje</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body text-center">
                            <h5 class="card-title">✅ Concluídos</h5>
                            <h2 id="agendamentosConcluidos">0</h2>
                            <p class="card-text">Hoje</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-danger">
                        <div class="card-body text-center">
                            <h5 class="card-title">⚠️ Atrasados</h5>
                            <h2 id="agendamentosAtrasados">0</h2>
                            <p class="card-text">Requer atenção</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Scripts -->
    <script src="../js/base.js"></script>
    <script src="../js/agenda-api.js"></script>
    <script src="../js/agenda-ui.js"></script>
    <script src="../js/agenda-controller.js"></script>
    <script>
        // Inicialização da aplicação
        document.addEventListener('DOMContentLoaded', function() {
            AgendaController.inicializar();
        });
    </script>
</body>

</html>