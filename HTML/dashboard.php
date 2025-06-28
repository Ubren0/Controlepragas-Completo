<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Controle de Pragas</title>
    <link rel="icon" href="../IMG/logo.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../CSS/base.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
    <!-- Barra lateral de navegação (Sidebar) - Sem alterações -->
    <div>
        <nav class="sidebar">
            <h2>Controle de Pragas</h2>
            <ul>
                <li onclick="window.location.href='dashboard.php'"><i class="bi bi-house-door-fill"></i> Dashboard</li>
                <li onclick="window.location.href='clientes.php'"><i class="bi bi-buildings-fill"></i> Clientes</li>
                <li onclick="window.location.href='servico.php'"><i class="bi bi-bug-fill"></i> Serviços</li>
                <li onclick="window.location.href='agenda.php'"><i class="bi bi-calendar-event-fill"></i> Agenda</li>
                <li class="config-toggle" onclick="toggleSubmenu()">
                    <i class="bi bi-gear-fill"></i> Configurações <span id="seta">▼</span>
                </li>
                <ul class="submenu" id="submenu">
                    <li onclick="window.location.href='usuarios.php'"><i class="bi bi-person-fill"></i> Usuários</li>
                    <li onclick="window.location.href='tipoServico.php'"><i class="bi bi-box-fill"></i> Tipo de Serviço</li>
                </ul>
                <li onclick="logout()"><i class="bi bi-box-arrow-right"></i> Sair</li>
            </ul>
        </nav>
    </div>

    <!-- Conteúdo principal - ADAPTADO para usar a classe "content" original -->
    <div class="content">
        <header class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
            <div>
                <h1 class="h2">Dashboard</h1>
                <p class="text-muted">Visão geral do sistema</p>
            </div>
        </header>

        <!-- Cards de resumo (usando grid Bootstrap) -->
        <section class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle text-muted">Total de Clientes</h6>
                            <h2 class="card-title mb-0" id="clientesCount">...</h2>
                        </div>
                        <div class="icon-bg bg-primary-soft"><i class="bi bi-buildings text-primary"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card h-100">
                     <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle text-muted">Serviços este mês</h6>
                            <h2 class="card-title mb-0" id="servicosCount">...</h2>
                        </div>
                        <div class="icon-bg bg-warning-soft"><i class="bi bi-calendar2-check text-warning"></i></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Gráficos (usando grid Bootstrap) -->
        <section class="row">
            <div class="col-lg-8 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Serviços Realizados</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <span id="filtroLabel">Últimos 6 meses</span>
                            </button>
                            <ul class="dropdown-menu" id="filtroGrafico">
                                <li><a class="dropdown-item" href="#" data-periodo="1m">Último mês</a></li>
                                <li><a class="dropdown-item" href="#" data-periodo="3m">Últimos 3 meses</a></li>
                                <li><a class="dropdown-item" href="#" data-periodo="6m">Últimos 6 meses</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body"><canvas id="graficoServicos"></canvas></div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header"><h5 class="card-title mb-0">Tipos de Serviço</h5></div>
                    <div class="card-body"><canvas id="graficoTiposServico"></canvas></div>
                </div>
            </div>
        </section>
        
        <!-- Tabelas (usando grid Bootstrap) -->
        <section class="row">
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header"><h5 class="card-title mb-0">Serviços Recentes</h5></div>
                    <div class="table-responsive"><table class="table table-hover table-sm mb-0"><thead><tr><th>Cliente</th><th>Serviço</th><th>Data</th><th>Status</th></tr></thead><tbody id="tabelaRecentes"></tbody></table></div>
                    <div class="card-footer text-end"><a href="servico.php" class="btn btn-sm btn-outline-primary">Ver todos</a></div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header"><h5 class="card-title mb-0">Próximos Agendamentos</h5></div>
                    <div class="table-responsive"><table class="table table-hover table-sm mb-0"><thead><tr><th>Cliente</th><th>Serviço</th><th>Data</th></tr></thead><tbody id="tabelaAgendamentos"></tbody></table></div>
                    <div class="card-footer text-end"><a href="agenda.php" class="btn btn-sm btn-success">Agendar novo</a></div>
                </div>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/base.js"></script>
    <script src="../js/dashboard.js"></script>
</body>
</html>