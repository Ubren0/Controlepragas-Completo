-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 01-Jul-2025 às 02:07
-- Versão do servidor: 5.7.36
-- versão do PHP: 8.1.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `greensea`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `agendamento`
--

CREATE TABLE `agendamento` (
  `IDagendamento` int(11) NOT NULL,
  `DataHora` datetime NOT NULL,
  `Status` enum('Agendado','Concluído','Cancelado') NOT NULL DEFAULT 'Agendado',
  `IDusuario` int(11) DEFAULT NULL,
  `idServico` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `agendamento`
--

INSERT INTO `agendamento` (`IDagendamento`, `DataHora`, `Status`, `IDusuario`, `idServico`) VALUES
(1, '2025-06-18 09:00:00', 'Agendado', 1, 1),
(2, '2025-07-02 09:00:00', 'Agendado', 1, 2),
(3, '2025-06-30 09:00:00', 'Agendado', 1, 3),
(4, '2025-07-01 09:00:00', 'Agendado', 1, 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `cargo`
--

CREATE TABLE `cargo` (
  `IDcargo` int(11) NOT NULL,
  `Descricao` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `cargo`
--

INSERT INTO `cargo` (`IDcargo`, `Descricao`) VALUES
(1, 'Administrador'),
(2, 'Usuário');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cliente`
--

CREATE TABLE `cliente` (
  `ID_Cliente` int(11) NOT NULL,
  `CPFCNPJ` varchar(20) DEFAULT NULL,
  `Nome` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Telefone` varchar(15) DEFAULT NULL,
  `TipoCliente` enum('F','J') NOT NULL,
  `Rua` varchar(255) DEFAULT NULL,
  `Bairro` varchar(100) DEFAULT NULL,
  `Cidade` varchar(100) DEFAULT NULL,
  `Estado` varchar(50) DEFAULT NULL,
  `CEP` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `cliente`
--

INSERT INTO `cliente` (`ID_Cliente`, `CPFCNPJ`, `Nome`, `Email`, `Telefone`, `TipoCliente`, `Rua`, `Bairro`, `Cidade`, `Estado`, `CEP`) VALUES
(1, '12345678901', 'João Silva', 'joao@email.com', '(11) 99999-1234', 'F', 'Rua das Flores, 123', 'Centro', 'São Paulo', 'SP', '01000-000'),
(2, '98765432100', 'Maria Santos', 'maria@email.com', '(11) 88888-5678', 'F', 'Av. Paulista, 456', 'Bela Vista', 'São Paulo', 'SP', '01310-000'),
(3, '12345678000199', 'Empresa ABC Ltda', 'contato@abc.com', '(11) 3333-4444', 'J', 'Rua Comercial, 789', 'Vila Madalena', 'São Paulo', 'SP', '05433-000'),
(4, '321321321', 'ricardo', 'dsfds@gmail.com', '321321543543', 'F', 'Rua Nagasaki', 'Cidade Continental', 'Serra', 'ES', '29163622');

-- --------------------------------------------------------

--
-- Estrutura da tabela `orcamento`
--

CREATE TABLE `orcamento` (
  `IDorcamento` int(11) NOT NULL,
  `ValorTotal` decimal(10,2) DEFAULT NULL,
  `DataHora_Inicio` datetime DEFAULT NULL,
  `DataHora_Fim` datetime DEFAULT NULL,
  `Aprovacao` tinyint(1) DEFAULT NULL,
  `Desconto` decimal(10,2) DEFAULT NULL,
  `idCliente` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `orcamento`
--

INSERT INTO `orcamento` (`IDorcamento`, `ValorTotal`, `DataHora_Inicio`, `DataHora_Fim`, `Aprovacao`, `Desconto`, `idCliente`) VALUES
(1, '150.00', '2025-06-18 09:00:00', NULL, 1, NULL, 1),
(2, '100.00', '2025-07-02 09:00:00', NULL, 1, NULL, 2),
(3, '150.00', '2025-06-30 09:00:00', NULL, 1, NULL, 3),
(4, '250.00', '2025-07-01 09:00:00', NULL, 1, NULL, 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `servico`
--

CREATE TABLE `servico` (
  `IDservico` int(11) NOT NULL,
  `Descricao` text,
  `Periodicidade` varchar(50) DEFAULT NULL,
  `idOrcamento` int(11) DEFAULT NULL,
  `IDtiposervico` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `servico`
--

INSERT INTO `servico` (`IDservico`, `Descricao`, `Periodicidade`, `idOrcamento`, `IDtiposervico`) VALUES
(1, 'Agendamento automático', NULL, 1, 1),
(2, 'Agendamento automático', NULL, 2, 4),
(3, 'Agendamento automático', NULL, 3, 1),
(4, 'Agendamento automático', NULL, 4, 7);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tiposervico`
--

CREATE TABLE `tiposervico` (
  `IDtiposervico` int(11) NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `ValorTipoServico` decimal(10,2) DEFAULT NULL,
  `ValorBase` decimal(10,2) DEFAULT NULL,
  `TempoEstimado` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tiposervico`
--

INSERT INTO `tiposervico` (`IDtiposervico`, `Nome`, `ValorTipoServico`, `ValorBase`, `TempoEstimado`) VALUES
(1, 'Dedetização Residencial', '150.00', '150.00', '02:00:00'),
(2, 'Dedetização Comercial', '300.00', '300.00', '04:00:00'),
(3, 'Controle de Formigas', '80.00', '80.00', '01:30:00'),
(4, 'Controle de Baratas', '100.00', '100.00', '01:45:00'),
(5, 'Desinsetização', '120.00', '120.00', '02:30:00'),
(6, 'Controle de Roedores', '180.00', '180.00', '03:00:00'),
(7, 'Descupinização', '250.00', '250.00', '05:00:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `IDusuario` int(11) NOT NULL,
  `Login` varchar(100) NOT NULL,
  `nome_completo` varchar(255) NOT NULL,
  `Senha` varchar(255) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Telefone` varchar(15) DEFAULT NULL,
  `idCargo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`IDusuario`, `Login`, `nome_completo`, `Senha`, `Email`, `Telefone`, `idCargo`) VALUES
(1, 'admin', 'Administrador do Sistema', '$2y$12$U3egbI0oth6z/QimDsuCrufJueCSpZBKxMM9UHtubtB.aJdl7MGpi', 'admin@sistema.com', NULL, 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `agendamento`
--
ALTER TABLE `agendamento`
  ADD PRIMARY KEY (`IDagendamento`),
  ADD KEY `IDusuario` (`IDusuario`),
  ADD KEY `idServico` (`idServico`);

--
-- Índices para tabela `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`IDcargo`);

--
-- Índices para tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`ID_Cliente`),
  ADD UNIQUE KEY `CPFCNPJ` (`CPFCNPJ`);

--
-- Índices para tabela `orcamento`
--
ALTER TABLE `orcamento`
  ADD PRIMARY KEY (`IDorcamento`),
  ADD KEY `idCliente` (`idCliente`);

--
-- Índices para tabela `servico`
--
ALTER TABLE `servico`
  ADD PRIMARY KEY (`IDservico`),
  ADD KEY `idOrcamento` (`idOrcamento`),
  ADD KEY `IDtiposervico` (`IDtiposervico`);

--
-- Índices para tabela `tiposervico`
--
ALTER TABLE `tiposervico`
  ADD PRIMARY KEY (`IDtiposervico`);

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`IDusuario`),
  ADD UNIQUE KEY `Login` (`Login`),
  ADD KEY `idCargo` (`idCargo`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agendamento`
--
ALTER TABLE `agendamento`
  MODIFY `IDagendamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `cargo`
--
ALTER TABLE `cargo`
  MODIFY `IDcargo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `ID_Cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `orcamento`
--
ALTER TABLE `orcamento`
  MODIFY `IDorcamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `servico`
--
ALTER TABLE `servico`
  MODIFY `IDservico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tiposervico`
--
ALTER TABLE `tiposervico`
  MODIFY `IDtiposervico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `IDusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `agendamento`
--
ALTER TABLE `agendamento`
  ADD CONSTRAINT `agendamento_ibfk_1` FOREIGN KEY (`IDusuario`) REFERENCES `usuario` (`IDusuario`),
  ADD CONSTRAINT `agendamento_ibfk_2` FOREIGN KEY (`idServico`) REFERENCES `servico` (`IDservico`);

--
-- Limitadores para a tabela `orcamento`
--
ALTER TABLE `orcamento`
  ADD CONSTRAINT `orcamento_ibfk_1` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`ID_Cliente`);

--
-- Limitadores para a tabela `servico`
--
ALTER TABLE `servico`
  ADD CONSTRAINT `servico_ibfk_1` FOREIGN KEY (`idOrcamento`) REFERENCES `orcamento` (`IDorcamento`),
  ADD CONSTRAINT `servico_ibfk_2` FOREIGN KEY (`IDtiposervico`) REFERENCES `tiposervico` (`IDtiposervico`);

--
-- Limitadores para a tabela `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`idCargo`) REFERENCES `cargo` (`IDcargo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
