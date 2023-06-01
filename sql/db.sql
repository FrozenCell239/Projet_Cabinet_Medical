CREATE DATABASE IF NOT EXISTS `cabinet` DEFAULT CHARACTER SET UTF8MB4 COLLATE utf8mb4_unicode_ci;
USE `cabinet`;

CREATE TABLE `patients`(
    `id_patient` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `prenom_patient` VARCHAR(42) NOT NULL,
    `nom_patient` VARCHAR(42) NOT NULL,
    `numero_patient` VARCHAR(14) NOT NULL,
    `numero_securite_sociale` VARCHAR(21) NOT NULL,
    `adresse_patient` VARCHAR(42) NOT NULL,
    `ville_patient` VARCHAR(42) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

CREATE TABLE `personnel`(
  `id_personnel` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `prenom_personnel` varchar(42) NOT NULL,
  `nom_personnel` varchar(42) NOT NULL,
  `profession` varchar(42) NOT NULL,
  `mail` varchar(42) NOT NULL,
  `niveau_privilege` tinyint(1) NOT NULL,
  `numero_badge` varchar(42) DEFAULT NULL,
  `code_porte` varchar(42) DEFAULT NULL,
  `identifiant` varchar(42) DEFAULT NULL,
  `mot_de_passe` varchar(42) DEFAULT NULL,
  `date_mdp` DATE DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

CREATE TABLE `reservations`(
    `id_reservation` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `id_personnel` INT(11) NOT NULL,
    `id_salle` INT(11) NOT NULL,
    `id_patient` INT(11) NOT NULL,
    `besoin` VARCHAR(42) NOT NULL,
    `date_heure` DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

CREATE TABLE `salles`(
    `id_salle` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `nom_salle` VARCHAR(42) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

ALTER TABLE `reservations` ADD FOREIGN KEY (`id_patient`) REFERENCES `patients`(`id_patient`);
ALTER TABLE `reservations` ADD FOREIGN KEY (`id_salle`) REFERENCES `salles`(`id_salle`);
ALTER TABLE `reservations` ADD FOREIGN KEY (`id_personnel`) REFERENCES `personnel`(`id_personnel`);