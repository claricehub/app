-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 17-Jul-2025 às 11:56
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `skillxpress`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `admin` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `admin`
--

INSERT INTO `admin` (`id`, `nome`, `email`, `password`, `admin`) VALUES
(1, 'Administrador', 'admin@exemplo.com', 'wH6QwQwQwQwQwQwQwQwQwOQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQw', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `avaliacao`
--

CREATE TABLE `avaliacao` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `trabalhador_id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `estrelas` int(11) NOT NULL CHECK (`estrelas` between 1 and 5),
  `comentario` text DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `avaliacao`
--

INSERT INTO `avaliacao` (`id`, `cliente_id`, `trabalhador_id`, `pedido_id`, `estrelas`, `comentario`, `criado_em`) VALUES
(3, 36, 8, 10, 2, 'nnn', '2025-07-15 17:05:58'),
(7, 36, 5, 7, 1, 'n', '2025-07-16 16:42:19'),
(8, 37, 5, 22, 4, 'dd', '2025-07-16 21:10:19'),
(9, 37, 5, 23, 4, 'cc', '2025-07-16 21:15:01'),
(10, 37, 5, 24, 5, 'fff', '2025-07-16 21:19:09'),
(11, 37, 5, 25, 5, 'KK', '2025-07-16 21:27:52');

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `categoria` text NOT NULL,
  `descricao` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `categorias`
--

INSERT INTO `categorias` (`id`, `categoria`, `descricao`) VALUES
(1, 'Construção Civil', 'Executa obras e remodelações em habitações, desde fundações até acabamentos.'),
(2, 'Canalizador', 'Instala e repara canalizações de água e esgoto em casas.'),
(3, 'Eletricista', 'Instala e mantém redes elétricas domésticas com segurança e eficiência.'),
(4, 'Pintor', 'Pinta interiores e exteriores de casas, garantindo estética e durabilidade.'),
(5, 'Vidraceiro', 'Instala vidros em janelas, portas e varandas de habitações.'),
(6, 'Carpinteiro', 'Constrói e instala móveis, portas e estruturas de madeira em habitações.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `contribuinte` varchar(15) DEFAULT NULL,
  `morada` varchar(255) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `email`, `senha`, `foto_perfil`, `contribuinte`, `morada`, `telefone`) VALUES
(25, 'Claudiene', 'platio@gmail', '$2y$10$dVKsmPGIj0.YtvskIcPrtOBNV2BscD9PTLtSvuDSFtTtSzWbhRmF2', 'cliente_25_686aa2aa4063a.jpg', NULL, NULL, NULL),
(26, 'clarice veiga da silva', 'veigaclarice04@gmail.com', '$2y$10$MSasFSIRIiSsrwaXPq6jGui19/nDvxRX2XmGU0MfS3b0S43sAI3h.', 'cliente_26_686aa764dcc8a.jpg', NULL, NULL, NULL),
(27, 'claudiA', 'agua@gmzil.com', '$2y$10$NdEMo7GzxkWjHAVQhr7SquUSsnMWRdloy/AmjAtsbcF4R7ZI/oP9e', 'cliente_27_686ac54d98b4a.jpg', NULL, NULL, NULL),
(29, 'YTGUYHUIO', 'adsdaGH7G87s@sd14', '$2y$10$8K0rC.n7SsdM8qTsLHrDNu9rKrT7vHCsCrEWbzEKvaUDBoLnCLWiS', 'cliente_29_686e435fd23ff.jpg', NULL, NULL, NULL),
(35, 'dff', 'platio@gmail', '$2y$10$T9HB0yIcydUGCN0B5hbH0.MQdXJupf2QdqgzHa6aJjlg8uzyIl8He', NULL, NULL, NULL, NULL),
(36, 'cc', 'platio@gmail', '$2y$10$3IL.9lOpLJygGeZmPyBP3uC8EYVNp4iCcJtszqmrGhM7G2EQfeZnO', NULL, NULL, NULL, NULL),
(37, 'aaa', 'cla@gmail.com', '$2y$10$6t3tey8Go0GEViBg8BQ7Z.R5lsSXp5QulEF01y01nlHdjofThsXHm', 'cliente_37_6875857455e78.jpg', NULL, NULL, NULL),
(38, 'vv', 'adsdas@sd14', '$2y$10$j3mLzkKzPOoFNwEYsm2jx..LEcCcbcjNM/sgkiy3qFBw1P2WsgNzO', NULL, NULL, NULL, NULL),
(39, 'alicia', 'alicia@gmail.com', '$2y$10$3NxaGDfd2yZgbTfj5rRPLe6Z57FRQlrW3GuUeQxuhbKzDH5.MJPhK', 'cliente_39_687587984a518.jpg', NULL, NULL, '1111111'),
(40, 'fernanda', 'fefe.pt78@gmail.com', '$2y$10$7mIWOJEvXdx9Hy7Uocnm8.Q8W3dPK5jDxwF8V15d/AcLJqmZKe8bC', NULL, NULL, NULL, NULL),
(41, 'aaa', 'adsdas@sd14', '$2y$10$RzTDyptzSPxnvZBPSSPAA.Qlk5tINCxU/Kqi/bXDmnX0PdCtHb0tG', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `contrato`
--

CREATE TABLE `contrato` (
  `id` int(11) NOT NULL,
  `id_c` int(11) NOT NULL,
  `id_ts` int(11) NOT NULL,
  `data` date NOT NULL,
  `avaliacao_c` varchar(3) NOT NULL,
  `avaliacao_s` varchar(3) NOT NULL,
  `estado` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `fotos_servico`
--

CREATE TABLE `fotos_servico` (
  `id` int(11) NOT NULL,
  `id_trabalhador` int(11) NOT NULL,
  `caminho_foto` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `data_envio` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_trabalhador` int(11) NOT NULL,
  `mensagem` text DEFAULT NULL,
  `data_pedido` datetime DEFAULT current_timestamp(),
  `status` enum('pendente','aceito','recusado','cancelado','feito') NOT NULL DEFAULT 'pendente',
  `marcado_por_trabalhador` tinyint(1) DEFAULT 0,
  `marcado_por_cliente` tinyint(1) DEFAULT 0,
  `data_marcado_trabalhador` datetime DEFAULT NULL,
  `data_marcado_cliente` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `id_cliente`, `id_trabalhador`, `mensagem`, `data_pedido`, `status`, `marcado_por_trabalhador`, `marcado_por_cliente`, `data_marcado_trabalhador`, `data_marcado_cliente`) VALUES
(2, 29, 1, 'quero', '2025-07-09 11:20:49', 'cancelado', 0, 0, NULL, NULL),
(5, 26, 4, 'aaaaa', '2025-07-14 23:29:25', 'recusado', 0, 0, NULL, NULL),
(6, 37, 4, 'aaa', '2025-07-14 23:32:53', 'aceito', 0, 0, NULL, NULL),
(7, 39, 4, 'ddddddddddddddddddddddddddddddddd', '2025-07-14 23:41:08', 'aceito', 0, 0, NULL, NULL),
(9, 40, 5, 'preciso de serviço de pintura', '2025-07-15 00:31:12', 'feito', 0, 0, NULL, NULL),
(10, 36, 5, 'cccccccc', '2025-07-15 00:34:23', 'feito', 0, 0, NULL, NULL),
(11, 41, 4, 'ff', '2025-07-16 13:10:28', 'aceito', 0, 0, NULL, NULL),
(12, 35, 5, 'mm', '2025-07-16 20:40:20', 'feito', 0, 0, NULL, NULL),
(13, 25, 5, 'mmmmmmm', '2025-07-16 20:44:37', 'feito', 0, 0, NULL, NULL),
(14, 26, 5, 'mm', '2025-07-16 20:49:23', 'feito', 0, 0, NULL, NULL),
(15, 26, 5, 'mmm', '2025-07-16 20:49:31', 'feito', 0, 0, NULL, NULL),
(16, 27, 5, 'nn', '2025-07-16 21:04:38', 'feito', 0, 0, NULL, NULL),
(17, 1, 2, 'Serviço de pintura concluído', '2025-07-16 21:06:52', 'pendente', 0, 0, NULL, NULL),
(18, 1, 2, 'Serviço de pintura concluído', '2025-07-16 21:06:52', 'aceito', 0, 0, NULL, NULL),
(19, 25, 5, 'bvb', '2025-07-16 21:08:25', 'feito', 0, 0, NULL, NULL),
(20, 25, 5, 'fff', '2025-07-16 21:12:10', 'feito', 0, 0, NULL, NULL),
(21, 39, 5, 'ddd', '2025-07-16 21:49:51', '', 0, 0, NULL, NULL),
(22, 37, 5, '3333', '2025-07-16 22:09:21', 'feito', 0, 0, NULL, NULL),
(23, 37, 5, 'dddd', '2025-07-16 22:13:52', 'feito', 0, 0, NULL, NULL),
(24, 37, 5, 'eeee', '2025-07-16 22:18:41', 'aceito', 1, 0, '2025-07-16 23:05:17', NULL),
(25, 37, 5, 'NNN', '2025-07-16 22:27:19', 'aceito', 1, 0, '2025-07-16 23:03:34', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `reset_senhas`
--

CREATE TABLE `reset_senhas` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expira` datetime NOT NULL,
  `usado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `servico_imagens`
--

CREATE TABLE `servico_imagens` (
  `id` int(11) NOT NULL,
  `id_trabalhador` int(11) NOT NULL,
  `nome_arquivo` varchar(255) NOT NULL,
  `data_upload` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `servico_imagens`
--

INSERT INTO `servico_imagens` (`id`, `id_trabalhador`, `nome_arquivo`, `data_upload`) VALUES
(1, 1, 'servico_1_686e42f019e0f.jpg', '2025-07-09 10:22:40'),
(2, 3, 'servico_3_686e46e750ac6.jpg', '2025-07-09 10:39:35'),
(3, 4, 'servico_4_686fe62c5d1fd.jpg', '2025-07-10 16:11:24'),
(4, 4, 'servico_4_686fe63b00e02.jpg', '2025-07-10 16:11:39'),
(5, 5, 'servico_5_687592beaa23f.jpg', '2025-07-14 23:29:02');

-- --------------------------------------------------------

--
-- Estrutura da tabela `trabalhadores`
--

CREATE TABLE `trabalhadores` (
  `id` int(255) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `profissao` int(11) DEFAULT NULL,
  `zona` int(11) DEFAULT NULL,
  `disponibilidade` int(11) NOT NULL DEFAULT 1,
  `admin` int(1) NOT NULL DEFAULT 0,
  `titulo` varchar(255) NOT NULL,
  `texto1` text NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `telefone` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `trabalhadores`
--

INSERT INTO `trabalhadores` (`id`, `nome`, `email`, `password`, `profissao`, `zona`, `disponibilidade`, `admin`, `titulo`, `texto1`, `foto_perfil`, `telefone`) VALUES
(5, 'claudio moises da silva', 'claudiomoisesdasilva7@gmail.com', '$2y$10$uxrobqgMYgfA/fuBGsYOIOj1mL8MFxeTlE2BakBOIxUunr1MgX3.S', NULL, 2, 1, 0, 'pintura', 'trabalho com pinturas interior e exterior', 'trabalhador_5_68781db2922f2.jpg', NULL),
(6, 'dpoos', 'ede@dcwd', '$2y$10$8G6SkilZ44CQIIQzsQGdquT2/tgKRMFIReZWFZXDf/laqrJEEHcPe', NULL, 13, 0, 0, '', '', NULL, NULL),
(8, 'dpoos', 'ede@dcwd', '$2y$10$vZ/O.oUj3CtLJyGsjBBFB.M0rAiS.wppS.Q0s1nkjUgEk1Jfjnn8S', NULL, 13, 1, 0, '', '', NULL, NULL),
(12, 'dpoos', 'platio@gmail', '$2y$10$j9Pgvgsxy/3c.4C.qndnx.7fnPnxfv7Ab8AejOxFdM5LY/koeAvuC', NULL, 2, 1, 1, '', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `trabalhador_profissao`
--

CREATE TABLE `trabalhador_profissao` (
  `trabalhador_id` int(11) NOT NULL,
  `profissao_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `trabalhador_profissao`
--

INSERT INTO `trabalhador_profissao` (`trabalhador_id`, `profissao_id`) VALUES
(1, 4),
(2, 2),
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(3, 5),
(3, 6),
(4, 1),
(4, 2),
(4, 3),
(4, 4),
(4, 5),
(4, 6),
(5, 1),
(6, 5),
(7, 5),
(8, 5),
(9, 5),
(10, 5),
(11, 3),
(12, 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `trab_serv`
--

CREATE TABLE `trab_serv` (
  `id_ts` int(11) NOT NULL,
  `id_t` int(11) NOT NULL,
  `id_s` int(11) NOT NULL,
  `valor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `zona`
--

CREATE TABLE `zona` (
  `id` int(11) NOT NULL,
  `zona` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `zona`
--

INSERT INTO `zona` (`id`, `zona`) VALUES
(11, 'Açores'),
(5, 'Aveiro'),
(17, 'Beja'),
(3, 'Braga'),
(19, 'Bragança'),
(15, 'Castelo Branco'),
(6, 'Coimbra'),
(16, 'Évora'),
(8, 'Faro'),
(18, 'Guarda'),
(7, 'Leiria'),
(1, 'Lisboa'),
(10, 'Madeira'),
(20, 'Portalegre'),
(2, 'Porto'),
(12, 'Santarém'),
(4, 'Setúbal'),
(13, 'Viana do Castelo'),
(14, 'Vila Real'),
(9, 'Viseu');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pedido_id` (`pedido_id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `trabalhador_id` (`trabalhador_id`);

--
-- Índices para tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `contrato`
--
ALTER TABLE `contrato`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ts` (`id_ts`),
  ADD KEY `id_c` (`id_c`);

--
-- Índices para tabela `fotos_servico`
--
ALTER TABLE `fotos_servico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_trabalhador` (`id_trabalhador`);

--
-- Índices para tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_trabalhador` (`id_trabalhador`);

--
-- Índices para tabela `reset_senhas`
--
ALTER TABLE `reset_senhas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Índices para tabela `servico_imagens`
--
ALTER TABLE `servico_imagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_trabalhador` (`id_trabalhador`);

--
-- Índices para tabela `trabalhadores`
--
ALTER TABLE `trabalhadores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profissao` (`profissao`),
  ADD KEY `fk_trabalhador_zona` (`zona`);

--
-- Índices para tabela `trabalhador_profissao`
--
ALTER TABLE `trabalhador_profissao`
  ADD PRIMARY KEY (`trabalhador_id`,`profissao_id`),
  ADD KEY `profissao_id` (`profissao_id`);

--
-- Índices para tabela `trab_serv`
--
ALTER TABLE `trab_serv`
  ADD PRIMARY KEY (`id_ts`),
  ADD KEY `id_t` (`id_t`);

--
-- Índices para tabela `zona`
--
ALTER TABLE `zona`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zona` (`zona`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de tabela `contrato`
--
ALTER TABLE `contrato`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fotos_servico`
--
ALTER TABLE `fotos_servico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `reset_senhas`
--
ALTER TABLE `reset_senhas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `servico_imagens`
--
ALTER TABLE `servico_imagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `trabalhadores`
--
ALTER TABLE `trabalhadores`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `trab_serv`
--
ALTER TABLE `trab_serv`
  MODIFY `id_ts` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `zona`
--
ALTER TABLE `zona`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  ADD CONSTRAINT `avaliacao_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `avaliacao_ibfk_2` FOREIGN KEY (`trabalhador_id`) REFERENCES `trabalhadores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `avaliacao_ibfk_3` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
