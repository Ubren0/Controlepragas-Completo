<?php
// ... (código de conexão existente) ...

// 4. SQL para criar as tabelas (com a tabela Usuario atualizada)
$sql_create_tables = "
CREATE TABLE IF NOT EXISTS Cargo (
    IDcargo INT PRIMARY KEY AUTO_INCREMENT,
    Descricao VARCHAR(100) NOT NULL UNIQUE
);

-- Tabela Usuario AJUSTADA para incluir a coluna Nome
CREATE TABLE IF NOT EXISTS Usuario (
    IDusuario INT PRIMARY KEY AUTO_INCREMENT,
    Nome VARCHAR(255) NOT NULL, -- COLUNA ADICIONADA
    Login VARCHAR(100) NOT NULL UNIQUE,
    Senha VARCHAR(255) NOT NULL,
    Email VARCHAR(100),
    Telefone VARCHAR(15),
    idCargo INT,
    FOREIGN KEY (idCargo) REFERENCES Cargo(IDcargo)
);

-- ... (resto das tabelas: Cliente, Orcamento, TipoServico, Servico, Agendamento) ...
CREATE TABLE IF NOT EXISTS Cliente (
    ID_Cliente INT PRIMARY KEY AUTO_INCREMENT,
    CPFCNPJ VARCHAR(20) UNIQUE,
    Nome VARCHAR(100),
    Email VARCHAR(100),
    Telefone VARCHAR(15),
    TipoCliente VARCHAR(1)
);

CREATE TABLE IF NOT EXISTS Orcamento (
    IDorcamento INT PRIMARY KEY AUTO_INCREMENT,
    ValorTotal DECIMAL(10,2),
    DataHora_Inicio DATETIME,
    DataHora_Fim DATETIME,
    Aprovacao BOOLEAN,
    Desconto DECIMAL(10,2),
    idCliente INT,
    FOREIGN KEY (idCliente) REFERENCES Cliente(ID_Cliente)
);

CREATE TABLE IF NOT EXISTS TipoServico (
    IDtiposervico INT PRIMARY KEY AUTO_INCREMENT,
    Nome VARCHAR(100) NOT NULL,
    Descricao TEXT,
    Categoria VARCHAR(50),
    ValorBase DECIMAL(10,2),
    TempoEstimado TIME,
    PeriodicidadeRecomendada VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS Servico (
    IDservico INT PRIMARY KEY AUTO_INCREMENT,
    Descricao TEXT,
    Periodicidade VARCHAR(50),
    idOrcamento INT,
    idCliente INT,
    idTipoServico INT,
    FOREIGN KEY (idOrcamento) REFERENCES Orcamento(IDorcamento),
    FOREIGN KEY (idCliente) REFERENCES Cliente(ID_Cliente),
    FOREIGN KEY (idTipoServico) REFERENCES TipoServico(IDtiposervico)
);

CREATE TABLE IF NOT EXISTS Agendamento (
    IDagendamento INT PRIMARY KEY AUTO_INCREMENT,
    Disponibilidade BOOLEAN,
    Hora TIME,
    Data DATE,
    IDusuario INT,
    idServico INT,
    FOREIGN KEY (IDusuario) REFERENCES Usuario(IDusuario),
    FOREIGN KEY (idServico) REFERENCES Servico(IDservico)
);

-- Vamos inserir alguns cargos padrão se a tabela estiver vazia
INSERT IGNORE INTO Cargo (IDcargo, Descricao) VALUES (1, 'Administrador'), (2, 'Técnico'), (3, 'Atendente');
";

// ... (resto do código de execução e limpeza de resultados) ...
if (!$conn->multi_query($sql_create_tables)) {
    die("Erro ao criar as tabelas: " . $conn->error);
}

while ($conn->next_result()) {
    if ($result = $conn->store_result()) {
        $result->free();
    }
}
?>
