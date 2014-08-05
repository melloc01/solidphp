-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 23-Mar-2014 às 16:20
-- Versão do servidor: 5.6.12-log
-- versão do PHP: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `loopwork`
--
CREATE DATABASE IF NOT EXISTS `loopwork` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `loopwork`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ferramenta`
--

CREATE TABLE IF NOT EXISTS `ferramenta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(64) NOT NULL,
  `administrativa` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Extraindo dados da tabela `ferramenta`
--

INSERT INTO `ferramenta` (`id`, `nome`, `administrativa`) VALUES
(2, 'historico', 1),
(3, 'usuarios', 1),
(4, 'nivel', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `historico`
--

CREATE TABLE IF NOT EXISTS `historico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operacao` varchar(512) NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `titulo` varchar(512) NOT NULL,
  `sql_backup` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `menul`
--

CREATE TABLE IF NOT EXISTS `menul` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_menu` varchar(64) NOT NULL,
  `fkFerramenta` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ferramenta_menu` (`fkFerramenta`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Extraindo dados da tabela `menul`
--

INSERT INTO `menul` (`id`, `nome_menu`, `fkFerramenta`) VALUES
(2, 'historico', 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `nivel`
--

CREATE TABLE IF NOT EXISTS `nivel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `nivel`
--

INSERT INTO `nivel` (`id`, `nome`) VALUES
(1, 'administrador'),
(2, 'teste');

-- --------------------------------------------------------

--
-- Estrutura da tabela `permissao`
--

CREATE TABLE IF NOT EXISTS `permissao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fkFerramenta` int(11) NOT NULL,
  `fkNivel` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ferramenta_permissao` (`fkFerramenta`),
  KEY `fk_nivel_permissao` (`fkNivel`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Extraindo dados da tabela `permissao`
--

INSERT INTO `permissao` (`id`, `fkFerramenta`, `fkNivel`) VALUES
(2, 2, 1),
(3, 3, 1),
(4, 4, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(128) NOT NULL,
  `password` varchar(64) DEFAULT NULL,
  `fkNivel` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_nivel_usuario` (`fkNivel`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `password`, `fkNivel`) VALUES
(1, 'admin', 'admin', 1),
(2, 'teste@teste.com', 'teste', 2);

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `menul`
--
ALTER TABLE `menul`
  ADD CONSTRAINT `fk_ferramenta_menu` FOREIGN KEY (`fkFerramenta`) REFERENCES `ferramenta` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `permissao`
--
ALTER TABLE `permissao`
  ADD CONSTRAINT `fk_ferramenta_permissao` FOREIGN KEY (`fkFerramenta`) REFERENCES `ferramenta` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_nivel_permissao` FOREIGN KEY (`fkNivel`) REFERENCES `nivel` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_nivel_usuario` FOREIGN KEY (`fkNivel`) REFERENCES `nivel` (`id`) ON DELETE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
