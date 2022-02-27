SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `proxmox` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `proxmox`;
CREATE TABLE User_(
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `login` VARCHAR(50),
   `password` VARCHAR(255),
   `role` VARCHAR(50),
   PRIMARY KEY(`id`)
);

CREATE TABLE Groupe(
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` VARCHAR(50),
   PRIMARY KEY(`id`)
);

CREATE TABLE Serveur(
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `ipAddress` VARCHAR(15),
   `dnsName` VARCHAR(255),
   `login` VARCHAR(50),
   `password` VARCHAR(255),
   PRIMARY KEY(`id`)
);

CREATE TABLE Service(
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `service` VARCHAR(150),
   PRIMARY KEY(`id`)
);

CREATE TABLE DNS(
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `ipAddress` VARCHAR(15),
   `port` INT,
   `dnsName` VARCHAR(255),
   `idServer` INT NOT NULL,
   PRIMARY KEY(`id`),
   FOREIGN KEY(`idServer`) REFERENCES Serveur(`id`)
);

CREATE TABLE Route(
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `portOrigin` INT,
   `portDest` INT,
   `hostDest` VARCHAR(15),
   `order_` INT,
   `idServer` INT NOT NULL,
   PRIMARY KEY(`id`),
   FOREIGN KEY(`idServer`) REFERENCES Serveur(`id`)
);

CREATE TABLE VM(
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `number` INT,
   `name` VARCHAR(50),
   `ip` VARCHAR(15),
   `sshPort` INT,
   `os` VARCHAR(255),
   `idUser` INT,
   `idServeur` INT NOT NULL,
   `idGroupe` INT,
   PRIMARY KEY(`id`),
   FOREIGN KEY(`idUser`) REFERENCES User_(`id`),
   FOREIGN KEY(`idServeur`) REFERENCES Serveur(`id`),
   FOREIGN KEY(`idGroupe`) REFERENCES Groupe(`id`)
);

CREATE TABLE userGroups(
   `idUser` INT,
   `idGroupe` INT,
   PRIMARY KEY(`idUser`, `idGroupe`),
   FOREIGN KEY(`idUser`) REFERENCES User_(`id`),
   FOREIGN KEY(`idGroupe`) REFERENCES Groupe(`id`)
);

CREATE TABLE vmServices(
   `idVm` INT,
   `idService` INT,
   `port` INT,
   PRIMARY KEY(`idVm`, `idService`),
   FOREIGN KEY(`idVm`) REFERENCES VM(`id`),
   FOREIGN KEY(`idService`) REFERENCES Service(`id`)
);

CREATE TABLE userServers(
   `id` INT,
   `id_1` INT,
   PRIMARY KEY(`id`, `id_1`),
   FOREIGN KEY(`id`) REFERENCES User_(`id`),
   FOREIGN KEY(`id_1`) REFERENCES Serveur(`id`)
);
