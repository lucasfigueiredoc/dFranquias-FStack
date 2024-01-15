-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 11/01/2024 às 14:53
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `fazenda`
--

-- --------------------------------------------------------

--
-- Banco de dados: `fazenda`
--
CREATE DATABASE IF NOT EXISTS `fazenda` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `fazenda`;

-- --------------------

--
-- Estrutura para tabela `doctrine_migration_versions`
--



CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20240103094933', '2024-01-03 10:49:43', 62),
-- ('DoctrineMigrations\\Version20240104212448', '2024-01-04 22:24:59', 741),
('DoctrineMigrations\\Version20240109005516', '2024-01-09 02:12:04', 577);

-- --------------------------------------------------------

--
-- Estrutura para tabela `gado`
--

CREATE TABLE `gado` (
  `id` int(11) NOT NULL,
  `leite` double DEFAULT NULL,
  `racao` double NOT NULL,
  `peso` double NOT NULL,
  `situacao` int(11) NOT NULL,
  `nascimento` date NOT NULL,
  `codigo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `gado`
--

INSERT INTO `gado` (`id`, `leite`, `racao`, `peso`, `situacao`, `nascimento`, `codigo`) VALUES
(4, 34.62, 133, 1289, 1, '2020-12-27', 882),
(12, 165.83, 2, 841, 1, '2010-01-29', 949),
(15, 86.46, 5002, 804, 1, '2023-02-02', 442),
(29, 20.14, 5002, 454, 1, '2023-02-03', 856),
(31, 11.61, 503, 768, 1, '2023-03-04', 345),
(32, 80.7, 600, 347, 1, '2023-02-20', 65);

-- --------------------------------------------------------

--
-- Estrutura para tabela `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Índices de tabela `gado`
--
ALTER TABLE `gado`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `gado`
--
ALTER TABLE `gado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10108;

--
-- AUTO_INCREMENT de tabela `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
