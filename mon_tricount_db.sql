-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : ven. 12 déc. 2025 à 14:00
-- Version du serveur : 5.7.44
-- Version de PHP : 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mon_tricount_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `label` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `label`) VALUES
(1, 'Transport'),
(2, 'Logement'),
(3, 'Nourriture'),
(4, 'Sorties');

-- --------------------------------------------------------

--
-- Structure de la table `expense`
--

CREATE TABLE `expense` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `is_settled` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `expense`
--

INSERT INTO `expense` (`id`, `title`, `amount`, `date`, `user_id`, `category_id`, `is_settled`) VALUES
(1, 'Fouquet\'s', 1344.23, '2025-12-01', 1, 3, 1),
(2, 'tacos', 1233.76, '2025-12-08', 1, 3, 1),
(3, 'Disney', 15.00, '2025-12-12', 2, 4, 1),
(4, 'Paris-Roubaix', 150.00, '2025-12-12', 3, 1, 1),
(5, 'Astérix', 150.00, '2025-12-12', 1, 4, 1);

-- --------------------------------------------------------

--
-- Structure de la table `expense_participant`
--

CREATE TABLE `expense_participant` (
  `expense_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `expense_participant`
--

INSERT INTO `expense_participant` (`expense_id`, `user_id`) VALUES
(2, 1),
(3, 1),
(4, 1),
(4, 2),
(5, 2),
(5, 3);

-- --------------------------------------------------------

--
-- Structure de la table `reimbursement`
--

CREATE TABLE `reimbursement` (
  `id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `expense_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `reimbursement`
--

INSERT INTO `reimbursement` (`id`, `amount`, `date`, `from_user_id`, `to_user_id`, `expense_id`) VALUES
(1, 15.00, '2025-12-10', 2, 1, NULL),
(2, 58.00, '2025-12-09', 2, 1, NULL),
(3, 125.00, '2025-12-12', 2, 1, 2),
(4, 125.00, '2025-12-12', 2, 1, 2),
(5, 233.76, '2025-12-12', 2, 1, 2),
(6, 1334.00, '2025-12-12', 2, 1, 1),
(7, 0.23, '2025-12-12', 2, 1, 1),
(8, 1200.00, '2025-12-12', 2, 1, 2),
(9, 1.00, '2025-12-12', 2, 1, 1),
(10, 1300.00, '2025-12-12', 2, 1, 1),
(11, 10.00, '2025-12-12', 1, 2, 3),
(12, 5.00, '2025-12-12', 3, 2, NULL),
(13, 10.00, '2025-12-12', 3, 2, 3),
(14, 100.00, '2025-12-12', 1, 3, 4),
(15, 50.00, '2025-12-12', 1, 3, 4),
(16, 50.00, '2025-12-12', 2, 1, 5),
(17, 100.00, '2025-12-12', 3, 1, 5);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `role` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `username`, `role`) VALUES
(1, 'peter.parker@nyc.com', '$2y$10$Bj5CI6t0HgIHpKU5LatLgOt8Vej5dfJjQoINvb3R8W29t3bFGzbYu', 'Peter parker', 'ADMIN'),
(2, 'miles.morales@nyc.com', '$2y$10$hYm12D4AswtX74kaa26nxe0c9w2ifVZqEZnr8lFNolqgP2nMtsVYe', 'Miles Morales', 'USER'),
(3, 'penny.parker@nyc.com', '$2y$10$nzsiQDFVsJIsPIV9ZR97reTtEcao7WAmVxV2rE3N.ehnvZ9j/IH0G', 'Penny Parker', 'USER');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Index pour la table `expense_participant`
--
ALTER TABLE `expense_participant`
  ADD KEY `expense_id` (`expense_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `reimbursement`
--
ALTER TABLE `reimbursement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `from_user_id` (`from_user_id`),
  ADD KEY `to_user_id` (`to_user_id`),
  ADD KEY `fk_reimbursement_expense` (`expense_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `expense`
--
ALTER TABLE `expense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `reimbursement`
--
ALTER TABLE `reimbursement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `expense`
--
ALTER TABLE `expense`
  ADD CONSTRAINT `expense_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expense_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- Contraintes pour la table `expense_participant`
--
ALTER TABLE `expense_participant`
  ADD CONSTRAINT `expense_participant_ibfk_1` FOREIGN KEY (`expense_id`) REFERENCES `expense` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expense_participant_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reimbursement`
--
ALTER TABLE `reimbursement`
  ADD CONSTRAINT `fk_reimbursement_expense` FOREIGN KEY (`expense_id`) REFERENCES `expense` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reimbursement_ibfk_1` FOREIGN KEY (`from_user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reimbursement_ibfk_2` FOREIGN KEY (`to_user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
