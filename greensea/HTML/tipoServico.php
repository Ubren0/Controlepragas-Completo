<?php
session_start(); // Inicia a sessão para mensagens de feedback
require_once 'db_config.php'; // Inclui a conexão e criação do banco

// --- LÓGICA PARA SALVAR UM NOVO SERVIÇO ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nomeServico'])) {
    // 1. Coletar e limpar os dados do formulário
    $nome = trim($_POST['nomeServico']);
    $descricao = trim($_POST['descricaoServico']);
    $categoria = $_POST['categoriaServico'];
    $valorBase = (float) $_POST['valorServico'];
    $duracaoHoras = (float) $_POST['duracaoServico'];
    $periodicidade = $_POST['periodicidade'];

    // 2. Converter duração (ex: 1.5 horas) para o formato TIME do SQL (HH:MM:SS)
    $horas = floor($duracaoHoras);
    $minutos = ($duracaoHoras - $horas) * 60;
    $tempoEstimado = sprintf('%02d:%02d:00', $horas, $minutos);

    // 3. Preparar a query SQL para evitar SQL Injection
    $sql = "INSERT INTO TipoServico (Nome, Descricao, Categoria, ValorBase, TempoEstimado, PeriodicidadeRecomendada) VALUES (?, ?, ?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        // 4. Vincular parâmetros
        $stmt->bind_param("sssds", $nome, $descricao, $categoria, $valorBase, $tempoEstimado, $periodicidade);

        // 5. Executar e verificar
        if ($stmt->execute()) {
            $_SESSION['message'] = "Serviço cadastrado com sucesso!";
        } else {
            $_SESSION['message'] = "Erro ao cadastrar o serviço: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Erro ao preparar a query: " . $conn->error;
    }

    // Redireciona para a mesma página para evitar reenvio do formulário
    header("Location: tipoServico.php");
    exit();
}

// --- LÓGICA PARA BUSCAR OS SERVIÇOS CADASTRADOS ---
$servicos = [];
$sql_select = "SELECT IDtiposervico, Nome, Descricao, Categoria FROM TipoServico ORDER BY Nome ASC";
$result = $conn->query($sql_select);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $servicos[] = $row;
    }
}
$conn->close(); // Fechar a conexão após buscar os dados
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

        <?php
        // Exibe a mensagem de feedback da sessão, se houver
        if (isset($_SESSION['message'])) {
            echo '<div class="alert alert-info">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']); // Limpa a mensagem para não aparecer de novo
        }
        ?>

        <!-- Formulário -->
        <div class="card">
            <form id="formServico" method="POST" action="tipoServico.php" aria-label="Formulário de cadastro de tipo de serviço">
                <div class="form-group">
                    <label for="nomeServico">Nome do Serviço: *</label>
                    <input type="text" id="nomeServico" name="nomeServico" required 
                           pattern="[A-Za-zÀ-ÿ\s]{3,100}"
                           title="Nome deve ter entre 3 e 100 caracteres">
                    <span class="error-message" id="nomeError"></span>
                </div>

                <div class="form-group">
                    <label for="descricaoServico">Descrição: *</label>
                    <textarea id="descricaoServico" name="descricaoServico" rows="3" required
                              minlength="10" maxlength="500"></textarea>
                    <span class="error-message" id="descricaoError"></span>
                </div>

                <div class="form-group">
                    <label for="categoriaServico">Categoria: *</label>
                    <select id="categoriaServico" name="categoriaServico" required>
                        <option value="">Selecione uma categoria</option>
                        <option value="desinfeccao">Desinfecção</option>
                        <option value="dedetizacao">Dedetização</option>
                        <option value="controle">Controle de Pragas</option>
                        <option value="outros">Outros</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="valorServico">Valor Base (R$): *</label>
                    <input type="number" id="valorServico" name="valorServico" required
                           min="0" step="0.01">
                </div>

                <div class="form-group">
                    <label for="duracaoServico">Duração Estimada (horas): *</label>
                    <input type="number" id="duracaoServico" name="duracaoServico" required
                           min="0.5" step="0.5">
                </div>

                <div class="form-group">
                    <label for="periodicidade">Periodicidade Recomendada:</label>
                    <select id="periodicidade" name="periodicidade">
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
                    <?php if (empty($servicos)): ?>
                        <tr>
                            <td colspan="4">Nenhum serviço cadastrado ainda.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($servicos as $servico): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($servico['Nome']); ?></td>
                                <td><?php echo htmlspecialchars($servico['Descricao']); ?></td>
                                <td><?php echo htmlspecialchars($servico['Categoria']); ?></td>
                                <td>
                                    <!-- Botões de ação (ex: editar/excluir) podem ser adicionados aqui -->
                                    <button class="btn-icon" title="Editar"><i class="bi bi-pencil-square"></i></button>
                                    <button class="btn-icon" title="Excluir"><i class="bi bi-trash-fill"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="../js/base.js"></script>
</body>

</html>