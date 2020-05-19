-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 19, 2020 at 09:39 AM
-- Server version: 5.7.26
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `site`
--

-- --------------------------------------------------------

--
-- Table structure for table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int(3) NOT NULL,
  `id_membre` int(3) DEFAULT NULL,
  `montant` float NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  `etat` enum('en cours de traitement','envoyé','livré') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `commande`
--

INSERT INTO `commande` (`id_commande`, `id_membre`, `montant`, `date_enregistrement`, `etat`) VALUES
(18, 29, 201, '2020-04-29 12:35:32', 'en cours de traitement'),
(19, 29, 88, '2020-04-29 12:36:21', 'en cours de traitement');

-- --------------------------------------------------------

--
-- Table structure for table `details_commande`
--

CREATE TABLE `details_commande` (
  `id_details_commande` int(3) NOT NULL,
  `id_commande` int(3) DEFAULT NULL,
  `id_produit` int(3) DEFAULT NULL,
  `quantite` int(3) NOT NULL,
  `prix` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `details_commande`
--

INSERT INTO `details_commande` (`id_details_commande`, `id_commande`, `id_produit`, `quantite`, `prix`) VALUES
(1, 4, 19, 1, 25),
(2, 4, 18, 1, 25),
(3, 5, 12, 1, 258),
(4, 6, 12, 1, 258),
(5, 7, 16, 1, 1500),
(6, 7, 19, 3, 25),
(7, 8, 19, 1, 25),
(8, 10, 17, 1, 100),
(9, 11, 16, 1, 15),
(10, 12, 16, 2, 15),
(11, 13, 19, 2, 25),
(12, 13, 17, 5, 100),
(13, 14, 17, 2, 100),
(14, 14, 20, 2, 88),
(15, 14, 22, 4, 88),
(16, 15, 18, 1, 25),
(17, 15, 21, 1, 100),
(18, 16, 18, 1, 25),
(19, 17, 18, 1, 25),
(20, 17, 21, 3, 100),
(21, 18, 18, 1, 25),
(22, 18, 22, 2, 88),
(23, 19, 22, 1, 88);

-- --------------------------------------------------------

--
-- Table structure for table `membre`
--

CREATE TABLE `membre` (
  `id_membre` int(3) NOT NULL,
  `pseudo` varchar(20) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `civilite` enum('m','f') NOT NULL,
  `ville` varchar(20) NOT NULL,
  `code_postal` int(5) NOT NULL,
  `adresse` varchar(50) NOT NULL,
  `statut` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `membre`
--

INSERT INTO `membre` (`id_membre`, `pseudo`, `mdp`, `nom`, `prenom`, `email`, `civilite`, `ville`, `code_postal`, `adresse`, `statut`) VALUES
(25, 'test3', '$2y$10$DPSAB/If1NyDiyKoUVTvteMcVALziDWljm7Or3k94eCQ1TYK6BNFW', 'test3', 'test3', 'test@gmail.com', 'm', 'Paris', 75004, 'fvehvuehruighvrm', 1),
(26, 'test4', '$2y$10$i.cR.ZisWngH4V8VriCwRuZ/0paeJ.DZibiegNjUl9lJEjyLXD846', 'test4', 'test4', 'test@gmail.com', 'm', 'Paris', 75004, 'fvehvuehruighvrm', 0),
(27, 'test5', '$2y$10$Opp1RFqXOm7/2QRPKnHbDe3okCgk.0upiZFn77vQXUPCF6M.j7a2G', 'test5', 'test5', 'test@gmail.com', 'm', 'Paris', 75004, 'fvehvuehruighvrm', 0),
(29, 'yann', '$2y$10$1i9xNUmvHiR7UKWHv2MLCu/NN0NqqVb7KM0UTX.w0qqo.E/pirhFS', 'yy', 'yy', 'y@gmail.com', 'm', 'Paris', 75016, '1 rue du hacking', 1);

-- --------------------------------------------------------

--
-- Table structure for table `produit`
--

CREATE TABLE `produit` (
  `id_produit` int(3) NOT NULL,
  `reference` varchar(20) NOT NULL,
  `categorie` varchar(20) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `couleur` varchar(20) NOT NULL,
  `taille` varchar(5) NOT NULL,
  `public` enum('m','f','mixte') NOT NULL,
  `photo` varchar(250) NOT NULL,
  `prix` float NOT NULL,
  `stock` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `produit`
--

INSERT INTO `produit` (`id_produit`, `reference`, `categorie`, `titre`, `description`, `couleur`, `taille`, `public`, `photo`, `prix`, `stock`) VALUES
(18, '45446546', 'Pantalon', 'pantalon noir', 'pantalon femme noir', 'noir', 'L', 'f', 'photos/ref45446546_pantalon1.jpg', 25, 6),
(21, '55555', 'Pantalon', 'Pantalon blanc', 'pantalon blanc', 'blanc', 'S', 'mixte', 'photos/ref55555_pantalon2.jpg', 100, 0),
(22, '894341', 'robe', 'robe noir', 'robe noir soirée', 'noir', 'L', 'f', 'photos/ref894341_robe1.jpg', 88, 0),
(23, '894341', 'robe', 'robe noir', 'robe noir soirée', 'noir', 'L', 'f', 'photos/ref894341_robe1.jpg', 88, 9),
(24, '55555', 'Pantalon', 'Pantalon blanc', 'pantalon blanc', 'blanc', 'S', 'mixte', 'photos/ref55555_pantalon2.jpg', 100, 52),
(25, '55555', 'Pantalon', 'Pantalon blanc', 'pantalon blanc', 'blanc', 'S', 'mixte', 'photos/ref55555_pantalon2.jpg', 100, 6),
(26, '85455', 'Pull', 'Pull gris', 'geragzhgth', 'blanc', 'L', 'f', 'photos/ref85455_pull1.jpg', 258, 4),
(27, '85455', 'Pull', 'Pull gris', 'geragzhgth', 'blanc', 'L', 'f', 'photos/ref85455_pull1.jpg', 258, 9);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`);

--
-- Indexes for table `details_commande`
--
ALTER TABLE `details_commande`
  ADD PRIMARY KEY (`id_details_commande`);

--
-- Indexes for table `membre`
--
ALTER TABLE `membre`
  ADD PRIMARY KEY (`id_membre`);

--
-- Indexes for table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`),
  ADD UNIQUE KEY `id_produit` (`id_produit`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `details_commande`
--
ALTER TABLE `details_commande`
  MODIFY `id_details_commande` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `membre`
--
ALTER TABLE `membre`
  MODIFY `id_membre` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
