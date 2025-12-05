-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Hôte : db.3wa.io
-- Généré le : jeu. 22 fév. 2024 à 08:28
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
-- Structure de la table `player_performance`
--

CREATE TABLE `player_performance` (
  `id` int(11) NOT NULL,
  `player` int(11) NOT NULL,
  `game` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `assists` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `player_performance`
--

INSERT INTO `player_performance` (`id`, `player`, `game`, `points`, `assists`) VALUES
(1, 6, 1, 13, 3),
(2, 9, 1, 11, 7),
(3, 10, 1, 12, 2),
(4, 2, 1, 5, 12),
(5, 3, 1, 7, 3),
(6, 8, 1, 9, 6),
(7, 11, 2, 11, 1),
(8, 13, 2, 8, 3),
(9, 14, 2, 10, 2),
(10, 1, 2, 11, 4),
(11, 4, 2, 13, 5),
(12, 12, 2, 8, 11),
(13, 5, 3, 5, 11),
(14, 7, 3, 8, 8),
(15, 15, 3, 12, 4),
(16, 6, 3, 6, 5),
(17, 9, 3, 7, 4),
(18, 10, 3, 5, 6);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `player_performance`
--
ALTER TABLE `player_performance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game` (`game`),
  ADD KEY `player` (`player`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `player_performance`
--
ALTER TABLE `player_performance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `player_performance`
--
ALTER TABLE `player_performance`
  ADD CONSTRAINT `player_performance_ibfk_1` FOREIGN KEY (`game`) REFERENCES `games` (`id`),
  ADD CONSTRAINT `player_performance_ibfk_2` FOREIGN KEY (`player`) REFERENCES `players` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
