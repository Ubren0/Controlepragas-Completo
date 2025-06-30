DROP SCHEMA IF EXISTS `greensea`;
CREATE SCHEMA IF NOT EXISTS `greensea`;
USE `greensea`;

CREATE TABLE IF NOT EXISTS Cargo (
    IDcargo INT PRIMARY KEY AUTO_INCREMENT,
    Descricao VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS Usuario (
    IDusuario INT PRIMARY KEY AUTO_INCREMENT,
    Login VARCHAR(100) NOT NULL UNIQUE,
    nome_completo VARCHAR(255) NOT NULL, -- Adicionada esta linha
    Senha VARCHAR(255) NOT NULL,
    Email VARCHAR(100),
    Telefone VARCHAR(15),
    idCargo INT,
    FOREIGN KEY (idCargo) REFERENCES Cargo(IDcargo)
);

CREATE TABLE IF NOT EXISTS Cliente (
    ID_Cliente INT PRIMARY KEY AUTO_INCREMENT,
    CPFCNPJ VARCHAR(20) UNIQUE,
    Nome VARCHAR(100),
    Email VARCHAR(100),
    Telefone VARCHAR(15),
    TipoCliente ENUM('F','J') NOT NULL, -- Alterado para ENUM para consistência
    Rua VARCHAR(255) NULL,
    Bairro VARCHAR(100) NULL,
    Cidade VARCHAR(100) NULL,
    Estado VARCHAR(50) NULL,
    CEP VARCHAR(10) NULL
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

-- Tabela Servico ajustada: removido idCliente e adicionado IDtiposervico
CREATE TABLE IF NOT EXISTS Servico (
    IDservico INT PRIMARY KEY AUTO_INCREMENT,
    Descricao TEXT,
    Periodicidade VARCHAR(50),
    idOrcamento INT,
    IDtiposervico INT, -- Adicionado para relacionar com o tipo de serviço
    FOREIGN KEY (idOrcamento) REFERENCES Orcamento(IDorcamento),
    FOREIGN KEY (IDtiposervico) REFERENCES TipoServico(IDtiposervico) -- Chave estrangeira
);

-- Tabela TipoServico ajustada: adicionado Nome para identificação
CREATE TABLE IF NOT EXISTS TipoServico (
    IDtiposervico INT PRIMARY KEY AUTO_INCREMENT,
    Nome VARCHAR(100) NOT NULL, -- Adicionado para identificar o serviço
    ValorTipoServico DECIMAL(10,2),
    ValorBase DECIMAL(10,2),
    TempoEstimado TIME
);

-- Tabela Agendamento ajustada: melhor estrutura de data/hora e status
CREATE TABLE IF NOT EXISTS Agendamento (
    IDagendamento INT PRIMARY KEY AUTO_INCREMENT,
    DataHora DATETIME NOT NULL, -- Unifica data e hora
    Status ENUM('Agendado', 'Concluído', 'Cancelado') NOT NULL DEFAULT 'Agendado', -- Status do agendamento
    IDusuario INT,
    idServico INT,
    FOREIGN KEY (IDusuario) REFERENCES Usuario(IDusuario),
    FOREIGN KEY (idServico) REFERENCES Servico(IDservico)
);

-- --- DADOS INICIAIS (SEED DATA) ---

-- 1. Inserir os cargos padrão
INSERT INTO `Cargo` (`IDcargo`, `Descricao`) VALUES
(1, 'Administrador'),
(2, 'Usuário');

-- 2. Inserir um usuário administrador padrão
-- Login: admin
-- Senha: 1234
-- O hash abaixo foi gerado com password_hash('1234', PASSWORD_DEFAULT)
INSERT INTO `Usuario` (`IDusuario`, `Login`, `nome_completo`, `Senha`, `Email`, `Telefone`, `idCargo`) VALUES
(1, 'admin', 'Administrador do Sistema', '$2y$10$9b5P.pZJ1vVAPfUe.yLgHezJJv.LgBL9R6n5sFfCj/sTjYxGz5M0q', 'admin@sistema.com', NULL, 1);

-- 3. Inserir dados de exemplo para testes
-- Clientes de exemplo
INSERT INTO `Cliente` (`ID_Cliente`, `CPFCNPJ`, `Nome`, `Email`, `Telefone`, `TipoCliente`, `Rua`, `Bairro`, `Cidade`, `Estado`, `CEP`) VALUES
(1, '12345678901', 'João Silva', 'joao@email.com', '(11) 99999-1234', 'F', 'Rua das Flores, 123', 'Centro', 'São Paulo', 'SP', '01000-000'),
(2, '98765432100', 'Maria Santos', 'maria@email.com', '(11) 88888-5678', 'F', 'Av. Paulista, 456', 'Bela Vista', 'São Paulo', 'SP', '01310-000'),
(3, '12345678000199', 'Empresa ABC Ltda', 'contato@abc.com', '(11) 3333-4444', 'J', 'Rua Comercial, 789', 'Vila Madalena', 'São Paulo', 'SP', '05433-000'),
(4, '98765432000188', 'Restaurante Bom Sabor', 'chef@bomsabor.com', '(11) 2222-3333', 'J', 'Rua dos Sabores, 321', 'Liberdade', 'São Paulo', 'SP', '01503-000'),
(5, '11122233344', 'Ana Costa', 'ana@email.com', '(11) 7777-8888', 'F', 'Rua Vergueiro, 654', 'Vila Mariana', 'São Paulo', 'SP', '04101-000');

-- Tipos de serviço de exemplo
INSERT INTO `TipoServico` (`IDtiposervico`, `Nome`, `ValorTipoServico`, `ValorBase`, `TempoEstimado`) VALUES
(1, 'Dedetização Residencial', 150.00, 150.00, '02:00:00'),
(2, 'Dedetização Comercial', 300.00, 300.00, '04:00:00'),
(3, 'Controle de Formigas', 80.00, 80.00, '01:30:00'),
(4, 'Controle de Baratas', 100.00, 100.00, '01:45:00'),
(5, 'Desinsetização', 120.00, 120.00, '02:30:00'),
(6, 'Controle de Roedores', 180.00, 180.00, '03:00:00'),
(7, 'Descupinização', 250.00, 250.00, '05:00:00');
