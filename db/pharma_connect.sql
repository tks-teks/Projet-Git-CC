-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 14 nov. 2025 à 15:38
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `pharma_connect`
--

-- --------------------------------------------------------

--
-- Structure de la table `medicaments`
--

CREATE TABLE `medicaments` (
  `id_medoc` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `medicaments`
--

INSERT INTO `medicaments` (`id_medoc`, `nom`, `description`) VALUES
(1, 'Mebendazole', 'Pour le ventre'),
(2, 'Paracétamol', 'Mal de tête');

-- --------------------------------------------------------

--
-- Structure de la table `pharmacies`
--

CREATE TABLE `pharmacies` (
  `id_pharma` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `adresse` varchar(200) DEFAULT NULL,
  `latitude` decimal(10,6) DEFAULT NULL,
  `longitude` decimal(10,6) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `pharmacies`
--

INSERT INTO `pharmacies` (`id_pharma`, `nom`, `adresse`, `latitude`, `longitude`, `contact`, `email`, `password`) VALUES
(1, 'La grace', 'Akwa', 10.000000, 98.000000, '678453213', 'Lagrace@gmail.com', '$2y$10$mX6hFTtY68qtfE8OyqZZyOyDwWDc6QP4upCJWmc7vlcbXPZYIzNWC'),
(2, 'sourire', 'Palace', 56.986500, 345.986000, '654323456', 'sourire@gmail.com', '$2y$10$CsSoviQjT3AMUCk6yQHBkOxbPim5OjAYrSIp.zhgMsTPte50SA8PS');

-- --------------------------------------------------------

--
-- Structure de la table `stock_pharmacie`
--

CREATE TABLE `stock_pharmacie` (
  `id` int(11) NOT NULL,
  `id_pharmacie` int(11) DEFAULT NULL,
  `id_medicament` int(11) DEFAULT NULL,
  `quantite` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `stock_pharmacie`
--

INSERT INTO `stock_pharmacie` (`id`, `id_pharmacie`, `id_medicament`, `quantite`) VALUES
(1, 1, NULL, 45),
(2, 1, NULL, 23),
(4, 1, 1, 12),
(5, 2, 2, 5),
(6, 2, 1, 4);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `medicaments`
--
ALTER TABLE `medicaments`
  ADD PRIMARY KEY (`id_medoc`);

--
-- Index pour la table `pharmacies`
--
ALTER TABLE `pharmacies`
  ADD PRIMARY KEY (`id_pharma`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `stock_pharmacie`
--
ALTER TABLE `stock_pharmacie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pharmacie` (`id_pharmacie`),
  ADD KEY `id_medicament` (`id_medicament`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `medicaments`
--
ALTER TABLE `medicaments`
  MODIFY `id_medoc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `pharmacies`
--
ALTER TABLE `pharmacies`
  MODIFY `id_pharma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `stock_pharmacie`
--
ALTER TABLE `stock_pharmacie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `stock_pharmacie`
--
ALTER TABLE `stock_pharmacie`
  ADD CONSTRAINT `stock_pharmacie_ibfk_1` FOREIGN KEY (`id_pharmacie`) REFERENCES `pharmacies` (`id_pharma`),
  ADD CONSTRAINT `stock_pharmacie_ibfk_2` FOREIGN KEY (`id_medicament`) REFERENCES `medicaments` (`id_medoc`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
