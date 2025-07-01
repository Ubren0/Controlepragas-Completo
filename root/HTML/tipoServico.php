<?php
// Garante que a sessão seja iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../PHP/logica_tiposervico.php';

// $tipos_servico_stmt já foi criado na lógica
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <!-- Ícone da aba -->
    <link rel="icon" href="../IMG/logo.ico">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipos de Serviços</title>   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/base.css">
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

    <!-- Conteúdo -->
    <div class="content">
        <h1>⚙ Configuração de Tipos de Serviço</h1>

        <!-- Mensagens -->
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

        <!-- Formulário -->
        <div class="card">
            <h3>📝 Cadastrar Novo Tipo de Serviço</h3>
            <form id="formServico" action="../PHP/logica_tiposervico.php" method="POST">
                <input type="hidden" name="acao" value="cadastrar_tiposervico">
                
                <div class="form-group">
                    <label for="nomeServico">Nome do Serviço: *</label>
                    <input type="text" id="nomeServico" name="nome" required 
                           pattern="[A-Za-zÀ-ÿ\s]{3,50}"
                           title="Nome deve ter entre 3 e 50 caracteres">
                </div>

                <div class="form-group">
                    <label for="valorServico">Valor Base (R$): *</label>
                    <input type="number" id="valorServico" name="valor" required
                           min="0" step="0.01">
                </div>

                <div class="form-group">
                    <label for="duracaoServico">Duração Estimada (HH:MM): *</label>
                    <input type="time" id="duracaoServico" name="tempo_estimado" required>
                </div>

                <button type="submit" class="btn-primary">💾 Salvar Serviço</button>
            </form>
        </div>

        <!-- Tabela de Tipos Cadastrados -->
        <div class="card">
            <h3>📋 Tipos de Serviço Cadastrados</h3>
            <table id="tabelaServicos" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Valor Base</th>
                        <th>Tempo Estimado</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($tipos_servico_stmt && $tipos_servico_stmt->rowCount() > 0) {
                        while ($tipo = $tipos_servico_stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "    <td>" . htmlspecialchars($tipo['IDtiposervico']) . "</td>";
                            echo "    <td>" . htmlspecialchars($tipo['Nome']) . "</td>";
                            echo "    <td>R$ " . number_format($tipo['ValorBase'], 2, ',', '.') . "</td>";
                            echo "    <td>" . htmlspecialchars($tipo['TempoEstimado']) . "</td>";
                            echo "    <td class='acoes'>
                                        <a href='../PHP/logica_tiposervico.php?acao=excluir_tiposervico&id=" . $tipo['IDtiposervico'] . "' 
                                           onclick=\"return confirm('Tem certeza que deseja excluir o tipo de serviço " . htmlspecialchars($tipo['Nome']) . "?');\" 
                                           class='btn btn-sm btn-danger'>
                                            🗑️ Excluir
                                        </a>
                                      </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>Nenhum tipo de serviço cadastrado ainda.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="../js/base.js"></script>
</body>

</html>
