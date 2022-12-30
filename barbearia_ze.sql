-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29-Dez-2022 às 13:35
-- Versão do servidor: 10.4.24-MariaDB
-- versão do PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `barbearia_ze`
--
CREATE DATABASE IF NOT EXISTS `barbearia_ze` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `barbearia_ze`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agendamento`
--

CREATE TABLE `agendamento` (
  `id` int(11) NOT NULL,
  `status_agendamento` int(11) NOT NULL DEFAULT 1,
  `id_cliente` int(11) NOT NULL,
  `id_servico` int(11) NOT NULL,
  `valor` double NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `observacao` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `agendamento`
--

INSERT INTO `agendamento` (`id`, `status_agendamento`, `id_cliente`, `id_servico`, `valor`, `start`, `end`, `observacao`) VALUES
(19, 3, 1, 23, 10.54, '2022-11-28 16:00:00', '2022-11-28 16:30:00', ''),
(25, 2, 74, 10, 30, '2022-11-28 17:30:00', '2022-11-28 18:00:00', ''),
(29, 2, 5, 9, 20, '2022-11-29 12:30:00', '2022-11-29 13:00:00', ''),
(30, 3, 60, 10, 30, '2022-11-29 09:30:00', '2022-11-29 10:00:00', ''),
(34, 2, 1, 23, 10.54, '2022-12-02 11:30:00', '2022-12-02 12:00:00', ''),
(39, 2, 58, 10, 30, '2022-12-07 14:00:00', '2022-12-07 14:30:00', ''),
(42, 1, 73, 9, 20, '2022-12-08 12:00:00', '2022-12-08 12:30:00', ''),
(43, 1, 1, 10, 30, '2022-12-08 13:00:00', '2022-12-08 13:30:00', ''),
(44, 1, 67, 23, 10.54, '2022-12-08 16:00:00', '2022-12-08 16:30:00', ''),
(46, 1, 73, 1, 15, '2022-12-20 21:00:00', '2022-12-20 21:30:00', 'O cliente pode se atrasar...'),
(47, 1, 109, 1, 16, '2022-12-28 21:00:00', '2022-12-28 21:30:00', 'O cliente pode ser que se atrase em 3 minutos...'),
(48, 1, 71, 10, 30, '2022-12-29 20:50:00', '2022-12-29 21:10:00', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cliente`
--

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `sexo` int(1) NOT NULL,
  `data_nascimento` date NOT NULL,
  `telefone` varchar(12) NOT NULL,
  `email` varchar(100) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `endereco` varchar(100) NOT NULL,
  `cep` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `cliente`
--

INSERT INTO `cliente` (`id`, `nome`, `sexo`, `data_nascimento`, `telefone`, `email`, `cpf`, `endereco`, `cep`) VALUES
(1, 'Luiz ', 1, '2001-12-01', '44999999999', 'luiz@gmail.com', '42474802002', 'Av. Perobal', '87538000'),
(2, 'Helio Takahashi', 1, '2001-04-06', '44997240863', 'helio@gmail.com', '97656117067', 'Av. Perobal', '87538000'),
(4, 'Gustavo Nestor', 1, '2001-05-02', '44558781224', 'gustavo@gmail.com', '14785296314', 'Av. Perobal', '87538000'),
(5, 'Guilherme', 1, '2001-05-02', '44558781224', 'guilherme@gmail.com', '14785296314', 'Av. Iporã', '87485000'),
(21, 'Teste 15', 1, '2022-10-22', '44558781224', 'lazaro@gmail.com', '14785296314', 'Av. Perobal', '87485000'),
(23, 'Teste 17', 1, '2001-03-01', '44558781224', 'lazaro@gmail.com', '12312312328', 'Av. Cruzeiro', '87485000'),
(24, 'Teste 18', 1, '2001-02-02', '44558781224', 'teste@gmail.com', '14785296314', 'Av. Cruzeiro', '87485000'),
(49, 'Mateus ', 1, '2001-02-02', '44558781224', 'teste@gmail.com', '12312312328', 'Av. Cruzeiro', '87485000'),
(55, 'Manoel Gome', 1, '1985-02-02', '44558781224', 'lazaro@gmail.com', '12312312328', 'Av. Iporã', '87485000'),
(56, 'Teste 22', 1, '2001-02-02', '44558781224', 'teste@gmail.com', '14785296314', 'Av. Cruzeiro', '87485000'),
(57, 'Teste 23', 1, '2001-02-02', '44558781224', 'teste@gmail.com', '28584101004', 'Av. Cruzeiro', '87485000'),
(58, 'Teste 24', 1, '2002-02-02', '44558781224', 'teste@gmail.com', '12312312328', 'Av. Cruzeiro', '87485000'),
(59, 'Teste 25', 1, '2001-02-02', '44558781224', 'teste@gmail.com', '14785296314', 'Av. Iporã', '87485000'),
(60, 'Teste 26', 1, '2001-02-03', '44558781224', 'teste@gmail.com', '12312312328', 'Av. Iporã', '87485000'),
(61, 'Teste 27', 1, '2001-02-04', '44558781224', 'teste@gmail.com', '12312312328', 'Av. Iporã', '87485000'),
(62, 'Teste 27', 1, '2001-02-02', '44558781224', 'teste@gmail.com', '12312312328', 'Av. Perobal', '87485000'),
(63, 'Teste 27', 1, '2001-02-02', '44558781224', 'teste@gmail.com', '12312312328', 'Av. Iporã', '87485000'),
(65, 'Lucas Lima', 1, '0000-00-00', '44887485232', 'teste@gmail.com', '12312312312', 'Av. Perobal', '57575000'),
(66, 'Henrique', 1, '2001-03-03', '44858588888', 'teste2@gmail.com', '12312315896', 'Av. Sla', '87485000'),
(67, 'Gustavo', 1, '2002-02-02', '47852158520', 'teste3@gmail.com', '14785214778', 'Av. Brasil', '25874000'),
(68, 'Otavio', 1, '2001-02-02', '47852158520', 'teste4@gmail.com', '14785214778', 'Av. Paraná', '25874000'),
(69, 'Pedro', 1, '2002-02-02', '47852158520', 'teste3@gmail.com', '14785214778', 'Av. Brasil 2', '25874000'),
(70, 'Luiz', 1, '2001-03-03', '4496363699', 'teste@gmail.com', '14785214778', 'Av. Brasil', '25874000'),
(71, 'Helio', 1, '2001-02-04', '47852158520', 'teste3@gmail.com', '14785214778', 'Av.Perobal', '25874000'),
(72, 'Daniel', 1, '2003-02-02', '47852158520', 'teste3@gmail.com', '14785214778', 'Av. Brasil', '25874000'),
(73, 'Joaquim', 1, '2005-01-01', '47852158520', 'teste3@gmail.com', '14785214778', 'Av. Brasil', '25874000'),
(74, 'Anderson', 1, '2004-02-02', '47852158520', 'teste3@gmail.com', '14785214778', 'Av. Num sei', '25874000'),
(76, 'Henrique 2', 1, '2001-03-03', '44858588888', 'teste2@gmail.com', '12312315896', 'Av. Sla', '87485000'),
(78, 'Otavio Luiz 2', 1, '2001-02-02', '47552158520', 'teste4@gmail.com', '14785214778', 'Av. Paraná', '25874000'),
(80, 'Luiz 2', 1, '2001-03-03', '4496363699', 'teste@gmail.com', '14785214778', 'Av. Brasil', '25874000'),
(81, 'Helio 2', 1, '2001-02-04', '47852158520', 'teste3@gmail.com', '14785214778', 'Av.Perobal', '25874000'),
(82, 'Daniel 2', 1, '2003-02-02', '47852158520', 'teste3@gmail.com', '14785214778', 'Av. Brasil', '25874000'),
(83, 'Joaquim 2', 1, '2005-01-01', '47852158520', 'teste3@gmail.com', '14785214778', 'Av. Brasil', '25874000'),
(85, 'Eduardo 2', 1, '2001-05-03', '47852158520', 'teste3@gmail.com', '14785214778', 'Av. Brasil', '25874000'),
(86, 'Lucas Lima 3', 1, '0000-00-00', '44887485232', 'teste@gmail.com', '12312312312', 'Av. Perobal', '57575000'),
(87, 'Henrique 3', 1, '2001-03-03', '44858588888', 'teste2@gmail.com', '12312315896', 'Av. Sla', '87485000'),
(89, 'Otavio 3', 1, '2001-02-02', '47852158520', 'teste4@gmail.com', '14785214778', 'Av. Paraná', '25874000'),
(90, 'Pedro 3', 1, '2002-02-02', '47852158520', 'teste3@gmail.com', '14785214778', 'Av. Brasil 2', '25874000'),
(91, 'Luiz 3', 1, '2001-03-03', '4496363699', 'teste@gmail.com', '14785214778', 'Av. Brasil', '25874000'),
(92, 'Helio 3', 1, '2001-02-04', '47852158520', 'teste3@gmail.com', '14785214778', 'Av.Perobal', '25874000'),
(93, 'Daniel 3', 1, '2003-02-02', '47852158520', 'teste3@gmail.com', '14785214778', 'Av. Brasil', '25874000'),
(94, 'Joaquim 3', 1, '2005-01-01', '47852158520', 'teste3@gmail.com', '14785214778', 'Av. Brasil', '25874000'),
(97, 'Eduardo', 1, '2001-05-03', '47852158520', 'teste3@gmail.com', '14785214778', 'Av. Brasil', '25874000'),
(98, 'Seu zé', 1, '1975-02-27', '44558781224', 'teste@gmail.com', '14785296314', 'Av. Cruzeiro', '87485000'),
(101, 'Teste de CPF', 1, '2001-02-02', '44558781224', 'teste147@gmail.com', '58551838075', 'Av. Cruzeiro', '87485000'),
(102, 'Teste Pag', 1, '2001-02-02', '44999999999', 'teste321456879@gmail.com', '30039294021', 'Av. Cruzeiro', '87485000'),
(103, 'Teste Pag 2', 1, '2001-02-02', '44558781224', 'testess@gmail.com', '28155686086', 'Av. Iporã', '87485000'),
(104, 'Teste de CPF 2', 1, '1978-02-02', '44558781224', 'teste1478523@gmail.com', '54341357093', 'Av. Iporã', '87485000'),
(105, 'Testando', 1, '2000-02-01', '44558781224', 'teste753@gmail.com', '60985475056', 'Av. Cruzeiro', '87485000'),
(107, 'Testando...', 1, '2000-02-02', '44558781224', 'test@gmail.com', '80370725093', 'Av. Cruzeiro', '87485000'),
(108, 'Teste 741 ssxsa', 1, '2001-12-02', '44999999999', 'laz74852@gmail.com', '13608166076', 'Av. Perobal', '87485000'),
(109, 'Teste 28/12/2022', 1, '2004-12-29', '44999999999', 'ts8@gmail.com', '49696538014', 'Av. Cruzeiro', '87485000');

-- --------------------------------------------------------

--
-- Estrutura da tabela `servico`
--

CREATE TABLE `servico` (
  `id` int(11) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `valor` decimal(7,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `servico`
--

INSERT INTO `servico` (`id`, `descricao`, `valor`) VALUES
(1, 'Barba', '16.00'),
(9, 'Corte', '20.00'),
(10, 'Corte e Barba', '30.00'),
(11, 'Desenho', '5.00'),
(13, 'Sobrancelha', '12.75'),
(23, 'Pezinho', '10.54');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `sexo` int(1) NOT NULL,
  `data_nascimento` date NOT NULL,
  `telefone` varchar(12) NOT NULL,
  `email` varchar(100) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nivel_acesso` int(1) NOT NULL,
  `token_senha` varchar(256) DEFAULT NULL,
  `token_senha_solicitacao` datetime DEFAULT NULL,
  `token_senha_expiracao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `sexo`, `data_nascimento`, `telefone`, `email`, `cpf`, `senha`, `nivel_acesso`, `token_senha`, `token_senha_solicitacao`, `token_senha_expiracao`) VALUES
(1, 'Lazaro Henrique ', 1, '2001-12-05', '44785523658', 'lazaro@gmail.com', '06567045000', '$2y$10$i5wAKogzu4s6AoOKqaSCreCbXgGJ/mB5mkBGEgJbXiCq9TUzh4XAW', 1, NULL, NULL, NULL),
(3, 'Juquinha Silva', 1, '2001-02-02', '44558781224', 'teste@gmail.com', '94325063048', '$2y$10$yhvMRJ9Vb.4KCKl9e8.1/uQSDiwJ5qS79i.TyJt8NvC8TFmLtn6E2', 2, NULL, NULL, NULL),
(4, 'Josévaldo ', 1, '2001-02-02', '44999999999', 'josevaldo@gmail.com', '21348727055', '$2y$10$7anF4I/7cInAN.tyKHILqOvVEyQajnqiYhwpRLAQnXPxHCSZSDP0W', 3, NULL, NULL, NULL),
(5, 'Paulo Silva', 1, '2001-02-14', '44558781224', 'paulo@gmail.com', '48314704067', '$2y$10$yhvMRJ9Vb.4KCKl9e8.1/uQSDiwJ5qS79i.TyJt8NvC8TFmLtn6E2', 2, NULL, NULL, NULL),
(6, 'Teste Password', 1, '2002-12-05', '44558781224', 'pass@gmail.com', '90698735080', '$2y$10$KXYg8QyYS9Olhi45h0HOw.17/hM2.Ri5hWTme1706NQpaSClg.0LC', 2, NULL, NULL, NULL),
(8, 'Ednaldo Pereira', 1, '2001-02-01', '44999999999', 'ednaldo@gmail.com', '06008186087', '$2y$10$Max9J17np5lnaOiBlbUwIOjvYllGx2bPFeBdneDvL7sukkpi7Qr8u', 2, NULL, NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `agendamento`
--
ALTER TABLE `agendamento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_servico` (`id_servico`);

--
-- Índices para tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `servico`
--
ALTER TABLE `servico`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agendamento`
--
ALTER TABLE `agendamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT de tabela `servico`
--
ALTER TABLE `servico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `agendamento`
--
ALTER TABLE `agendamento`
  ADD CONSTRAINT `agendamento_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id`),
  ADD CONSTRAINT `agendamento_ibfk_2` FOREIGN KEY (`id_servico`) REFERENCES `servico` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
