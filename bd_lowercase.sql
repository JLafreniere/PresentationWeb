-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Sam 11 Mars 2017 à 20:50
-- Version du serveur :  5.7.14
-- Version de PHP :  7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `bd_application`
--

-- --------------------------------------------------------

--
-- Structure de la table `activites`
--

CREATE TABLE `activites` (
  `id_activite` int(11) NOT NULL,
  `nom_activite` varchar(75) NOT NULL,
  `duree` varchar(30) NOT NULL,
  `commentaire` text NOT NULL,
  `couleur` char(6) NOT NULL,
  `hidden` tinyint(1) DEFAULT NULL,
  `ponderation` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `activites`
--

INSERT INTO `activites` (`id_activite`, `nom_activite`, `duree`, `commentaire`, `couleur`, `hidden`, `ponderation`) VALUES
(1, 'Soccer intérieur', '50', 'Equipes 4 contre 4', '3152C3', 0, 1),
(2, 'Badminton', '50', 'Badminton en double', '3152C3', 0, 1),
(4, 'Marathon', '180', '', '', 0, 6),
(6, 'Soccer extérieur', '50', 'Equipe 7 contre 7', '48B492', 0, 1),
(7, 'Natation', '60', 'Nage libre', '1B7EBB', 0, 2);

-- --------------------------------------------------------

--
-- Structure de la table `activites_prevues`
--

CREATE TABLE `activites_prevues` (
  `id_activite_prevue` int(11) NOT NULL,
  `date_activite` date NOT NULL,
  `heure_debut` time NOT NULL,
  `participants_max` int(11) NOT NULL,
  `frais` double NOT NULL,
  `endroit` varchar(200) NOT NULL,
  `id_activite` int(11) NOT NULL,
  `hidden` tinyint(1) DEFAULT NULL,
  `presences_prises` tinyint(1) NOT NULL,
  `responsable` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `groupes`
--

CREATE TABLE `groupes` (
  `id_groupe` int(11) NOT NULL,
  `nom_groupe` varchar(65) NOT NULL,
  `id_prof` int(11) NOT NULL,
  `id_Session` int(11) NOT NULL,
  `ensemble` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id_session` int(11) NOT NULL,
  `debut_session` date NOT NULL,
  `fin_session` date NOT NULL,
  `mi_session` date NOT NULL,
  `nom_session` varchar(60) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `sessions`
--

INSERT INTO `sessions` (`id_session`, `debut_session`, `fin_session`, `mi_session`, `nom_session`) VALUES
(1, '2017-03-08', '2017-03-21', '2017-03-15', 'Hiver');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id_utilisateur` int(11) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `id_groupe` int(11) DEFAULT NULL,
  `code_access` char(5) DEFAULT NULL,
  `actif` tinyint(1) DEFAULT '0',
  `courriel` varchar(75) DEFAULT NULL,
  `telephone` char(14) DEFAULT NULL,
  `sexe` char(1) DEFAULT NULL,
  `username` varchar(30) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `administrateur` tinyint(1) NOT NULL,
  `type_utilisateur` varchar(50) DEFAULT NULL,
  `date_inscription` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `nom`, `prenom`, `id_groupe`, `code_access`, `actif`, `courriel`, `telephone`, `sexe`, `username`, `password`, `administrateur`, `type_utilisateur`, `date_inscription`) VALUES
(1, 'administrateur', 'administrateur', 0, NULL, 1, NULL, NULL, NULL, 'administrateur', '$2y$10$uO8R31N32qnesMkIcWt9LO15yGVCbvsWkgg2P9yNmHb3ZGCR1IRb6', 2, 'administrateur', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur_activites`
--

CREATE TABLE `utilisateur_activites` (
  `id_utilisateur_activite` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_activite_prevue` int(11) NOT NULL,
  `present` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `activites`
--
ALTER TABLE `activites`
  ADD PRIMARY KEY (`id_activite`);

--
-- Index pour la table `activites_prevues`
--
ALTER TABLE `activites_prevues`
  ADD PRIMARY KEY (`id_activite_prevue`);

--
-- Index pour la table `groupes`
--
ALTER TABLE `groupes`
  ADD PRIMARY KEY (`id_groupe`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id_session`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_utilisateur`);

--
-- Index pour la table `utilisateur_activites`
--
ALTER TABLE `utilisateur_activites`
  ADD PRIMARY KEY (`id_utilisateur_activite`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `activites`
--
ALTER TABLE `activites`
  MODIFY `id_activite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `activites_prevues`
--
ALTER TABLE `activites_prevues`
  MODIFY `id_activite_prevue` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `groupes`
--
ALTER TABLE `groupes`
  MODIFY `id_groupe` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id_session` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT pour la table `utilisateur_activites`
--
ALTER TABLE `utilisateur_activites`
  MODIFY `id_utilisateur_activite` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
