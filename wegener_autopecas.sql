-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.32-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para wegener_autopecas
DROP DATABASE IF EXISTS `wegener_autopecas`;
CREATE DATABASE IF NOT EXISTS `wegener_autopecas` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `wegener_autopecas`;

-- Copiando estrutura para tabela wegener_autopecas.categoria
DROP TABLE IF EXISTS `categoria`;
CREATE TABLE IF NOT EXISTS `categoria` (
  `id_cat` int(11) NOT NULL AUTO_INCREMENT,
  `nome_categoria` varchar(255) NOT NULL,
  PRIMARY KEY (`id_cat`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela wegener_autopecas.categoria: ~12 rows (aproximadamente)
DELETE FROM `categoria`;
INSERT INTO `categoria` (`id_cat`, `nome_categoria`) VALUES
	(1, 'Amortecedor'),
	(2, 'Lubrificantes '),
	(3, 'Pivô'),
	(4, 'Terminal de direção'),
	(5, 'Flexível de freio'),
	(6, 'Cilindro Mestre de Freio'),
	(7, 'Cilindro de Roda '),
	(8, 'Servo de Freio'),
	(9, 'Kit de Embreagem'),
	(10, 'Disco de Freio'),
	(11, 'Pastilha de Freio');

-- Copiando estrutura para tabela wegener_autopecas.produto
DROP TABLE IF EXISTS `produto`;
CREATE TABLE IF NOT EXISTS `produto` (
  `id_prod` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL DEFAULT '',
  `descricao` text NOT NULL,
  `preco` float NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `imagem` blob NOT NULL,
  `ativo` char(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`id_prod`) USING BTREE,
  KEY `categoria_id` (`categoria_id`),
  CONSTRAINT `categoria_id` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id_cat`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela wegener_autopecas.produto: ~9 rows (aproximadamente)
DELETE FROM `produto`;
INSERT INTO `produto` (`id_prod`, `nome`, `descricao`, `preco`, `categoria_id`, `imagem`, `ativo`) VALUES
	(1, 'PD/1433', 'Pastilha de freio GM S-10 2.4/2.8 2012/...', 280, 11, _binary 0x75706c6f6164732f70617374696c68615f667265696f5f732d31302e706e67, 'A'),
	(2, 'N-398', 'Pivô de Suspensão Inferior GM Vectra 1997/2005', 130, 3, _binary 0x75706c6f6164732f7069766f5f676d5f7665637472612e706e67, 'A'),
	(3, 'SYL-1081', 'Pastilha de freio GM Vectra GLS 2.0/2.2 1996/2005 Omega 2.0/2.2 1992/1998\r\n', 100, 11, _binary 0x75706c6f6164732f70617374696c68615f667265696f5f676d5f7665637472612e706e67, 'A'),
	(4, 'P-338', 'Pastilha de freio Renault Megane/Symbol/Clio 1998/...', 120, 11, _binary 0x75706c6f6164732f70617374696c68615f667265696f5f72656e61756c745f6d6567616e652e706e67, 'A'),
	(5, 'C-3352', 'Cilindro de roda tras Fusca 1300 76/84 Fusca 1600 76/86 Modelo Varga', 150, 3, _binary 0x75706c6f6164732f43696c696e64726f5f726f64615f74726173656972615f66757363612e706e67, 'A'),
	(6, 'N-93081', 'Pivô de Suspensão Inferior do GM Cruze 2017/2021', 130, 3, _binary 0x75706c6f6164732f7069766f5f676d5f6372757a652e706e67, 'A'),
	(7, 'N-183', 'Terminal de direção Volkswagen Gol 95/2008 Saveiro/Parati 1998/2008\r\n', 80, 4, _binary 0x75706c6f6164732f7465726d696e616c5f676f6c2e706e67, 'A'),
	(8, 'N-128', 'Terminal de direção Volkswagen Fusca/Brasília/Kombi 1979/1996', 90, 4, _binary 0x75706c6f6164732f7465726d696e616c5f4b6f6d62692e706e67, 'A'),
	(9, 'N-127', 'TERMINAL DIRECAO FUSCA 1300L/1500/1600 1973/1996 BRASILIA LE', 90, 4, _binary 0x75706c6f6164732f7465726d696e616c5f66757363612e706e67, 'A');

-- Copiando estrutura para tabela wegener_autopecas.usuarios_wegener
DROP TABLE IF EXISTS `usuarios_wegener`;
CREATE TABLE IF NOT EXISTS `usuarios_wegener` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` set('Administrador','Cliente') NOT NULL,
  `nome` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `telefone` varchar(255) NOT NULL DEFAULT '',
  `senha` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_user`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela wegener_autopecas.usuarios_wegener: ~2 rows (aproximadamente)
DELETE FROM `usuarios_wegener`;
INSERT INTO `usuarios_wegener` (`id_user`, `tipo`, `nome`, `email`, `telefone`, `senha`) VALUES
	(1, 'Administrador', 'Felipe Hoffmann Wegener', 'fewegener@gmail.com', '55991495642', '$2y$10$Ki5fDa18LjH..hGEByC4me0woHAuRxWAxtdac/hCIyljydt6XppO6'),
	(2, 'Cliente', 'Yago Checela', 'yago.chacela03@gmail.com', '6426144793', '$2y$10$F4m.xOCG1puMbwwb40d02uxMgDofjIrd3uW9xiSXDRPEBjBIZx/U.');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
