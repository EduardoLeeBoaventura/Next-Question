DROP DATABASE IF EXISTS `questionario`;
CREATE DATABASE `questionario`;

USE `questionario`;

CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `ref` varchar(25) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(50) DEFAULT NULL UNIQUE,
  `cpf` varchar(50) DEFAULT NULL UNIQUE,
  `geral` tinyint(1) DEFAULT '0' COMMENT 'Quando verdadeiro, indica que o usuário tem acesso a todos os setores/filiais/[...]',
  `desenvolvedor` tinyint(1) DEFAULT '0' COMMENT 'Quando verdadeiro, indica que o usuário é desenvolvedor',
  `situacao` enum('ATIVO','INATIVO','PENDENTE') DEFAULT 'PENDENTE',
  `visibilidade` tinyint(1) DEFAULT '1',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_cadastro` int DEFAULT NULL,
  `data_edicao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usuario_edicao` int DEFAULT NULL
);
CREATE TABLE `usuarios_privilegios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ref` varchar(25) NOT NULL,
  `tipo` enum('BLOQUEAR', 'AUTORIZAR') DEFAULT 'AUTORIZAR' NOT NULL,
  `pagina` varchar(255) NOT NULL,
  `permissoes` varchar(20) DEFAULT 'R' NOT NULL COMMENT 'C = Create (criar), R = Read (ver / listar), U = Update (editar), D = Delete (exclusão - visibilidade para 0), E = Emit (emissão de documentos). O `+` qualifica para ações administrativas, seja de leitura, escrita ou exclusão (Ex.: R+, E+, D+). Os valores devem ser separados por `|`',
  `id_usuario` int NOT NULL,
  `situacao` enum('ATIVO','INATIVO') DEFAULT 'ATIVO',
  `visibilidade` tinyint(1) DEFAULT '1',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_cadastro` int DEFAULT NULL,
  `data_edicao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usuario_edicao` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  FOREIGN KEY (`usuario_cadastro`) REFERENCES `usuarios` (`id`),
  FOREIGN KEY (`usuario_edicao`) REFERENCES `usuarios` (`id`)
);






CREATE TABLE `niveis_acessos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ref` varchar(25) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `situacao` enum('ATIVO','INATIVO') DEFAULT 'ATIVO',
  `visibilidade` tinyint(1) DEFAULT '1',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_cadastro` int DEFAULT NULL,
  `data_edicao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usuario_edicao` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`usuario_cadastro`) REFERENCES `usuarios` (`id`),
  FOREIGN KEY (`usuario_edicao`) REFERENCES `usuarios` (`id`)
);
CREATE TABLE `niveis_privilegios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ref` varchar(25) NOT NULL,
  `tipo` enum('BLOQUEAR', 'AUTORIZAR') DEFAULT 'AUTORIZAR' NOT NULL,
  `pagina` varchar(255) NOT NULL,
  `permissoes` varchar(20) DEFAULT 'R' NOT NULL COMMENT 'C = Create (criar), R = Read (ver / listar), U = Update (editar), D = Delete (exclusão - visibilidade para 0), E = Emit (emissão de documentos). O `+` qualifica para ações administrativas, seja de leitura, escrita ou exclusão (Ex.: R+, E+, D+). Os valores devem ser separados por `|`',
  `id_nivel` int NOT NULL,
  `situacao` enum('ATIVO','INATIVO') DEFAULT 'ATIVO',
  `visibilidade` tinyint(1) DEFAULT '1',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_cadastro` int DEFAULT NULL,
  `data_edicao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usuario_edicao` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_nivel`) REFERENCES `niveis_acessos` (`id`),
  FOREIGN KEY (`usuario_cadastro`) REFERENCES `usuarios` (`id`),
  FOREIGN KEY (`usuario_edicao`) REFERENCES `usuarios` (`id`)
);
CREATE TABLE `usuarios_conn_niveis` (
  `id_usuario` int NOT NULL,
  `id_nivel` int NOT NULL,
  FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  FOREIGN KEY (`id_nivel`) REFERENCES `niveis_acessos` (`id`)
);






CREATE TABLE `categorias` (
	`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `ref` varchar(25) NOT NULL,
    `nome` VARCHAR(50) NOT NULL UNIQUE,
    `descricao` TEXT,
    `status` ENUM('ativo','inativo') DEFAULT 'ativo',
    `visibilidade` tinyint(1) DEFAULT '1',
    `usuario_cadastro` int DEFAULT NULL,
    `usuario_edicao` int DEFAULT NULL,
    `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `data_edicao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  FOREIGN KEY (`usuario_cadastro`) REFERENCES `usuarios`(`id`),
	  FOREIGN KEY (`usuario_edicao`) REFERENCES `usuarios`(`id`)
);

CREATE TABLE `questionario` (
	  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `ref` varchar(25) NOT NULL,
    `nome` VARCHAR(100) NOT NULL,
    `status` ENUM('ativo','inativo') DEFAULT 'ativo',
    `descricao` TEXT,
    `quantidade_questoes` int,
    `visibilidade` tinyint(1) DEFAULT '1',
    `usuario_cadastro` int DEFAULT NULL,
    `usuario_edicao` int DEFAULT NULL,
    `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `data_edicao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  FOREIGN KEY (`usuario_cadastro`) REFERENCES `usuarios`(`id`),
	  FOREIGN KEY (`usuario_edicao`) REFERENCES `usuarios`(`id`)
);
CREATE TABLE `perguntas` (
	`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `ref` varchar(25) NOT NULL,
    `pergunta` TEXT NOT NULL,
    `opcoes` TEXT COMMENT 'As opções devem estar padronizadas',
    `gabarito` VARCHAR(1),
    `visibilidade` tinyint(1) DEFAULT '1',
    `usuario_cadastro` int DEFAULT NULL,
    `usuario_edicao` int DEFAULT NULL,
    `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `data_edicao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  FOREIGN KEY (`usuario_cadastro`) REFERENCES `usuarios`(`id`),
	  FOREIGN KEY (`usuario_edicao`) REFERENCES `usuarios`(`id`)
);
CREATE TABLE `categorias_conn_perguntas` (
	`id_categoria` INT NOT NULL,
    `id_pergunta` INT NOT NULL,
    FOREIGN KEY (`id_categoria`) REFERENCES `categorias`(`id`),
    FOREIGN KEY (`id_pergunta`) REFERENCES `perguntas`(`id`)
);

CREATE TABLE `vinculo_questionario` (
	  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  `id_categoria` INT NOT NULL,
    `id_questionario` INT NOT NULL,
    FOREIGN KEY (`id_categoria`) REFERENCES `categorias`(`id`),
    FOREIGN KEY (`id_questionario`) REFERENCES `questionario`(`id`)
);

CREATE TABLE `questionario_conn_perguntas` (
	`id_vinculo` INT NOT NULL,
	`id_pergunta` INT NOT NULL,
  FOREIGN KEY (`id_vinculo`) REFERENCES `vinculo_questionario`(`id`)
  FOREIGN KEY (`id_pergunta`) REFERENCES `perguntas`(`id`)
);



CREATE TABLE `logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ref` varchar(25) NOT NULL,
  `erro` text NOT NULL,
  `auxiliar` text NOT NULL,
  `data_cadastro` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);


INSERT INTO `usuarios` (ref, nome, email, senha, telefone, cpf, geral, desenvolvedor, situacao) VALUES ('YA8EJ-TSYB-XESEG-BN76NQT4','Jonta Sancar','email@email.com', '$2y$10$pqOsZHlxPFtFA/xLbdYRee7eje8etw7aTO958ff.JRcjfOvBXjpk6','(00) 0 0000-0000','000.000.000-00',1,1,'ATIVO');

# email : email@email.com
# senha : senha