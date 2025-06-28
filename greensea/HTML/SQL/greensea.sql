CREATE SCHEMA IF NOT EXISTS `greensea`;
USE `greensea`;
DROP SCHEMA IF EXISTS `greensea`;

CREATE TABLE IF NOT EXISTS Cargo (
    IDcargo INT PRIMARY KEY AUTO_INCREMENT,
    Descricao VARCHAR(100)
);


CREATE TABLE IF NOT EXISTS Usuario (
    IDusuario INT PRIMARY KEY AUTO_INCREMENT,
    Login VARCHAR(100) NOT NULL UNIQUE,
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
    TipoCliente VARCHAR(1) -- ou ENUM('F','J') se preferir
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


CREATE TABLE IF NOT EXISTS Servico (
    IDservico INT PRIMARY KEY AUTO_INCREMENT,
    Descricao TEXT,
    Periodicidade VARCHAR(50),
    idOrcamento INT,
    idCliente INT,
    FOREIGN KEY (idOrcamento) REFERENCES Orcamento(IDorcamento),
    FOREIGN KEY (idCliente) REFERENCES Cliente(ID_Cliente)
);


CREATE TABLE IF NOT EXISTS TipoServico (
    IDtiposervico INT PRIMARY KEY AUTO_INCREMENT,
    ValorTipoServico DECIMAL(10,2),
    ValorBase DECIMAL(10,2),
    TempoEstimado TIME
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


CREATE TABLE IF NOT EXISTS Possui (
    IDservico INT,
    IDtiposervico INT,
    PRIMARY KEY (IDservico, IDtiposervico),
    FOREIGN KEY (IDservico) REFERENCES Servico(IDservico),
    FOREIGN KEY (IDtiposervico) REFERENCES TipoServico(IDtiposervico)
);


CREATE TABLE IF NOT EXISTS Faz (
    IDusuario INT,
    IDagendamento INT,
    PRIMARY KEY (IDusuario, IDagendamento),
    FOREIGN KEY (IDusuario) REFERENCES Usuario(IDusuario),
    FOREIGN KEY (IDagendamento) REFERENCES Agendamento(IDagendamento)
);
