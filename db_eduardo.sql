DROP DATABASE IF EXISTS `questionnaire`;
CREATE DATABASE `questionnaire`;

USE `questionnaire`;

CREATE TABLE `type_user` (
	`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL UNIQUE,
    `status` ENUM('ativo','inativo') DEFAULT 'ativo',
	`regist_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `edit_date` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `user` (
	`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `login` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `status` ENUM('ativo','inativo') DEFAULT 'ativo',
    `id_type_user` INT, -- NOT NULL,
    `regist_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `edit_date` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
	-- FOREIGN KEY (`id_type_user`) REFERENCES `type_user`(`id`)
);

CREATE TABLE `questionnaire_topic` (
	`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `status` ENUM('ativo','inativo') DEFAULT 'ativo'
);

CREATE TABLE `questionnaire` (
	`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `id_user_author` INT, -- NOT NULL,
    `id_topic` INT NOT NULL,
    `status` ENUM('ativo','inativo') DEFAULT 'ativo',
    `description` TEXT,
    FOREIGN KEY (`id_topic`) REFERENCES `questionnaire_topic`(`id`),
	FOREIGN KEY (`id_user_author`) REFERENCES `user`(`id`)
);

CREATE TABLE `questions` (
	`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `quest` TEXT NOT NULL,
    `options` TEXT COMMENT 'As opções devem estar padronizadas',
    `coption` VARCHAR(1)
);

CREATE TABLE `conn_question` (
	`id_questionnaire` INT NOT NULL,
    `id_quest` INT NOT NULL,
    FOREIGN KEY (`id_questionnaire`) REFERENCES `questionnaire`(`id`),
    FOREIGN KEY (`id_quest`) REFERENCES `questions`(`id`)
);