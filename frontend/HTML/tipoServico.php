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

        <!-- Formulário -->
        <div class="card">
            <form id="formServico" aria-label="Formulário de cadastro de tipo de serviço">
                <div class="form-group">
                    <label for="nomeServico">Nome do Serviço: *</label>
                    <input type="text" id="nomeServico" required 
                           pattern="[A-Za-zÀ-ÿ\s]{3,50}"
                           title="Nome deve ter entre 3 e 50 caracteres">
                    <span class="error-message" id="nomeError"></span>
                </div>

                <div class="form-group">
                    <label for="descricaoServico">Descrição: *</label>
                    <textarea id="descricaoServico" rows="3" required
                              minlength="10" maxlength="500"></textarea>
                    <span class="error-message" id="descricaoError"></span>
                </div>

                <div class="form-group">
                    <label for="categoriaServico">Categoria: *</label>
                    <select id="categoriaServico" required>
                        <option value="">Selecione uma categoria</option>
                        <option value="desinfeccao">Desinfecção</option>
                        <option value="dedetizacao">Dedetização</option>
                        <option value="controle">Controle de Pragas</option>
                        <option value="outros">Outros</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="valorServico">Valor Base (R$): *</label>
                    <input type="number" id="valorServico" required
                           min="0" step="0.01">
                </div>

                <div class="form-group">
                    <label for="duracaoServico">Duração Estimada (horas): *</label>
                    <input type="number" id="duracaoServico" required
                           min="0.5" step="0.5">
                </div>

                <div class="form-group">
                    <label for="periodicidade">Periodicidade Recomendada:</label>
                    <select id="periodicidade">
                        <option value="unica">Única</option>
                        <option value="mensal">Mensal</option>
                        <option value="trimestral">Trimestral</option>
                        <option value="semestral">Semestral</option>
                        <option value="anual">Anual</option>
                    </select>
                </div>

                <button type="submit" class="btn-primary">Salvar Serviço</button>
            </form>
        </div>

        <!-- Tabela -->
        <div class="card">
            <h3>📋 Serviços Cadastrados</h3>
            <table id="tabelaServicos">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Categoria</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Linhas serão inseridas via JS -->
                </tbody>
            </table>
        </div>
    </div>

    <script src="../js/base.js"></script>
</body>

</html>
