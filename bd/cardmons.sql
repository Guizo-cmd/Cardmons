-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29/06/2026 às 04:41
-- Versão do servidor: 8.0.35
-- Versão do PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `cardmons`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `carta_pokemon`
--

CREATE TABLE `carta_pokemon` (
  `id_carta` int NOT NULL,
  `nome` varchar(100) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `raridade` varchar(50) NOT NULL,
  `edicao` varchar(50) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `estoque` int NOT NULL DEFAULT '0',
  `imagem_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `carta_pokemon`
--

INSERT INTO `carta_pokemon` (`id_carta`, `nome`, `tipo`, `raridade`, `edicao`, `preco`, `estoque`, `imagem_url`) VALUES
(1, 'Charizard VMAX', 'Fogo', 'Ultra Rara', 'Sword &amp; Shield', 299.90, 3, 'assets/img/charizard.png'),
(2, 'Pikachu V', 'Elétrico', 'Rara', 'Vivid Voltage', 79.90, 10, 'assets/img/pikachu.png'),
(3, 'Mewtwo GX', 'Psíquico', 'Hiper Rara', 'Hidden Fates', 349.90, 2, 'assets/img/mewtwo.png'),
(4, 'Blastoise EX', 'Água', 'Rara', 'Evolutions', 129.90, 4, 'assets/img/blastoise.png'),
(5, 'Rayquaza VSTAR', 'Dragão', 'Ultra Rara', 'Brilliant Stars', 189.90, 3, 'assets/img/rayquaza.png'),
(6, 'Gengar V', 'Sombrio', 'Rara', 'Fusion Strike', 89.90, 7, 'assets/img/gengar.png'),
(7, 'Squirtle', 'Água', 'Comum', 'Kanto', 150.00, 0, 'assets/img/squirtle.png');

-- --------------------------------------------------------

--
-- Estrutura para tabela `item_pedido`
--

CREATE TABLE `item_pedido` (
  `id_item` int NOT NULL,
  `quantidade` int NOT NULL,
  `preco_unit` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `id_pedido` int NOT NULL,
  `id_carta` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `item_pedido`
--

INSERT INTO `item_pedido` (`id_item`, `quantidade`, `preco_unit`, `subtotal`, `id_pedido`, `id_carta`) VALUES
(1, 1, 299.90, 299.90, 1, 1),
(2, 1, 79.90, 79.90, 1, 2),
(3, 1, 129.90, 129.90, 2, 4),
(4, 1, 189.90, 189.90, 3, 5),
(5, 1, 150.00, 150.00, 4, 7);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido`
--

CREATE TABLE `pedido` (
  `id_pedido` int NOT NULL,
  `data_pedido` datetime NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pendente',
  `valor_total` decimal(10,2) NOT NULL,
  `id_usuario` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `pedido`
--

INSERT INTO `pedido` (`id_pedido`, `data_pedido`, `status`, `valor_total`, `id_usuario`) VALUES
(1, '2026-06-29 00:59:00', 'pendente', 379.80, 2),
(2, '2026-06-28 23:12:30', 'pendente', 129.90, 2),
(3, '2026-06-28 23:12:43', 'pendente', 189.90, 2),
(4, '2026-06-28 23:13:11', 'pendente', 150.00, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(100) NOT NULL,
  `tipo` varchar(20) NOT NULL DEFAULT 'cliente',
  `data_cadastro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nome`, `email`, `senha`, `tipo`, `data_cadastro`) VALUES
(1, 'Administrador', 'admin@cardmons.com', '202cb962ac59075b964b07152d234b70', 'administrador', '2026-06-29 00:59:00'),
(2, 'Ash Ketchum', 'ash@cardmons.com', '202cb962ac59075b964b07152d234b70', 'cliente', '2026-06-29 00:59:00'),
(3, 'Misty Waterflower', 'misty@cardmons.com', '202cb962ac59075b964b07152d234b70', 'cliente', '2026-06-29 00:59:00');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `carta_pokemon`
--
ALTER TABLE `carta_pokemon`
  ADD PRIMARY KEY (`id_carta`);

--
-- Índices de tabela `item_pedido`
--
ALTER TABLE `item_pedido`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `fk_item_pedido` (`id_pedido`),
  ADD KEY `fk_item_carta` (`id_carta`);

--
-- Índices de tabela `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `fk_pedido_usuario` (`id_usuario`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `uk_email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `carta_pokemon`
--
ALTER TABLE `carta_pokemon`
  MODIFY `id_carta` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `item_pedido`
--
ALTER TABLE `item_pedido`
  MODIFY `id_item` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id_pedido` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `item_pedido`
--
ALTER TABLE `item_pedido`
  ADD CONSTRAINT `fk_item_carta` FOREIGN KEY (`id_carta`) REFERENCES `carta_pokemon` (`id_carta`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_item_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `fk_pedido_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
