-- phpMyAdmin SQL Dump
-- version 4.9.6
-- https://www.phpmyadmin.net/
--
-- Hôte : fq0hs.myd.infomaniak.com
-- Généré le :  mer. 10 jan. 2024 à 23:20
-- Version du serveur :  10.6.15-MariaDB-1:10.6.15+maria~deb11-log
-- Version de PHP :  7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `fq0hs_ent`
--

-- --------------------------------------------------------

--
-- Structure de la table `classes`
--

CREATE TABLE `classes` (
  `id_classe` int(11) NOT NULL,
  `nom_classe` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `classes`
--

INSERT INTO `classes` (`id_classe`, `nom_classe`) VALUES
(1, 'B2 MMI'),
(9, 'test'),
(8, 'test');

-- --------------------------------------------------------

--
-- Structure de la table `coef_modules`
--

CREATE TABLE `coef_modules` (
  `ext_id_module` int(11) NOT NULL,
  `ext_id_competence` int(11) NOT NULL,
  `coef_module` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `coef_modules`
--

INSERT INTO `coef_modules` (`ext_id_module`, `ext_id_competence`, `coef_module`) VALUES
(3, 1, 1),
(4, 1, 1.5),
(5, 1, 1),
(16, 1, 1),
(18, 1, 5),
(2, 2, 1),
(5, 2, 1),
(6, 2, 2),
(19, 2, 4),
(14, 3, 2),
(8, 3, 2),
(9, 3, 1.5),
(10, 3, 1),
(7, 3, 3),
(1, 3, 1.5),
(20, 3, 5),
(21, 3, 6),
(11, 4, 2),
(12, 4, 2),
(13, 4, 1.5),
(22, 4, 6),
(15, 5, 2),
(17, 5, 2),
(23, 5, 2.5),
(21, 1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `competences`
--

CREATE TABLE `competences` (
  `id_competence` int(11) NOT NULL,
  `nom_competence` varchar(255) NOT NULL,
  `ext_id_classe` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `competences`
--

INSERT INTO `competences` (`id_competence`, `nom_competence`, `ext_id_classe`) VALUES
(1, 'Comprendre', 1),
(2, 'Concevoir', 1),
(3, 'Exprimer', 1),
(4, 'Développer', 1),
(5, 'Entreprendre', 1),
(7, 'test', 8),
(8, 'test2', 8);

-- --------------------------------------------------------

--
-- Structure de la table `devoirs`
--

CREATE TABLE `devoirs` (
  `id_devoir` int(11) NOT NULL,
  `nom_devoir` varchar(255) NOT NULL,
  `ext_id_module` int(11) NOT NULL,
  `ext_id_prof` int(11) NOT NULL,
  `coef_devoir` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `devoirs`
--

INSERT INTO `devoirs` (`id_devoir`, `nom_devoir`, `ext_id_module`, `ext_id_prof`, `coef_devoir`) VALUES
(1, 'Oral Ted Talk', 1, 5, 1),
(2, 'Language boost', 1, 5, 0.5),
(3, 'Oral usability', 2, 5, 1),
(4, 'QCM', 3, 6, 1),
(5, 'Tri par cartes', 3, 6, 1),
(6, 'Échelles SUS', 3, 6, 1),
(7, 'Devoir amphi', 4, 7, 1),
(8, 'Oral', 4, 7, 1),
(9, 'DST', 5, 8, 1),
(10, 'DST', 6, 9, 1),
(11, 'Protocole 1 analyse et présentation d\'itw', 7, 10, 1),
(12, 'Les questions de l\'itw', 7, 10, 1),
(13, 'QCM typo', 8, 11, 2),
(14, 'Devoir GIF', 8, 11, 2),
(15, 'CC', 9, 12, 100),
(16, 'Dossier Mahara', 10, 13, 1),
(17, 'Tournage', 10, 14, 1),
(18, 'TP Test', 11, 6, 2),
(19, 'QCM', 11, 6, 1),
(20, 'DST', 12, 15, 1),
(21, 'TP test', 13, 16, 1),
(22, 'Suivi d\'activités séances de math', 14, 7, 0.5),
(23, 'Math 26/09 et 14/10/22', 14, 7, 1),
(24, 'R1.14 14/10/2022', 14, 7, 0.5),
(25, 'R1.14 08/11/2022', 14, 7, 1),
(26, 'R1.14 01/12/2022', 14, 7, 2),
(27, 'suivi des plannings et relevés des activités individuelles', 15, 7, 2),
(28, 'Test CNV', 15, 7, 1),
(29, 'DST', 16, 17, 1),
(30, 'Oral', 17, 9, 1),
(31, 'Écrit', 17, 9, 1),
(32, 'Qualité web', 18, 6, 0.5),
(33, 'Marketing', 18, 8, 0.5),
(34, 'Rapport', 19, 8, 1),
(35, 'Sylvie Dallet', 20, 12, 4),
(36, 'Florence Bister', 20, 7, 3),
(37, 'Frédéric Poisson', 20, 11, 3),
(38, 'Montage', 21, 13, 1),
(39, 'Protocole 2', 21, 10, 1),
(40, 'Codage video', 21, 7, 1),
(41, 'Sous-titre', 21, 5, 1),
(42, 'Note hébergement', 22, 16, 0.1),
(43, 'Note intégration', 22, 6, 0.4),
(44, 'Note dév.web', 22, 15, 0.5),
(45, 'Oral - déc 2022', 23, 7, 1),
(46, 'Bilan gestion de projet - janvier 2023', 23, 7, 1),
(47, 'test', 14, 7, 4);

-- --------------------------------------------------------

--
-- Structure de la table `groupes`
--

CREATE TABLE `groupes` (
  `id_groupe` int(11) NOT NULL,
  `nom_groupe` varchar(255) NOT NULL,
  `ext_id_classe` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `groupes`
--

INSERT INTO `groupes` (`id_groupe`, `nom_groupe`, `ext_id_classe`) VALUES
(1, 'TP A', 1),
(2, 'TP B', 1),
(3, 'TP C', 1),
(4, 'TP D', 1);

-- --------------------------------------------------------

--
-- Structure de la table `jury`
--

CREATE TABLE `jury` (
  `ext_id_prof` int(11) NOT NULL,
  `ext_id_module` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `jury`
--

INSERT INTO `jury` (`ext_id_prof`, `ext_id_module`) VALUES
(5, 1),
(5, 2),
(6, 3),
(7, 4),
(8, 5),
(9, 6),
(10, 7),
(11, 8),
(12, 9),
(13, 10),
(14, 10),
(6, 11),
(15, 12),
(16, 13),
(7, 14),
(7, 15),
(17, 16),
(9, 17),
(6, 18),
(8, 18),
(8, 19),
(12, 20),
(7, 20),
(11, 20),
(13, 21),
(10, 21),
(7, 21),
(5, 21),
(7, 23),
(15, 22),
(16, 22),
(6, 22);

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id_message` int(11) NOT NULL,
  `ext_id_sender` int(11) NOT NULL,
  `ext_id_receiver` int(11) NOT NULL,
  `message` text NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id_message`, `ext_id_sender`, `ext_id_receiver`, `message`, `date`) VALUES
(1, 1, 2, 'test pour un message vraiment très très long, comme ça je vois à quoi ça ressemble, et apparement là il est pas encore asser long pour faire mes tests', '2024-01-02 14:14:19'),
(48, 1, 4, 'Texte très long pour faire un dernier petit test', '2024-01-04 23:57:34'),
(3, 4, 1, 'test3', '2024-01-02 14:26:02'),
(4, 2, 1, 'dedcezfffd voili voilou', '0000-00-00 00:00:00'),
(5, 1, 2, 'test', '2024-01-02 17:32:27'),
(6, 1, 2, 'test encore', '2024-01-02 17:34:25'),
(7, 1, 2, 'okay, parfait, ça fonctionne', '2024-01-02 17:34:36'),
(8, 1, 2, 'test encore', '2024-01-02 17:35:02'),
(9, 1, 2, 'ddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd\r\n', '2024-01-02 21:05:53'),
(10, 1, 2, '????????????', '2024-01-03 14:10:42'),
(11, 1, 2, '😃', '2024-01-03 14:12:50'),
(12, 1, 2, '😃😃😃', '2024-01-03 14:13:03'),
(13, 1, 4, 'bien reçu', '2024-01-03 16:52:46'),
(14, 1, 4, 'test\r\n', '2024-01-03 17:07:47'),
(15, 1, 4, 'okay, c\'est good\r\n', '2024-01-03 17:07:55'),
(47, 1, 3, 'Hey, ça va ?', '2024-01-04 14:30:07'),
(49, 1, 4, 'Punaise, le texte était pas asser long de 3 caractères, donc nouveau message pour tester', '2024-01-04 23:58:02'),
(45, 1, 2, 'test', '2024-01-03 18:02:35'),
(54, 2, 1, 'je te gnock', '2024-01-09 09:18:22'),
(53, 2, 1, 'ça va ?', '2024-01-09 09:18:14'),
(52, 1, 2, 'coucou bebou', '2024-01-09 09:17:24'),
(51, 1, 13, 'Bonjour', '2024-01-06 16:35:32'),
(50, 7, 3, 'Test', '2024-01-05 14:21:18');

-- --------------------------------------------------------

--
-- Structure de la table `modules`
--

CREATE TABLE `modules` (
  `id_module` int(11) NOT NULL,
  `nom_module` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `modules`
--

INSERT INTO `modules` (`id_module`, `nom_module`) VALUES
(2, 'R1.02 Anglais pour le web'),
(3, 'R1.03 Ergo & accessibilit'),
(4, 'R1.04 Culture numériqu'),
(5, 'R1.05 Stratégie de com'),
(6, 'R1.06 Express com & rhéto'),
(7, 'R1.07 Écrit multi & narra'),
(8, 'R1.08 Production graphiqu'),
(9, 'R1.09 Culture artistique'),
(10, 'R1.10 Prod audio & vidéo'),
(1, 'R1.01 Anglais'),
(11, 'R1.11 Intégration'),
(12, 'R1.12 Développement web'),
(13, 'R1.13 Hébergement'),
(14, 'R1.14 Représ & trait info'),
(15, 'R1.15 Gestion de projet'),
(16, 'R1.16 Éco & droit du num'),
(17, 'R1.17 PPP'),
(18, 'SAÉ1.01 Audit com num'),
(19, 'SAÉ1.02 Reco de com num'),
(20, 'SAÉ1.03 Design graphiq'),
(21, 'SAÉ1.04 Prod audiovisue'),
(22, 'SAÉ1.05 Prod site'),
(23, 'SAÉ1.06 Gest prjt com nu');

-- --------------------------------------------------------

--
-- Structure de la table `notes`
--

CREATE TABLE `notes` (
  `id_note` int(11) NOT NULL,
  `valeur` float DEFAULT NULL,
  `ext_id_student` int(11) NOT NULL,
  `ext_id_devoir` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `notes`
--

INSERT INTO `notes` (`id_note`, `valeur`, `ext_id_student`, `ext_id_devoir`) VALUES
(1, 12, 1, 1),
(2, 3, 1, 2),
(3, 10, 1, 3),
(4, 15, 1, 4),
(5, 14.5, 1, 5),
(6, 20, 1, 6),
(7, 10, 1, 7),
(8, 15, 1, 8),
(9, 12, 1, 9),
(10, 10, 1, 10),
(11, 12, 1, 11),
(12, 12, 1, 12),
(13, 17, 1, 13),
(14, 14, 1, 14),
(15, 14, 1, 15),
(16, 14, 1, 16),
(17, 17, 1, 17),
(18, 17, 1, 18),
(19, 13.81, 1, 19),
(20, 18.9, 1, 20),
(21, 16.41, 1, 21),
(22, 22.66, 1, 22),
(23, 16.5, 1, 23),
(24, 19, 1, 24),
(25, 18, 1, 25),
(26, 15.5, 1, 26),
(27, 7.5, 1, 27),
(28, 11.8, 1, 28),
(29, 8, 1, 29),
(30, 18, 1, 30),
(31, 18, 1, 31),
(32, 17.5, 1, 32),
(33, 12, 1, 33),
(34, 13.5, 1, 34),
(35, 14, 1, 35),
(36, 11.5, 1, 36),
(37, 15, 1, 37),
(38, 14, 1, 38),
(39, 13.5, 1, 39),
(40, 14.7, 1, 40),
(41, 12, 1, 41),
(42, 17, 1, 42),
(43, 17, 1, 43),
(44, 18.5, 1, 44),
(45, 10, 1, 45),
(46, 10, 1, 46),
(47, 18, 2, 40),
(48, NULL, 3, 7),
(49, 13, 4, 40);

-- --------------------------------------------------------

--
-- Structure de la table `projets`
--

CREATE TABLE `projets` (
  `id_projet` int(11) NOT NULL,
  `nom_projet` varchar(255) NOT NULL,
  `ext_id_user` int(11) NOT NULL,
  `lien_projet` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `promotions`
--

CREATE TABLE `promotions` (
  `id_promo` int(11) NOT NULL,
  `ext_id_groupe` int(11) NOT NULL,
  `ext_id_usr` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `promotions`
--

INSERT INTO `promotions` (`id_promo`, `ext_id_groupe`, `ext_id_usr`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 3, 3),
(4, 3, 4);

-- --------------------------------------------------------

--
-- Structure de la table `themes`
--

CREATE TABLE `themes` (
  `ext_id_projet` int(11) NOT NULL,
  `nom_theme` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `username` varchar(250) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(250) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `numEtud` int(11) DEFAULT NULL,
  `statut` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `role` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `username`, `password`, `email`, `prenom`, `nom`, `date_naissance`, `numEtud`, `statut`, `description`, `role`) VALUES
(1, 'morgan.zarka', '$2y$10$NPyHLmPmYfnizFz.B1RJ9uhQ4UaeLFYCCPvifZkD5CLvevayhjJo6', 'morgan.zarka@edu.univ-eiffel.fr', 'Morgan', 'ZARKA', '2004-04-05', 269597, 'La moustache est là UwU', '', 1),
(2, 'waldi.fiaga', '$2y$10$q0pJMjYV/gBv.ypG28KqC.WbCdpTAJ7hzNkcRJTfUxanECACyCl76', 'waldi.fiaga@edu.univ-eiffel.fr', 'Waldi', 'FIAGA', NULL, 267468, 'Entre Ana et kata', NULL, 1),
(3, 'arthur.zachary', '//', 'arthur.zachary@edu.univ-eiffel.fr', 'Arthur', 'ZACHARY', '2004-11-18', 266813, 'K0la', NULL, 1),
(4, 'robin.vigier', '//', 'robin.vigier@edu.univ-eiffel.fr', 'Robin', 'VIGIER', '2004-07-11', 278513, 'Fan de figma, de wakfu et de bébou', NULL, 1),
(5, 'alexandre.leroy', '$2y$10$7wcPuILJE8yAya/7I/L59ewIwTGIpGRTrKUCIAsdS3.XG.qpKKznu', 'alexandre.leroy@univ-eiffel.fr', 'Alexandre', 'LEROY', NULL, NULL, NULL, NULL, 2),
(6, 'gaelle.charpentier', '$2y$10$.exubzo1sf1nmroYw3Vm.e/Lx/i9gRpbYbinEJGl5MVh9Ay5T2V2S', 'gaelle.charpentier@univ-eiffel.fr', 'Gaëlle', 'CHARPENTIER', NULL, NULL, NULL, NULL, 2),
(7, 'florence.bister', '$2y$10$.wdwvQvPhpNml6.beqqF/ep48ahOXiPSoJhXyB3gEdUTquYxOVG9O', 'florence.bister@univ-eiffel.fr', 'Florence', 'BISTER', NULL, NULL, NULL, NULL, 2),
(8, 'leyla.jaoued', '$2y$10$Ux1EIFAEK0Ou9ZVZ/ZqZ0OE.DcHNHzBIX6Ai7ipG4kH2KwBm/NS5m', 'leyla.jaoued@univ-eiffel.fr', 'Leyla', 'JAOUED-ABASSI', NULL, NULL, NULL, NULL, 2),
(9, 'odile.niel', '$2y$10$X53ya3bp9X0uqhkg976sW.qG9o2eaUF//56MEVeOe1VucUw8x80mG', 'odile.niel@univ-eiffel.fr', 'Odile', 'NIEL', NULL, NULL, NULL, NULL, 2),
(10, 'kpc', '$2y$10$o2Y./mjazOih2DvtJw7q.e5Rzwcp4x3477rbJk/jHQng9DZyzJ7jO', 'kpc@univ-eiffel.fr', 'Karim Pierre', 'CHABANE', NULL, NULL, NULL, NULL, 2),
(11, 'frederic.poisson', '$2y$10$YoakgZVC8S1o/HqH0iD1/u90MqRwFBnX0QILQVqaAN.pJ8.0l92NK', 'frederic.poisson@univ-eiffel.fr', 'Frédéric', 'POISSON', NULL, NULL, NULL, NULL, 2),
(12, 'sylvie.dallet', '$2y$10$2XSJ1sD08RGS14S0Ji7e1urkXu8g3ms6tC39JbygUdEXxRhD283H6', 'sylvie.dallet@univ-eiffel.fr', 'Sylvie', 'DALLET', NULL, NULL, NULL, NULL, 2),
(13, 'anne.tasso', '$2y$10$tljmumb722A9nVKmQpXt..pxa8tDmfLWdGa9YergppQ2pvuBdRFaO', 'anne.tasso@univ-eiffel.fr', 'Anne', 'TASSO', NULL, NULL, NULL, NULL, 2),
(14, 'lahcen.soussi', '$2y$10$Bu5wU5N9DQBK2lSesU5Q2Ol49kkfkFeBgptDJVncYggrK7ZPYMjCu', 'lahcen.soussi@univ-eiffel.fr', 'Lahcen', 'SOUSSI', NULL, NULL, NULL, NULL, 2),
(15, 'philippe.gambette', '$2y$10$xuMtQqfSSy4esB8l9BoeWOiX2PB/lM/ednIN4tty9fFmU9SxleW/a', 'philippe.gambette@univ-eiffel.fr', 'Philippe', 'GAMBETTE', NULL, NULL, NULL, NULL, 2),
(16, 'matthieu.berthet', '$2y$10$DtPo3dZt5naEp1U41T3JhOAPMVI6LohtYYOfTd2WySZuiqUCnuHWO', 'matthieu.berthet@univ-eiffel.fr', 'Matthieu', 'BERTHET', NULL, NULL, NULL, NULL, 2),
(17, 'mem', '$2y$10$jSftAhVvXGXu4.H0kDDUIeD7F.wPTwq9gosqOxkeLfNkG9F.XkdLS', 'mem@univ-eiffel.fr', 'Marie Emmanuelle', 'MEZY', NULL, NULL, NULL, NULL, 2),
(18, 'admin', '$2y$10$qhlaoJuVH9EotRII/j8sXOypBrIugBismpv.TRRHLV4nZ.FRaZjaa', 'contact@morganzarka.dev', 'Admin', 'ADMIN', NULL, NULL, 'Votre administrateur préféré', NULL, 3),
(25, 'renaud.eppstein', '$2y$10$IfWqsdkZ7WHv/pZSrZQuFuQHpTuzY1RG8t4g6NXroRtLbOtb5OcJK', 'renaud.eppstein@univ-eiffel.fr', 'Renaud', 'EPPSTEIN', NULL, NULL, NULL, NULL, 2);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id_classe`);

--
-- Index pour la table `competences`
--
ALTER TABLE `competences`
  ADD PRIMARY KEY (`id_competence`);

--
-- Index pour la table `devoirs`
--
ALTER TABLE `devoirs`
  ADD PRIMARY KEY (`id_devoir`);

--
-- Index pour la table `groupes`
--
ALTER TABLE `groupes`
  ADD PRIMARY KEY (`id_groupe`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id_message`);

--
-- Index pour la table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id_module`);

--
-- Index pour la table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id_note`);

--
-- Index pour la table `projets`
--
ALTER TABLE `projets`
  ADD PRIMARY KEY (`id_projet`);

--
-- Index pour la table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id_promo`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `numEtud` (`numEtud`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `classes`
--
ALTER TABLE `classes`
  MODIFY `id_classe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `competences`
--
ALTER TABLE `competences`
  MODIFY `id_competence` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `devoirs`
--
ALTER TABLE `devoirs`
  MODIFY `id_devoir` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT pour la table `groupes`
--
ALTER TABLE `groupes`
  MODIFY `id_groupe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id_message` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT pour la table `modules`
--
ALTER TABLE `modules`
  MODIFY `id_module` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `notes`
--
ALTER TABLE `notes`
  MODIFY `id_note` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT pour la table `projets`
--
ALTER TABLE `projets`
  MODIFY `id_projet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id_promo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
