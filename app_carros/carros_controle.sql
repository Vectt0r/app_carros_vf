-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 06-Jan-2024 às 14:59
-- Versão do servidor: 10.4.25-MariaDB
-- versão do PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `app_carros`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `carros_controle`
--

CREATE TABLE `carros_controle` (
  `id` int(11) NOT NULL,
  `placa` text NOT NULL,
  `data` date NOT NULL,
  `mes` int(20) NOT NULL,
  `km_inicial` int(20) NOT NULL,
  `hora_saida` time NOT NULL,
  `km_final` int(20) NOT NULL,
  `hora_chegada` time NOT NULL,
  `servico` text NOT NULL,
  `localidade` text DEFAULT NULL,
  `regiao` text NOT NULL,
  `funcionario` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `carros_controle`
--
ALTER TABLE `carros_controle`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `carros_controle`
--
ALTER TABLE `carros_controle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
