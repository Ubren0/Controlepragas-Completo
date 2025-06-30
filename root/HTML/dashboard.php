<?php
// Remover a verificação PHP - vamos usar apenas JavaScript
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - GreenSea</title>
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
                <li class="active" onclick="window.location.href='dashboard.php'">
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
                <ul class="submenu" id="submenu" role="menu">
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
        <h1>🏠 Dashboard</h1>
        <p>Bem-vindo, <strong id="nomeUsuario">Carregando...</strong>!</p>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">👥 Clientes</h5>
                        <h2 id="totalClientes">0</h2>
                        <p class="card-text">Total de clientes cadastrados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">🛠️ Serviços</h5>
                        <h2 id="totalServicos">0</h2>
                        <p class="card-text">Tipos de serviço disponíveis</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title">👤 Usuários</h5>
                        <h2>2</h2>
                        <p class="card-text">Usuários do sistema</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>🚀 Ações Rápidas</h5>
                    </div>
                    <div class="card-body">
                        <a href="cadastroCliente.php" class="btn btn-primary me-2 mb-2">
                            <i class="bi bi-person-plus"></i> Novo Cliente
                        </a>
                        <a href="servico.php" class="btn btn-success me-2 mb-2">
                            <i class="bi bi-calendar-plus"></i> Agendar Serviço
                        </a>
                        <a href="agenda.php" class="btn btn-info me-2 mb-2">
                            <i class="bi bi-calendar-week"></i> Ver Agenda
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>📊 Informações do Sistema</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Usuário:</strong> <span id="loginUsuario">Carregando...</span></p>
                        <p><strong>Nível:</strong> <span id="nivelUsuario">Carregando...</span></p>
                        <p><strong>Data/Hora:</strong> <span id="dataHora"></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/base.js"></script>
    <script>
        // Exibir dados do usuário logado
        document.addEventListener('DOMContentLoaded', function() {
            const dadosUsuario = verificarAutenticacao();
            if (dadosUsuario) {
                document.getElementById('nomeUsuario').textContent = dadosUsuario.nome;
                document.getElementById('loginUsuario').textContent = dadosUsuario.login;
                document.getElementById('nivelUsuario').textContent = dadosUsuario.nivel;
            }
            
            // Atualizar data/hora
            const agora = new Date();
            document.getElementById('dataHora').textContent = agora.toLocaleString('pt-BR');
            
            // Simular dados de estatísticas (você pode integrar com API depois)
            document.getElementById('totalClientes').textContent = '3';
            document.getElementById('totalServicos').textContent = '5';
        });
    </script>
</body>
</html>