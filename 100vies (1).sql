-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 16 jan. 2026 à 21:48
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
-- Base de données : `100vies`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `nom_admin` varchar(500) DEFAULT NULL,
  `email_admin` varchar(100) DEFAULT NULL,
  `mdp_admin` varchar(255) DEFAULT NULL,
  `role` enum('gestionnaire','superadmin') DEFAULT NULL,
  `theme` varchar(10) DEFAULT 'light'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id_admin`, `nom_admin`, `email_admin`, `mdp_admin`, `role`, `theme`) VALUES
(1, 'Yosra', 'yosra2errais@gmail.com', 'amira', 'superadmin', 'dark'),
(2, 'Amira', 'amirajardak@gmail.com', 'yosra', 'superadmin', 'light');

-- --------------------------------------------------------

--
-- Structure de la table `appelurgent`
--

CREATE TABLE `appelurgent` (
  `id_appel` int(11) NOT NULL,
  `type_appel` varchar(20) DEFAULT NULL,
  `urgence` tinyint(1) DEFAULT NULL,
  `description_appel` text DEFAULT NULL,
  `date_appel` date DEFAULT NULL,
  `id_receveur` int(11) DEFAULT NULL,
  `actif` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `appelurgent`
--

INSERT INTO `appelurgent` (`id_appel`, `type_appel`, `urgence`, `description_appel`, `date_appel`, `id_receveur`, `actif`) VALUES
(1, 'Sang', 1, 'Besoin urgent de sang A+', '2025-01-16', 1, 1),
(2, 'Sang', 1, 'Urgence chirurgicale', '2025-01-19', 2, 1),
(3, 'Sang', 1, 'Urgence pour groupe O-', '2025-02-11', 3, 1),
(4, 'Plaquettes', 1, 'Besoin immédiat de plaquettes', '2025-02-13', 4, 1),
(5, NULL, NULL, 'test', '2026-01-02', 1, 1),
(6, NULL, NULL, 'test', '2026-01-04', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `campagne`
--

CREATE TABLE `campagne` (
  `id_cmp` int(11) NOT NULL,
  `nom_cmp` varchar(100) NOT NULL,
  `date_deb_cmp` date NOT NULL,
  `date_fin_cmp` date NOT NULL,
  `lieu` varchar(100) NOT NULL,
  `description_cmp` text NOT NULL,
  `status_cmp` enum('Prévu','En cours','Términée') NOT NULL,
  `id_centre` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `campagne`
--

INSERT INTO `campagne` (`id_cmp`, `nom_cmp`, `date_deb_cmp`, `date_fin_cmp`, `lieu`, `description_cmp`, `status_cmp`, `id_centre`, `image`) VALUES
(1, 'Campagne Don de Sang Tunis', '2025-02-01', '2025-02-05', 'Tunis', 'Donner du sang sauve des vies', '', 1, 'campagne1.png'),
(2, 'Campagne Don de Sang Sfax', '2025-03-01', '2025-03-03', 'Sfax', 'Campagne régionale', 'Términée', 5, 'campagne2.png'),
(3, 'Campagne Ramadan Don de Sang', '2025-03-10', '2025-03-15', 'Ariana', 'Collecte spéciale Ramadan', '', 1, 'campagne3.png'),
(4, 'Campagne Universitaire', '2025-04-05', '2025-04-07', 'Sousse', 'Don de sang pour étudiants', '', 2, 'campagne4.png'),
(6, 'test', '2025-12-12', '2026-02-12', 'sousse', 'ytgrfedz', 'Prévu', 1, ''),
(7, 'test', '2026-02-12', '2026-02-14', 'sousse', 'aqestrdry', 'Prévu', 1, '');

-- --------------------------------------------------------

--
-- Structure de la table `centre`
--

CREATE TABLE `centre` (
  `id_centre` int(11) NOT NULL,
  `nom_centre` varchar(100) DEFAULT NULL,
  `adresse_centre` varchar(150) DEFAULT NULL,
  `gouvernorat` varchar(50) DEFAULT NULL,
  `lat` decimal(10,7) NOT NULL,
  `lng` decimal(10,7) NOT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `type_centre` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `centre`
--

INSERT INTO `centre` (`id_centre`, `nom_centre`, `adresse_centre`, `gouvernorat`, `lat`, `lng`, `tel`, `email`, `type_centre`) VALUES
(1, 'Centre National de Transfusion Sanguine Tunis', 'Rue Jbel Lakhdhar, Bab Saadoun', 'Tunis', 36.8051311, 10.1572654, '71234567', 'cnts.tunis@cnts.tn', 'Public'),
(2, 'Centre de Transfusion Sanguine Sousse', 'Avenue Hedi Chaker', 'Sousse', 35.8450097, 10.6220690, '73210045', 'cts.sousse@cnts.tn', 'Public'),
(3, 'Centre de Transfusion Sanguine Sfax', 'Route El Ain', 'Sfax', 34.7417799, 10.7519621, '74221234', 'cts.sfax@cnts.tn', 'Public'),
(4, 'Centre Régional de Transfusion Bizerte', 'Avenue Habib Bougatfa', 'Bizerte', 37.2757583, 9.8650920, '72223344', 'cts.bizerte@cnts.tn', 'Public'),
(6, 'Centre de Transfusion Sanguine Gabès', 'Rue de la Liberté', 'Gabès', 33.8622940, 10.1149603, '75227890', 'cts.gabes@cnts.tn', 'Public'),
(7, 'Centre Régional de Transfusion Gafsa', 'Cité El Ons', 'Gafsa', 34.4204735, 8.7969436, '76234598', 'cts.gafsa@cnts.tn', 'Public'),
(11, 'Centre Mobile de Don de Sang Ariana', 'Avenue de l\'Indépendance', 'Ariana', 36.8678795, 10.1834465, '71234567', 'cts.ariana@cnts.tn', 'Mobile');

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

CREATE TABLE `commentaires` (
  `id_comment` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `id_donneur` int(11) NOT NULL,
  `type_contenu` enum('appel','campagne') NOT NULL,
  `id_contenu` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commentaires_appel`
--

CREATE TABLE `commentaires_appel` (
  `id_commentaire` int(11) NOT NULL,
  `id_appel` int(11) NOT NULL,
  `contenu` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commentaires_appel`
--

INSERT INTO `commentaires_appel` (`id_commentaire`, `id_appel`, `contenu`) VALUES
(2, 4, '@Jlassi'),
(4, 4, '@Ben Salah'),
(10, 4, '@Trabelsi');

-- --------------------------------------------------------

--
-- Structure de la table `commentaires_campagne`
--

CREATE TABLE `commentaires_campagne` (
  `id_commentaire` int(11) NOT NULL,
  `id_cmp` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `date_commentaire` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commentaires_campagne`
--

INSERT INTO `commentaires_campagne` (`id_commentaire`, `id_cmp`, `contenu`, `date_commentaire`) VALUES
(1, 4, 'test2', '2026-01-01 19:01:31');

-- --------------------------------------------------------

--
-- Structure de la table `commentaires_info`
--

CREATE TABLE `commentaires_info` (
  `id_commentaire` int(11) NOT NULL,
  `id_info` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `date_commentaire` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commentaires_info`
--

INSERT INTO `commentaires_info` (`id_commentaire`, `id_info`, `contenu`, `date_commentaire`) VALUES
(1, 15, 'test3', '2026-01-01 19:01:42');

-- --------------------------------------------------------

--
-- Structure de la table `don`
--

CREATE TABLE `don` (
  `id_don` int(11) NOT NULL,
  `date_don` date DEFAULT NULL,
  `heure` time DEFAULT NULL,
  `id_donneur` int(11) DEFAULT NULL,
  `id_centre` int(11) DEFAULT NULL,
  `id_receveur` int(11) DEFAULT NULL,
  `type_don` varchar(20) DEFAULT NULL,
  `etat_don` varchar(30) DEFAULT NULL,
  `volume` decimal(5,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `don`
--

INSERT INTO `don` (`id_don`, `date_don`, `heure`, `id_donneur`, `id_centre`, `id_receveur`, `type_don`, `etat_don`, `volume`) VALUES
(7, '2025-02-02', '10:30:00', 1, NULL, 1, 'Sang', 'validé', 450),
(8, '2025-02-03', '11:00:00', 2, NULL, 2, 'Sang', 'en attente', 450),
(9, '2025-03-02', '09:45:00', 3, NULL, 1, 'Sang', 'validé', 450),
(14, '2025-02-14', '09:15:00', 4, 1, 3, 'Sang', 'validé', 450),
(15, '2025-02-15', '10:45:00', 5, 2, 4, 'Plaquettes', 'validé', 300),
(18, '2026-01-12', '09:15:00', 1, 2, NULL, 'A+', 'En attente', 20),
(19, '2026-01-12', '09:15:00', 1, 2, NULL, 'A+', 'En attente', 20),
(20, '2026-01-06', '11:00:00', 1, 11, NULL, 'A+', 'En attente', 50),
(21, '2026-02-12', '10:00:00', 1, 2, NULL, 'A+', 'En attente', 20),
(22, '2026-02-13', '10:00:00', 1, 2, NULL, 'A+', 'En attente', 30),
(23, '2026-07-12', '10:00:00', 1, 4, NULL, 'A+', 'En attente', 30),
(24, '2027-01-11', '10:11:00', 1, 2, NULL, 'A+', 'En attente', 10),
(25, '2028-08-12', '10:00:00', 1, 11, NULL, 'A+', 'En attente', 10),
(26, '2029-08-12', '10:00:00', 1, 1, NULL, 'A+', 'En attente', 20);

-- --------------------------------------------------------

--
-- Structure de la table `donneur`
--

CREATE TABLE `donneur` (
  `id_donneur` int(11) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `datenais` date DEFAULT NULL,
  `sexe` char(50) DEFAULT NULL,
  `grp_sanguin` varchar(50) DEFAULT NULL CHECK (`grp_sanguin` in ('A+','A-','B+','B-','AB+','AB-','O+','O-')),
  `email` varchar(100) DEFAULT NULL,
  `mdp` varchar(255) DEFAULT NULL,
  `adresse` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `donneur`
--

INSERT INTO `donneur` (`id_donneur`, `nom`, `prenom`, `datenais`, `sexe`, `grp_sanguin`, `email`, `mdp`, `adresse`) VALUES
(1, 'Ben Salah', 'Ahmed', '1996-05-12', 'H', 'A+', 'ahmed@gmail.com', '1234', 'Tunis'),
(2, 'Trabelsi', 'Ines', '1993-09-20', 'F', 'O+', 'ines@gmail.com', '1234', 'Sfax'),
(3, 'Mansouri', 'Walid', '1988-03-02', 'H', 'B+', 'walid@gmail.com', '1234', 'Sousse'),
(4, 'Jlassi', 'Marouen', '1991-07-18', 'H', 'AB+', 'marouen@gmail.com', '1234', 'Ariana'),
(5, 'Saidi', 'Rim', '1998-11-02', 'F', 'A-', 'rim@gmail.com', '1234', 'Nabeul'),
(6, 'Chouchane', 'Houssem', '1985-01-25', 'H', 'O-', 'houssem@gmail.com', '1234', 'Bizerte');

-- --------------------------------------------------------

--
-- Structure de la table `likes`
--

CREATE TABLE `likes` (
  `id_like` int(11) NOT NULL,
  `type` enum('appel','campagne','info') NOT NULL,
  `id_pub` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `date_like` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `likes`
--

INSERT INTO `likes` (`id_like`, `type`, `id_pub`, `id_user`, `date_like`) VALUES
(5, 'campagne', 4, 1, '2026-01-01 19:11:31'),
(10, 'appel', 4, 1, '2026-01-01 19:21:52'),
(12, 'info', 14, 1, '2026-01-01 19:33:09'),
(14, 'appel', 2, 1, '2026-01-01 20:52:39'),
(15, 'campagne', 3, 1, '2026-01-01 21:03:20'),
(16, 'appel', 3, 1, '2026-01-01 21:28:04'),
(17, 'appel', 1, 1, '2026-01-01 21:28:06'),
(18, 'campagne', 2, 1, '2026-01-01 21:41:22'),
(20, 'appel', 5, 1, '2026-01-02 20:36:14'),
(22, 'appel', 6, 1, '2026-01-16 19:58:25');

-- --------------------------------------------------------

--
-- Structure de la table `notif`
--

CREATE TABLE `notif` (
  `id_notification` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `role` enum('donneur','receveur','admin') NOT NULL,
  `titre` varchar(100) DEFAULT NULL,
  `message` text NOT NULL,
  `lien` varchar(255) DEFAULT NULL,
  `lu` tinyint(1) DEFAULT 0,
  `date_notif` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `notif`
--

INSERT INTO `notif` (`id_notification`, `id_user`, `role`, `titre`, `message`, `lien`, `lu`, `date_notif`) VALUES
(1, 1, 'donneur', 'Rendez-vous confirmé', 'Votre rendez-vous de don du 12/07/2026 est bien enregistré.', 'notifications.php', 1, '2026-01-10 21:49:14'),
(7, 1, 'donneur', 'Rendez-vous confirmé', 'Votre rendez-vous de don du 12/08/2028 a été enregistré.', 'notifications.php', 1, '2026-01-10 23:03:44'),
(8, 1, 'donneur', 'Rendez-vous confirmé', 'Votre rendez-vous de don du 12/08/2029 a été enregistré.', 'notifications.php', 1, '2026-01-11 15:33:44'),
(9, 1, 'receveur', 'Nouvelle campagne', 'Nouvelle campagne disponible : test', 'participer.php?id=7', 0, '2026-01-16 19:15:09'),
(10, 2, 'receveur', 'Nouvelle campagne', 'Nouvelle campagne disponible : test', 'participer.php?id=7', 0, '2026-01-16 19:15:09'),
(11, 3, 'receveur', 'Nouvelle campagne', 'Nouvelle campagne disponible : test', 'participer.php?id=7', 0, '2026-01-16 19:15:09'),
(12, 4, 'receveur', 'Nouvelle campagne', 'Nouvelle campagne disponible : test', 'participer.php?id=7', 0, '2026-01-16 19:15:09');

-- --------------------------------------------------------

--
-- Structure de la table `notification`
--

CREATE TABLE `notification` (
  `id_notif` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `date_envoi` date DEFAULT NULL,
  `statu` varchar(20) DEFAULT NULL,
  `type_notif` varchar(50) DEFAULT NULL,
  `id_donneur` int(11) DEFAULT NULL,
  `id_receveur` int(11) DEFAULT NULL,
  `nom_donneur` varchar(255) DEFAULT NULL,
  `email_donneur` varchar(255) DEFAULT NULL,
  `sujet` varchar(255) DEFAULT NULL,
  `contenu_message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `notification`
--

INSERT INTO `notification` (`id_notif`, `message`, `date_envoi`, `statu`, `type_notif`, `id_donneur`, `id_receveur`, `nom_donneur`, `email_donneur`, `sujet`, `contenu_message`) VALUES
(17, NULL, '2026-01-01', 'Non lu', NULL, NULL, NULL, 'Amira Jardak', 'jardakamira@gmail.com', 'test2', 'ukyjsfdjikyujy');

-- --------------------------------------------------------

--
-- Structure de la table `participation`
--

CREATE TABLE `participation` (
  `id_donneur` int(11) NOT NULL,
  `id_cmp` int(11) NOT NULL,
  `role` enum('participant','organisateur','bénévole') NOT NULL,
  `statut` enum('en_attente','confirmé','annulé') NOT NULL,
  `date_participation` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `participation`
--

INSERT INTO `participation` (`id_donneur`, `id_cmp`, `role`, `statut`, `date_participation`) VALUES
(1, 1, '', 'confirmé', '2026-01-04 16:30:16'),
(2, 1, '', 'confirmé', '2026-01-04 16:30:16'),
(3, 2, '', 'confirmé', '2026-01-04 16:30:16'),
(4, 3, '', 'confirmé', '2026-01-04 16:30:16'),
(5, 4, '', 'confirmé', '2026-01-04 16:30:16'),
(1, 4, 'participant', 'en_attente', '2026-01-04 16:30:38'),
(1, 4, 'participant', 'en_attente', '2026-01-04 16:32:14'),
(1, 4, 'participant', 'en_attente', '2026-01-04 16:32:14'),
(1, 4, 'participant', 'en_attente', '2026-01-04 16:32:27'),
(1, 4, 'participant', 'en_attente', '2026-01-04 16:32:27'),
(1, 4, 'participant', 'en_attente', '2026-01-04 16:32:38'),
(1, 4, 'participant', 'en_attente', '2026-01-04 16:35:46'),
(1, 4, 'participant', 'en_attente', '2026-01-04 17:08:47'),
(1, 4, 'participant', 'en_attente', '2026-01-15 17:22:59'),
(1, 4, 'participant', 'en_attente', '2026-01-15 17:24:04');

-- --------------------------------------------------------

--
-- Structure de la table `receveur`
--

CREATE TABLE `receveur` (
  `id_receveur` int(11) NOT NULL,
  `nom_receveur` varchar(100) DEFAULT NULL,
  `prenom_receveur` varchar(100) DEFAULT NULL,
  `groupe_sanguin_receveur` varchar(3) DEFAULT NULL,
  `hopital` varchar(100) DEFAULT NULL,
  `besoin_urgent` tinyint(1) NOT NULL,
  `date_demande` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `email_receveur` varchar(100) NOT NULL,
  `mdp_receveur` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `receveur`
--

INSERT INTO `receveur` (`id_receveur`, `nom_receveur`, `prenom_receveur`, `groupe_sanguin_receveur`, `hopital`, `besoin_urgent`, `date_demande`, `description`, `email_receveur`, `mdp_receveur`) VALUES
(1, 'Khelifi', 'Sami', 'A+', 'Hôpital Charles Nicolle', 1, '2025-01-15', 'Accident grave', 'sami@gmail.com', '1234'),
(2, 'Bouazizi', 'Mouna', 'O+', 'Hôpital Farhat Hached', 0, '2025-01-18', 'Opération programmée', 'mouna@gmail.com', '1234'),
(3, 'Ayadi', 'Nour', 'AB+', 'Hôpital Mongi Slim', 1, '2025-02-10', 'Transfusion urgente', 'nour@gmail.com', '1234'),
(4, 'Mejri', 'Youssef', 'O-', 'Hôpital Régional Béja', 0, '2025-02-12', 'Traitement long terme', 'youssef@gmail.com', '1234');

-- --------------------------------------------------------

--
-- Structure de la table `statistiques`
--

CREATE TABLE `statistiques` (
  `id_stat` int(11) NOT NULL,
  `type_stat` varchar(30) DEFAULT NULL,
  `date_calcul` date DEFAULT NULL,
  `valeur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `statistiques`
--

INSERT INTO `statistiques` (`id_stat`, `type_stat`, `date_calcul`, `valeur`) VALUES
(1, 'Total dons', '2025-03-01', 3),
(2, 'Volume collecté', '2025-03-01', 1350),
(3, 'Dons février', '2025-02-28', 5),
(4, 'Donneurs actifs', '2025-02-28', 8);

-- --------------------------------------------------------

--
-- Structure de la table `temoignages`
--

CREATE TABLE `temoignages` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `histoire` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `temoignages`
--

INSERT INTO `temoignages` (`id`, `nom`, `role`, `histoire`, `created_at`) VALUES
(12, 'Ahmed Ben Salah', 'Donneur', 'Donner du sang est un devoir humain.', '2025-02-01 23:00:00'),
(13, 'Mouna Bouazizi', 'Receveur', 'Un don m’a sauvé la vie.', '2025-01-19 23:00:00'),
(14, 'Rim Saidi', 'Donneur', 'Un petit geste peut sauver une vie.', '2025-02-14 23:00:00'),
(15, 'Nour Ayadi', 'Receveur', 'Merci à tous les donneurs anonymes.', '2025-02-15 23:00:00');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Index pour la table `appelurgent`
--
ALTER TABLE `appelurgent`
  ADD PRIMARY KEY (`id_appel`);

--
-- Index pour la table `campagne`
--
ALTER TABLE `campagne`
  ADD PRIMARY KEY (`id_cmp`);

--
-- Index pour la table `centre`
--
ALTER TABLE `centre`
  ADD PRIMARY KEY (`id_centre`);

--
-- Index pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD PRIMARY KEY (`id_comment`);

--
-- Index pour la table `commentaires_appel`
--
ALTER TABLE `commentaires_appel`
  ADD PRIMARY KEY (`id_commentaire`),
  ADD KEY `id_appel` (`id_appel`);

--
-- Index pour la table `commentaires_campagne`
--
ALTER TABLE `commentaires_campagne`
  ADD PRIMARY KEY (`id_commentaire`),
  ADD KEY `id_cmp` (`id_cmp`);

--
-- Index pour la table `commentaires_info`
--
ALTER TABLE `commentaires_info`
  ADD PRIMARY KEY (`id_commentaire`),
  ADD KEY `id_info` (`id_info`);

--
-- Index pour la table `don`
--
ALTER TABLE `don`
  ADD PRIMARY KEY (`id_don`),
  ADD KEY `fk_id_donneur` (`id_donneur`),
  ADD KEY `fk_id_centre` (`id_centre`),
  ADD KEY `fk_id_receveur` (`id_receveur`);

--
-- Index pour la table `donneur`
--
ALTER TABLE `donneur`
  ADD PRIMARY KEY (`id_donneur`);

--
-- Index pour la table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id_like`),
  ADD UNIQUE KEY `unique_like` (`type`,`id_pub`,`id_user`);

--
-- Index pour la table `notif`
--
ALTER TABLE `notif`
  ADD PRIMARY KEY (`id_notification`);

--
-- Index pour la table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id_notif`);

--
-- Index pour la table `participation`
--
ALTER TABLE `participation`
  ADD KEY `fk_id_cmp` (`id_cmp`);

--
-- Index pour la table `receveur`
--
ALTER TABLE `receveur`
  ADD PRIMARY KEY (`id_receveur`);

--
-- Index pour la table `statistiques`
--
ALTER TABLE `statistiques`
  ADD PRIMARY KEY (`id_stat`);

--
-- Index pour la table `temoignages`
--
ALTER TABLE `temoignages`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `appelurgent`
--
ALTER TABLE `appelurgent`
  MODIFY `id_appel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `campagne`
--
ALTER TABLE `campagne`
  MODIFY `id_cmp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `centre`
--
ALTER TABLE `centre`
  MODIFY `id_centre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `commentaires`
--
ALTER TABLE `commentaires`
  MODIFY `id_comment` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commentaires_appel`
--
ALTER TABLE `commentaires_appel`
  MODIFY `id_commentaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `commentaires_campagne`
--
ALTER TABLE `commentaires_campagne`
  MODIFY `id_commentaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `commentaires_info`
--
ALTER TABLE `commentaires_info`
  MODIFY `id_commentaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `don`
--
ALTER TABLE `don`
  MODIFY `id_don` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `donneur`
--
ALTER TABLE `donneur`
  MODIFY `id_donneur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `likes`
--
ALTER TABLE `likes`
  MODIFY `id_like` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `notif`
--
ALTER TABLE `notif`
  MODIFY `id_notification` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `notification`
--
ALTER TABLE `notification`
  MODIFY `id_notif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `receveur`
--
ALTER TABLE `receveur`
  MODIFY `id_receveur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `statistiques`
--
ALTER TABLE `statistiques`
  MODIFY `id_stat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `temoignages`
--
ALTER TABLE `temoignages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commentaires_appel`
--
ALTER TABLE `commentaires_appel`
  ADD CONSTRAINT `commentaires_appel_ibfk_1` FOREIGN KEY (`id_appel`) REFERENCES `appelurgent` (`id_appel`) ON DELETE CASCADE;

--
-- Contraintes pour la table `commentaires_campagne`
--
ALTER TABLE `commentaires_campagne`
  ADD CONSTRAINT `commentaires_campagne_ibfk_1` FOREIGN KEY (`id_cmp`) REFERENCES `campagne` (`id_cmp`) ON DELETE CASCADE;

--
-- Contraintes pour la table `commentaires_info`
--
ALTER TABLE `commentaires_info`
  ADD CONSTRAINT `commentaires_info_ibfk_1` FOREIGN KEY (`id_info`) REFERENCES `temoignages` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `don`
--
ALTER TABLE `don`
  ADD CONSTRAINT `fk_id_centre` FOREIGN KEY (`id_centre`) REFERENCES `centre` (`id_centre`),
  ADD CONSTRAINT `fk_id_donneur` FOREIGN KEY (`id_donneur`) REFERENCES `donneur` (`id_donneur`),
  ADD CONSTRAINT `fk_id_receveur` FOREIGN KEY (`id_receveur`) REFERENCES `receveur` (`id_receveur`);

--
-- Contraintes pour la table `participation`
--
ALTER TABLE `participation`
  ADD CONSTRAINT `fk_id_cmp` FOREIGN KEY (`id_cmp`) REFERENCES `campagne` (`id_cmp`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
