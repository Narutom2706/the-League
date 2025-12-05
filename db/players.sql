-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Hôte : db.3wa.io
-- Généré le : jeu. 22 fév. 2024 à 08:27
-- Version du serveur :  5.7.33-0ubuntu0.18.04.1-log
-- Version de PHP : 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `maridoucet_bre01_league`
--

-- --------------------------------------------------------

--
-- Structure de la table `players`
--

CREATE TABLE `players` (
  `id` int(11) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `bio` varchar(1023) NOT NULL,
  `portrait` int(11) NOT NULL,
  `team` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `players`
--

INSERT INTO `players` (`id`, `nickname`, `bio`, `portrait`, `team`) VALUES
(1, 'Berkov', 'TBD', 6, 4),
(2, 'Britus', 'TBD', 7, 2),
(3, 'Dundy', 'TBD', 8, 2),
(4, 'Garrin', 'TBD', 9, 4),
(5, 'Gayj', 'TBD', 10, 5),
(6, 'Ladso', 'TBD', 11, 1),
(7, 'Mayan', 'TBD', 12, 5),
(8, 'Nerdex', 'TBD', 13, 2),
(9, 'RedWitch', 'TBD', 14, 1),
(10, 'Soltan', 'TBD', 15, 1),
(11, 'Speck', 'TBD', 16, 3),
(12, 'Stonk', 'TBD', 17, 4),
(13, 'Vicrane', 'TBD', 18, 3),
(14, 'Wofin', 'TBD', 20, 3),
(15, 'Vrixo', 'TBD', 19, 5);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`id`),
  ADD KEY `portrait` (`portrait`),
  ADD KEY `team` (`team`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `players`
--
ALTER TABLE `players`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `players`
--
ALTER TABLE `players`
  ADD CONSTRAINT `players_ibfk_1` FOREIGN KEY (`portrait`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `players_ibfk_2` FOREIGN KEY (`team`) REFERENCES `teams` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
