-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 22 mars 2021 à 14:59
-- Version du serveur :  10.4.17-MariaDB
-- Version de PHP : 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `capou`
--

-- --------------------------------------------------------

--
-- Structure de la table `centrale`
--

CREATE TABLE `centrale` (
  `id` int(11) NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connected` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20210322135718', '2021-03-22 14:57:32', 1437);

-- --------------------------------------------------------

--
-- Structure de la table `donnees_piquet`
--

CREATE TABLE `donnees_piquet` (
  `id` int(11) NOT NULL,
  `id_piquet_id` int(11) NOT NULL,
  `horodatage` datetime NOT NULL,
  `humidite` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `temperature` double NOT NULL,
  `gps` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `donnees_station`
--

CREATE TABLE `donnees_station` (
  `id` int(11) NOT NULL,
  `id_station_id` int(11) NOT NULL,
  `pression` double NOT NULL,
  `gps` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `horodatage` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `donnees_vanne`
--

CREATE TABLE `donnees_vanne` (
  `id` int(11) NOT NULL,
  `id_vanne_id` int(11) NOT NULL,
  `debit` double NOT NULL,
  `gps` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `horodatage` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `electro_vanne`
--

CREATE TABLE `electro_vanne` (
  `id` int(11) NOT NULL,
  `etat` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `groupe`
--

CREATE TABLE `groupe` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `groupe`
--

INSERT INTO `groupe` (`id`) VALUES
(1);

-- --------------------------------------------------------

--
-- Structure de la table `groupe_electro_vanne`
--

CREATE TABLE `groupe_electro_vanne` (
  `groupe_id` int(11) NOT NULL,
  `electro_vanne_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `groupe_piquet`
--

CREATE TABLE `groupe_piquet` (
  `groupe_id` int(11) NOT NULL,
  `piquet_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `groupe_station`
--

CREATE TABLE `groupe_station` (
  `groupe_id` int(11) NOT NULL,
  `station_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `operateur`
--

CREATE TABLE `operateur` (
  `id` int(11) NOT NULL,
  `id_groupe_id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `piquet`
--

CREATE TABLE `piquet` (
  `id` int(11) NOT NULL,
  `etat` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `station`
--

CREATE TABLE `station` (
  `id` int(11) NOT NULL,
  `etat` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `centrale`
--
ALTER TABLE `centrale`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `donnees_piquet`
--
ALTER TABLE `donnees_piquet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_29AB2F3C644444F5` (`id_piquet_id`);

--
-- Index pour la table `donnees_station`
--
ALTER TABLE `donnees_station`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A19EBDBB843732E2` (`id_station_id`);

--
-- Index pour la table `donnees_vanne`
--
ALTER TABLE `donnees_vanne`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_893426B3F30350AF` (`id_vanne_id`);

--
-- Index pour la table `electro_vanne`
--
ALTER TABLE `electro_vanne`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `groupe`
--
ALTER TABLE `groupe`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `groupe_electro_vanne`
--
ALTER TABLE `groupe_electro_vanne`
  ADD PRIMARY KEY (`groupe_id`,`electro_vanne_id`),
  ADD KEY `IDX_42DEC137A45358C` (`groupe_id`),
  ADD KEY `IDX_42DEC13D9D043A7` (`electro_vanne_id`);

--
-- Index pour la table `groupe_piquet`
--
ALTER TABLE `groupe_piquet`
  ADD PRIMARY KEY (`groupe_id`,`piquet_id`),
  ADD KEY `IDX_8BA702E17A45358C` (`groupe_id`),
  ADD KEY `IDX_8BA702E1E471F8D2` (`piquet_id`);

--
-- Index pour la table `groupe_station`
--
ALTER TABLE `groupe_station`
  ADD PRIMARY KEY (`groupe_id`,`station_id`),
  ADD KEY `IDX_595E1FFF7A45358C` (`groupe_id`),
  ADD KEY `IDX_595E1FFF21BDB235` (`station_id`);

--
-- Index pour la table `operateur`
--
ALTER TABLE `operateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_B4B7F99DE7927C74` (`email`),
  ADD KEY `IDX_B4B7F99DFA7089AB` (`id_groupe_id`);

--
-- Index pour la table `piquet`
--
ALTER TABLE `piquet`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `station`
--
ALTER TABLE `station`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `donnees_piquet`
--
ALTER TABLE `donnees_piquet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `donnees_station`
--
ALTER TABLE `donnees_station`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `donnees_vanne`
--
ALTER TABLE `donnees_vanne`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `groupe`
--
ALTER TABLE `groupe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `operateur`
--
ALTER TABLE `operateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `donnees_piquet`
--
ALTER TABLE `donnees_piquet`
  ADD CONSTRAINT `FK_29AB2F3C644444F5` FOREIGN KEY (`id_piquet_id`) REFERENCES `piquet` (`id`);

--
-- Contraintes pour la table `donnees_station`
--
ALTER TABLE `donnees_station`
  ADD CONSTRAINT `FK_A19EBDBB843732E2` FOREIGN KEY (`id_station_id`) REFERENCES `station` (`id`);

--
-- Contraintes pour la table `donnees_vanne`
--
ALTER TABLE `donnees_vanne`
  ADD CONSTRAINT `FK_893426B3F30350AF` FOREIGN KEY (`id_vanne_id`) REFERENCES `electro_vanne` (`id`);

--
-- Contraintes pour la table `groupe_electro_vanne`
--
ALTER TABLE `groupe_electro_vanne`
  ADD CONSTRAINT `FK_42DEC137A45358C` FOREIGN KEY (`groupe_id`) REFERENCES `groupe` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_42DEC13D9D043A7` FOREIGN KEY (`electro_vanne_id`) REFERENCES `electro_vanne` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `groupe_piquet`
--
ALTER TABLE `groupe_piquet`
  ADD CONSTRAINT `FK_8BA702E17A45358C` FOREIGN KEY (`groupe_id`) REFERENCES `groupe` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_8BA702E1E471F8D2` FOREIGN KEY (`piquet_id`) REFERENCES `piquet` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `groupe_station`
--
ALTER TABLE `groupe_station`
  ADD CONSTRAINT `FK_595E1FFF21BDB235` FOREIGN KEY (`station_id`) REFERENCES `station` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_595E1FFF7A45358C` FOREIGN KEY (`groupe_id`) REFERENCES `groupe` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `operateur`
--
ALTER TABLE `operateur`
  ADD CONSTRAINT `FK_B4B7F99DFA7089AB` FOREIGN KEY (`id_groupe_id`) REFERENCES `groupe` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
