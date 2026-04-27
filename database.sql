-- Annuaire GEII - Dump SQL
-- Base de donnees pour l'application Annuaire des Experiences GEII
--
-- Voir GUIDE_DEPLOIEMENT.md pour les instructions d'installation.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `annuaire_geii`
--
CREATE DATABASE IF NOT EXISTS `annuaire_geii` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `annuaire_geii`;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Donnees de la table `admins`
-- IMPORTANT : Creez votre propre compte admin (voir GUIDE_DEPLOIEMENT.md, section 3.4)
--

-- --------------------------------------------------------

--
-- Table structure for table `annuaire_geii`
--

CREATE TABLE `annuaire_geii` (
  `ID_Societe` int NOT NULL,
  `Nom_Societe` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Division_Site` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Activite_Source` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Classification_GEII` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Adresse_1` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Adresse_2` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Code_Postal` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `Ville` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Pays` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'France',
  `Commentaires_GEII` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT 'Demande generale',
  `created_at` datetime NOT NULL,
  `ip` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table `contact_messages` â€” vide (donnees de test supprimees)
--

-- --------------------------------------------------------

--
-- Table structure for table `domaines`
--

CREATE TABLE `domaines` (
  `id` int NOT NULL,
  `nom` varchar(120) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `entreprises`
--

CREATE TABLE `entreprises` (
  `id` int NOT NULL,
  `nom` varchar(120) COLLATE utf8mb4_general_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ville` varchar(120) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_email` varchar(190) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_phone` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `site_web` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `linkedin_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `entreprises`
--

INSERT INTO `entreprises` (`id`, `nom`, `adresse`, `ville`, `contact_email`, `contact_phone`, `site_web`, `linkedin_url`, `created_at`, `updated_at`) VALUES
(1, 'NORDSOFT', NULL, 'VILLENEUVE D\'ASCQ', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(2, 'SNCF VOYAGEURS', NULL, 'HELLEMMES', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(3, 'ENSAIT', NULL, 'ROUBAIX', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(4, 'CMD Engrenages et Reducteurs', NULL, 'CAMBRAI', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(5, 'IRIS INFORMATIQUE', NULL, 'BILLY-BERCLAU', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(6, 'SYNERNET', NULL, 'LILLE', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(7, 'CEGELEC TROYES', NULL, 'TROYES', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(8, 'SYSTECO SARL', NULL, 'VILLENEUVE D\'ASCQ', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(9, 'ABC D\'AIR', NULL, 'VALENTON', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(10, 'ACTEMIUM ARRAS', NULL, 'TILLOY LES MOFFLAINES', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(11, 'CEGELEC Nord Grands Projets', NULL, 'WASQUEHAL', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(12, 'PSA Automobiles SA Site de DOUVRIN', NULL, 'DOUVRIN', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(13, 'FEREST ENERGIES / SYSTEM PLUS', NULL, 'LILLE', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(14, 'CROWN EMBALLAGE France SA', NULL, 'BOULOGNE-SUR-MER', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(15, 'IESEG School of Management', NULL, 'LILLE', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(16, 'EES CLEMESSY Entreprise Dunkerque', NULL, 'COUDEKERQUE', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(17, 'ONTEX', NULL, 'DOURGES', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(18, 'KEOLIS LILLE Metropole', NULL, 'MARCQ EN BAROEUL', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(19, 'SNCF RESEAU INFRALOG INPDC', NULL, 'HELLEMMES', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(20, 'CNRS UMR8520 IEMN', NULL, 'LILLE', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(21, 'SAD Automation', NULL, 'NOYELLES-GODAULT', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(22, 'SADE ENERGY GROUP', NULL, 'VILLENEUVE D\'ASCQ', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(23, 'GROUPE DPS', NULL, 'LILLE', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(24, 'IMPACT', NULL, 'ORLEANS', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(25, 'SE ENERGY', NULL, 'BAILLEUL', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(26, 'COFASI', NULL, 'FRELINGHIEN', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(27, 'SNCF RESEAU', NULL, 'EURALILLE', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(28, 'APAVE NORD-OUEST SAS', NULL, 'MONT-SAINT-AIGNAN', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(29, 'LME', NULL, 'TRITH SAINT LEGER', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(30, 'Eco Technics', NULL, 'VILLENEUVE D\'ASCQ', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(31, 'OPTIMA-Concept', NULL, 'RUITZ', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(32, 'Kamase', NULL, 'ROUBAIX', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(33, 'SNCF INGENIERIE DU MATERIEL', NULL, 'HELLEMMES', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(34, 'INODESIGN', NULL, 'CROIX', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(35, 'IUT A DE LILLE', NULL, 'VILLENEUVE D\'ASCQ', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(36, 'ENGIE SOLUTIONS', NULL, 'VILLENEUVE D\'ASCQ', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(37, 'Goodyear Dunlop', NULL, 'AMIENS', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(38, 'ACTEMIUM MAINTENANCE DUNKERQUE', NULL, 'SAINT POL SUR MER', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(39, 'SMARTVRAC', NULL, 'LILLE', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(40, 'Technord France SARL', NULL, 'VILLENEUVE D\'ASCQ', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(41, 'Mairie de Bailleul', NULL, 'BAILLEUL', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(42, 'AMI Electronique', NULL, 'RONCQ', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(43, 'SGAMI-NORD', NULL, 'LILLE', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(44, 'OSEAN SAS', NULL, 'LE PRADET', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(45, 'Ecotechnics', NULL, 'VILLENEUVE D\'ASCQ', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(46, 'Patisserie Pasquier Vron', NULL, 'VRON', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(47, 'ENGIE INEO', NULL, 'LESQUIN', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(48, 'ALARM CHRISTIAN SECURITE', NULL, 'PONT A MARCQ', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(49, 'DESORMEAUX', NULL, 'LE GRAND-QUEVILLY', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(50, 'ERM ELECTRONIQUE', NULL, 'FLEURBAIX', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(51, 'SAS DV ELECTRONIQUE', NULL, 'HESOIN', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(52, 'RENAULT ELECTRICITY', NULL, 'DOUAI', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(53, 'DPE DESIGN', NULL, 'COURRIERES', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(54, 'Virmicro SAS', NULL, 'LILLE', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(55, 'STRATIFORME INDUSTRIES', NULL, 'BERSEE', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(56, 'EDF', NULL, 'VILLENEUVE D\'ASCQ', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(57, 'AXIMA REFRIGERATION', NULL, 'LESQUIN', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(58, 'ORANGE CYBERDEFENSE', NULL, 'NANTERRE', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(59, 'SYSTELEC NORD', NULL, 'NIVELLE', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(60, 'DUNKERQUE PRODUCTION / MINAKEM', NULL, 'DUNKERQUE', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(61, 'EZS EAST SOLUTIONS', NULL, 'HALLENES-LEZ HAUBOURDIN', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(62, 'SANTERNE Aeronautique et Defense', NULL, 'BEAURAINS', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(63, 'EES CLEMESSY', NULL, 'COUDEKERQUE', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(64, 'Universite de Lille (CRISTAL)', NULL, 'LILLE', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(65, 'CIUCH', NULL, 'TOURCOING', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(66, 'SEGA Macul', NULL, 'WATTRELOS', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(67, 'AXIVITY', NULL, 'AVELIN', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(68, 'Cegelec Nord Tertiaire', NULL, 'WASQUEHAL', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(69, 'AMVALOR LILLE', NULL, 'LILLE', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(70, 'AGS', NULL, 'MERY-SUR OISE', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(71, 'SAVIME', NULL, 'LA CHAPELLE D\'ARMENTIERES', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(72, 'SAS BOUVE-LOCA SERVICE', NULL, 'LA BASSEE', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(73, 'Ideal Fibres & Fabrics COMINES', NULL, 'COMINES', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(74, 'ADE', NULL, 'CAPPELLE EN PEVELE', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(75, '4D Pioneers', NULL, 'VILLENEUVE D\'ASCQ', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(76, 'ALUMINIUM DUNKERQUE', NULL, 'LOON-PLAGE', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(77, 'CENTRE HOSPITALIER DE ROUBAIX', NULL, 'ROUBAIX', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(78, 'ARC FRANCE', NULL, 'ARQUES', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(79, 'CSI CONNECTIC SERVICE INDUSTRIE', NULL, 'BOUC-BEL-AIR', NULL, NULL, '', '', '2026-04-04 11:26:07', '2026-04-04 12:40:46'),
(80, 'FRANCELOG', NULL, 'LINSELLES', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(81, 'EZS EASY SOLUTIONS', NULL, 'HALLENNES-LEZ-HAUBOURDIN', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(82, 'TECHNOMECANIC', NULL, 'SERVINS', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(83, 'DV ELECTRONIQUE', NULL, 'DOUVRIN', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(84, 'TACQUET INDUSTRIES', NULL, 'CARVIN', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(85, 'JEUMONT ELECTRIC', NULL, 'JEUMONT', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(86, 'RATP', NULL, 'SAINT-OUEN', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(87, 'NAVAL GROUP', NULL, 'BREST', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(88, 'ELIS NORD', NULL, 'MARCQ-EN-BAROEUL', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(89, 'CEGELEC NORD GRANDS PROJET', NULL, 'WASQUEHAL', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(90, 'Pouchain', NULL, 'NIEPPE', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(91, '2BDM INGENIERIE', NULL, 'DUNKERQUE', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(92, 'Formation Action Recherche', NULL, 'MONS EN BAROEUL', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(93, 'SICA / ALSTOM', NULL, 'CHALINY / VILLENEUVE D\'ASCQ', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(94, 'SOC JL CORP LCI LOOS', NULL, 'LILLE', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(95, 'CHAUDIERES INDUSTRIELLES', NULL, 'CARVIN', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(96, 'CEGELEC TERTIAIRE', NULL, 'WASQUEHAL', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(97, 'Revor Group N.V.', NULL, 'KUURNE', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(98, 'HIOLLE TECHNOLOGIES', NULL, 'ERQUINGHEM LYS', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(99, 'Alliance Healthcare', NULL, 'LEZENNES', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(100, 'EIFFAGE ENERGIE SYSTEMES', NULL, 'WASQUEHAL', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(101, 'Universite de Lille', NULL, 'VILLENEUVE D\'ASCQ', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(102, 'Technicentre d\'Hellemmes', NULL, 'LILLE', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(103, 'Universite de Lille (IUT)', NULL, 'VILLENEUVE D\'ASCQ', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(104, 'CHATEAU BLANC', NULL, 'MARCQ EN BAROEUL', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(105, 'Universite de Lille (IRCICA)', NULL, 'VILLENEUVE D\'ASCQ', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(106, 'Centrale Lille / IEMN', NULL, 'VILLENEUVE D\'ASCQ', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(107, 'FIVES GROUP', NULL, 'CAPDENAC-GARE', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(108, 'SPIE CITYNETWORKS', NULL, 'LESQUIN', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(109, 'Kiomda', NULL, 'LILLE', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(110, 'CRITT M2A', NULL, 'BRUAY LA BUISSIERE', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(111, 'E-MOBILITY EXPERT', NULL, 'ROOST WARENDIN', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(112, 'Inetum', NULL, 'LILLE', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(113, 'Lucibel SA', NULL, 'BARENTIN', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(114, 'CHUBB FRANCE', NULL, 'WASQUEHAL', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(115, 'Enedis (Lorraine)', NULL, 'VILLERS LES NANCY', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(116, 'SOLARPASS', NULL, 'LILLE', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(117, 'Thales SIX GTS France', NULL, 'GENNEVILLIERS', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(118, 'SITUACTION GEOLOC', NULL, 'MARCQ EN BAROEUL', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(119, 'DUBRULLE SAS', NULL, 'STE MARIE CAPPEL', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(120, 'DV GROUP EEM', NULL, 'TILLOY LEZ CAMBRAI', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(121, 'GMCE', NULL, 'LE PERRAY EN YVELINES', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(122, 'CITC EuraRFID', NULL, 'LILLE', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(123, 'CAMRAIL', NULL, 'DOUALA', NULL, NULL, '', '', '2026-04-04 11:26:08', '2026-04-04 12:40:46'),
(124, 'Inria', NULL, 'Villeneuve-D\'ascq', NULL, '', '', '', '2026-04-04 12:07:02', NULL),
(125, 'HOME ELECTRO', NULL, 'Tourcoing', NULL, '', '', '', '2026-04-04 12:07:02', NULL),
(126, 'TECHNORD', NULL, 'Villeneuve D\'ascq 59491', NULL, '320191170', 'https://www.technord.com/', 'https://www.linkedin.com/company/technord/?originalSubdomain=fr', '2026-04-04 12:07:02', NULL),
(127, 'Omega equipements', NULL, 'Isques', NULL, '', '', '', '2026-04-04 12:07:02', NULL),
(128, 'MCI', NULL, '5 Chemin Du Pave Napoleon, 59260 Lille', NULL, '', '', '', '2026-04-04 12:07:02', NULL),
(129, 'Bureau Veritas', NULL, 'Marcq-En-BarÅ“ul', NULL, '', 'https://www.bureauveritas.fr/?utm_medium=GMB&ved=2ahUKEwji1Y6yyMyTAxVtfKQEHTM1JWIQgU96BAgbEAg', '', '2026-04-04 12:07:02', NULL),
(130, 'Semeru', NULL, 'Lesquin', NULL, '', '', '', '2026-04-04 12:07:02', NULL),
(131, 'Brioche Pasquier', NULL, 'Zone D\'activites Legeres, 227 Allee De La, 62690 Aubigny-En-Artois', NULL, '', '', '', '2026-04-04 12:07:02', NULL),
(132, 'Maxicoffee', NULL, 'Neuville-En-Ferrain', NULL, '328235374', 'https://www.maxicoffee.com/maxicoffee-business-lille-neuville-en-ferrain-f-760.html', '', '2026-04-04 12:07:02', NULL),
(133, 'Maxicoffe', NULL, 'Neuville En Ferrain', NULL, '', '', '', '2026-04-04 12:07:02', NULL),
(134, 'SNCF', NULL, 'Hellemes', NULL, '', '', '', '2026-04-04 12:07:02', NULL),
(135, 'IRCICA', NULL, 'Villeneuve D\'ascq', NULL, '', '', '', '2026-04-04 12:07:02', NULL),
(136, 'RTE', NULL, 'Marq En Baroeul', NULL, '320136600', 'RTE, le gestionnaire du reseau de transport | RTE https://share.google/icKAwokjRuSFhgGcB', 'Rte Lille', '2026-04-04 12:07:02', '2026-04-04 12:40:46'),
(137, 'cnpe gravelines (edf)', NULL, 'Gravelines', NULL, '', 'Edf.fr', 'Edf', '2026-04-04 12:07:02', '2026-04-04 12:40:46'),
(138, 'IEMN', NULL, 'Lille', NULL, '', '', '', '2026-04-04 12:07:02', NULL),
(139, 'NEU-JKF', NULL, 'La Chapelle Armentieres', NULL, '320456575', 'https://neujkf-automation.com/global/fr', 'Neu-Jkf Automation', '2026-04-04 12:07:02', '2026-04-04 12:40:46'),
(140, 'L2EP', NULL, 'Villeneuve D\'ascq', NULL, '', 'L2Ep.univ-Lille.fr', 'L2Ep', '2026-04-04 12:07:02', '2026-04-04 12:40:46'),
(141, 'Chubb Sicli', NULL, 'Wasquehal', NULL, '', '', '', '2026-04-04 12:07:02', NULL),
(142, 'CRIEtAl', NULL, 'Villeneuve-D\'ascq', NULL, '', '', '', '2026-04-04 12:07:02', NULL),
(143, 'Ciuch solutions', NULL, 'Tourcoing', NULL, '', 'https://www.ciuch.com/fr/', '', '2026-04-04 12:07:02', NULL),
(144, 'ASTERM', NULL, 'Rungis', NULL, '0156347020', 'https://www.asterm.com/', 'https://fr.linkedin.com/company/asterm-sas', '2026-04-04 12:07:02', NULL),
(145, 'ENEDIS', NULL, 'Villeneuve D\'ascq', NULL, '', 'www.enedis.fr', '', '2026-04-04 12:07:02', NULL),
(146, 'KONE', NULL, 'Clermont-Ferrand', NULL, '', '', '', '2026-04-04 12:07:02', NULL),
(147, 'Keolis Lille', NULL, 'Lille', NULL, '', '', '', '2026-04-04 12:07:02', NULL),
(148, 'eimi', NULL, 'Douvrin', NULL, '', '', '', '2026-04-04 12:07:02', NULL),
(149, 'Siden-Sian Noreade', NULL, 'Orchies', NULL, '', '', '', '2026-04-04 12:07:03', NULL),
(150, 'AMPERE electricity (RENAULT GROUPE)', NULL, 'Douai', NULL, '', '', '', '2026-04-04 12:07:03', NULL),
(151, 'Etria Manufacturing', NULL, 'Dieppe', NULL, '0235067000', 'Etria Manufacturing France', 'Etria Manufacturing', '2026-04-04 12:07:03', '2026-04-04 12:40:46'),
(152, 'Siem', NULL, 'Lambersart', NULL, '', '', '', '2026-04-04 12:07:03', NULL),
(153, 'CHU de Lille', NULL, 'Lille', NULL, '', '', '', '2026-04-04 12:07:03', NULL),
(154, 'Matra Ã‰lectronique', NULL, 'Venette(60280)', NULL, '', '', '', '2026-04-04 12:07:03', NULL),
(155, 'Stellantis', NULL, 'Charleville-Meziere', NULL, '324364040', 'Aucun', '', '2026-04-04 12:07:03', NULL),
(156, 'Aesc France', NULL, 'Lambres Les Douai', NULL, '', '', '', '2026-04-04 12:07:03', NULL),
(157, 'ATON Ã‰nergies', NULL, 'Lambersart', NULL, '', 'Aton Ã‰nergies.com', '', '2026-04-04 12:07:03', '2026-04-04 12:40:46'),
(158, 'SNCF Voyageur', NULL, 'Hellemmes', NULL, '', '', '', '2026-04-04 12:07:03', NULL),
(159, 'Fives ECL', NULL, '100 Rue Chalant, 59790 Ronchin', NULL, '320887070', '', 'https://www.linkedin.com/company/fives-aluminium/posts/?feedView=all', '2026-04-04 12:07:03', NULL),
(160, 'Philâ€™Energie', NULL, 'Noyelle-Sous-Lens', NULL, '', '', '', '2026-04-04 12:07:03', NULL),
(161, 'Spie', NULL, 'Lesquin', NULL, '', 'https://www.spie.fr', 'Spie Facilities', '2026-04-04 12:07:03', '2026-04-04 12:40:46'),
(162, 'Eiffage Ã‰nergie system service', NULL, 'Marcq En BarÅ“ul', NULL, '', '', '', '2026-04-04 12:07:03', NULL),
(163, 'OPELLA', NULL, 'Lisieux', NULL, '231486610', 'https://www.opella.com/fr', 'https://www.linkedin.com/company/opella-health/', '2026-04-04 12:07:03', NULL),
(164, 'Toyota Boshoku Somain', NULL, '270 Rue Pierre Lescot, 59490 Somain', NULL, '', '', '', '2026-04-04 12:07:03', NULL),
(165, 'Central /iemn', NULL, 'Villeuneuve -Dâ€™ascq', NULL, '', '', '', '2026-04-04 12:07:03', NULL),
(166, 'Rhea Ã‰lectronique', NULL, 'Erquinghem-Lys', NULL, '', '', '', '2026-04-04 12:07:03', NULL),
(167, 'Smith detection', NULL, 'Vitry Sur Seine (94)', NULL, '0155535555', 'https://www.smithsdetection.com', 'Smiths Detection', '2026-04-04 12:07:03', '2026-04-04 12:40:46'),
(168, 'Caterpillar', NULL, 'Grimbergen (Belgique)', NULL, '', '', 'Caterpillar Grimbergen', '2026-04-04 12:07:03', '2026-04-04 12:40:46'),
(169, 'Ecole Centrale de LILLE', NULL, 'Villeneuve D\'ascq', NULL, '', '', '', '2026-04-04 12:07:03', NULL),
(170, 'Glassrepair ent', NULL, 'Mouscron', NULL, '', 'Glassrepair.fr', '', '2026-04-04 12:07:03', '2026-04-04 12:40:46');

-- --------------------------------------------------------

--
-- Table structure for table `experiences`
--

CREATE TABLE `experiences` (
  `id` int NOT NULL,
  `etudiant_nom` varchar(80) COLLATE utf8mb4_general_ci NOT NULL,
  `etudiant_prenom` varchar(80) COLLATE utf8mb4_general_ci NOT NULL,
  `etudiant_email` varchar(190) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `etudiant_linkedin` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email_verification_token` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `entreprise_id` int DEFAULT NULL,
  `entreprise_nom` varchar(120) COLLATE utf8mb4_general_ci NOT NULL,
  `type` enum('Stage','Alternance') COLLATE utf8mb4_general_ci NOT NULL,
  `domaine_id` int DEFAULT NULL,
  `poste` varchar(160) COLLATE utf8mb4_general_ci NOT NULL,
  `domaine` varchar(120) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ville` varchar(120) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `annee` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `tuteur_nom` varchar(120) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `missions` text COLLATE utf8mb4_general_ci,
  `outils` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `technos` text COLLATE utf8mb4_general_ci,
  `duree_mois` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL COMMENT 'Date de suppression (soft delete). NULL = actif, NOT NULL = supprimâ”œÂ® (restaurable pendant 1h)',
  `is_approved` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=Pending, 1=Approved'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `experiences`
--

INSERT INTO `experiences` (`id`, `etudiant_nom`, `etudiant_prenom`, `etudiant_email`, `etudiant_linkedin`, `email_verification_token`, `email_verified_at`, `entreprise_id`, `entreprise_nom`, `type`, `domaine_id`, `poste`, `domaine`, `ville`, `annee`, `tuteur_nom`, `description`, `missions`, `outils`, `technos`, `duree_mois`, `created_at`, `updated_at`, `deleted_at`, `is_approved`) VALUES
(1, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 1, 'NORDSOFT', 'Stage', NULL, 'Technicienne maintenance informatique - Cartographie', '', 'VILLENEUVE D\'ASCQ', '2020', 'GERLACH', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(2, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 2, 'SNCF VOYAGEURS', 'Stage', NULL, 'Developpement test composant EEPROM', '', 'HELLEMMES', '2020', 'BOUZELOC', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(3, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 3, 'ENSAIT', 'Stage', NULL, 'Automatisation d\'un robot 6 axes', '', 'ROUBAIX', '2020', 'DEVAUX', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(4, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 4, 'CMD Engrenages et Reducteurs', 'Stage', NULL, 'Supervision et maintenance de systemes', '', 'CAMBRAI', '2020', 'CHARTIER', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(5, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 5, 'IRIS INFORMATIQUE', 'Stage', NULL, 'Depannage de materiel de type peripheriques informatiques', '', 'BILLY-BERCLAU', '2020', 'EVRARD', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(6, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 6, 'SYNERNET', 'Stage', NULL, 'Assistant Chef d\'equipe', '', 'LILLE', '2020', 'SAMAILLE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(7, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 7, 'CEGELEC TROYES', 'Stage', NULL, 'Realisation de schemas electriques', '', 'TROYES', '2020', 'LECLERCQ', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(8, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 8, 'SYSTECO SARL', 'Stage', NULL, 'Integration solution intrusion/controle d\'acces/supervision IP', '', 'VILLENEUVE D\'ASCQ', '2020', 'VAISSEAU', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(9, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 9, 'ABC D\'AIR', 'Stage', NULL, 'Assistant Chef d\'equipe', '', 'VALENTON', '2020', 'GREISCHEL', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(10, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 10, 'ACTEMIUM ARRAS', 'Stage', NULL, 'Technicienne chantier', '', 'TILLOY LES MOFFLAINES', '2021', 'LEUNIS', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(11, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 11, 'CEGELEC Nord Grands Projets', 'Stage', NULL, 'Technicien d\'etudes en electricite', '', 'WASQUEHAL', '2021', 'AMMILUX', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(12, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 12, 'PSA Automobiles SA Site de DOUVRIN', 'Stage', NULL, 'Optimisation et traitement de l\'obsolescence en Automatisme', '', 'DOUVRIN', '2021', 'GALLIEZ', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(13, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 13, 'FEREST ENERGIES / SYSTEM PLUS', 'Stage', NULL, 'Pre-etude d\'installations electriques / Technicien support', '', 'LILLE', '2021', 'FEREST / DETREZ', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(14, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 14, 'CROWN EMBALLAGE France SA', 'Stage', NULL, 'Technicien de maintenance', '', 'BOULOGNE-SUR-MER', '2021', 'Veronique LOYWYCK', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(15, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 15, 'IESEG School of Management', 'Stage', NULL, 'Developpement d\'une application d\'inscription', '', 'LILLE', '2021', 'KALAYLI', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(16, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 16, 'EES CLEMESSY Entreprise Dunkerque', 'Stage', NULL, 'Analyse et modification de programmes automates', '', 'COUDEKERQUE', '2021', 'GESQUIERE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(17, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 17, 'ONTEX', 'Stage', NULL, 'Anticiper l\'obsolescence des installations', '', 'DOURGES', '2021', 'KWIATEK', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(18, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 18, 'KEOLIS LILLE Metropole', 'Stage', NULL, 'Supervision Reseau Industriel RMS GBS', '', 'MARCQ EN BAROEUL', '2021', 'LAURENT', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(19, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 19, 'SNCF RESEAU INFRALOG INPDC', 'Stage', NULL, 'Secteur Telecom Lille Parcours info voyageur et VideoProtection', '', 'HELLEMMES', '2021', 'GUINCESTRE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(20, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 20, 'CNRS UMR8520 IEMN', 'Stage', NULL, 'Integration et mise en Å“uvre de capteurs dans un simulateur', '', 'LILLE', '2021', 'MULLER', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(21, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 21, 'SAD Automation', 'Stage', NULL, 'Developpement et test d\'une Unite de Methanisation', '', 'NOYELLES-GODAULT', '2021', 'DELIERS', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(22, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 22, 'SADE ENERGY GROUP', 'Stage', NULL, 'Travaux d\'electricite', '', 'VILLENEUVE D\'ASCQ', '2021', 'DILMI', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(23, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 23, 'GROUPE DPS', 'Stage', NULL, 'Administrateur Reseau', '', 'LILLE', '2021', 'VANASTEN', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(24, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 24, 'IMPACT', 'Stage', NULL, 'Developpement logiciel et integration de protocoles', '', 'ORLEANS', '2021', 'JUIGNE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(25, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 25, 'SE ENERGY', 'Stage', NULL, 'Technicien en installation de centrale photovoltaÃ¯que', '', 'BAILLEUL', '2021', 'WOUDENBERG', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(26, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 26, 'COFASI', 'Stage', NULL, 'Developpement Visual C++ Embedded', '', 'FRELINGHIEN', '2021', 'BELLAIS', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(27, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 27, 'SNCF RESEAU', 'Stage', NULL, 'Etude de la mise en Å“uvre d\'un systeme de commande', '', 'EURALILLE', '2021', 'BOURGEADE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(28, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 28, 'APAVE NORD-OUEST SAS', 'Stage', NULL, 'Developpement des prestations en assistance technique', '', 'MONT-SAINT-AIGNAN', '2021', 'FLEURY', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(29, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 29, 'LME', 'Stage', NULL, 'Creation d\'un banc de test', '', 'TRITH SAINT LEGER', '2021', 'FRATELLO', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(30, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 30, 'Eco Technics', 'Stage', NULL, 'Realisation de plans electriques et base de donnees', '', 'VILLENEUVE D\'ASCQ', '2021', 'SERGENT', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(31, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 31, 'OPTIMA-Concept', 'Stage', NULL, 'Realisation d\'un banc de test automatise pour cartes', '', 'RUITZ', '2021', 'HOUSSARD', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(32, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 32, 'Kamase', 'Stage', NULL, 'Technicien HIF', '', 'ROUBAIX', '2021', 'LESAFFRE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(33, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 33, 'SNCF INGENIERIE DU MATERIEL', 'Stage', NULL, 'Retro ingenierie des parties electriques et mecaniques', '', 'HELLEMMES', '2021', 'TOPET', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(34, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 34, 'INODESIGN', 'Stage', NULL, 'Technicien en production de cartes electroniques', '', 'CROIX', '2021', 'THOUVENIN', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(35, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 35, 'IUT A DE LILLE', 'Stage', NULL, 'Mise en place d\'une plateforme de robotique mobile', '', 'VILLENEUVE D\'ASCQ', '2021', 'WAUQUIER', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(36, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 36, 'ENGIE SOLUTIONS', 'Stage', NULL, 'Mise en service d\'une installation sous GTC', '', 'VILLENEUVE D\'ASCQ', '2021', 'DOYER', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(37, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 37, 'Goodyear Dunlop', 'Stage', NULL, 'Fiabilisation machines de controle', '', 'AMIENS', '2021', 'DHEILLY', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(38, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 38, 'ACTEMIUM MAINTENANCE DUNKERQUE', 'Stage', NULL, 'Technicien stagiaire en reparation de cable', '', 'SAINT POL SUR MER', '2021', 'DUMONT', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(39, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 39, 'SMARTVRAC', 'Stage', NULL, 'Conception et realisation d\'un banc de test', '', 'LILLE', '2021', 'CORDIEZ', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(40, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 40, 'Technord France SARL', 'Stage', NULL, 'Supervision et automation agroalimentaire', '', 'VILLENEUVE D\'ASCQ', '2021', 'BOSSU', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(41, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 41, 'Mairie de Bailleul', 'Stage', NULL, 'Developpements, gestion bases de donnees', '', 'BAILLEUL', '2021', 'GAUTIER', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(42, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 42, 'AMI Electronique', 'Stage', NULL, 'Production et Installation des Materiels de mesure', '', 'RONCQ', '2021', 'BOGAERT', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(43, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 43, 'SGAMI-NORD', 'Stage', NULL, 'Mise en place d\'indicateurs de supervision', '', 'LILLE', '2021', 'Hubert Alexandre PLOY', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(44, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 44, 'OSEAN SAS', 'Stage', NULL, 'Validation de cartes electroniques et developpement d\'un banc de test', '', 'LE PRADET', '2021', 'PHILIPPE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(45, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 45, 'Ecotechnics', 'Stage', NULL, 'Technicien junior', '', 'VILLENEUVE D\'ASCQ', '2021', 'NOLLET', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(46, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 34, 'Inodesign', 'Stage', NULL, 'Production de cartes electroniques', '', 'CROIX', '2021', 'THOUVENIN', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(47, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 46, 'Patisserie Pasquier Vron', 'Stage', NULL, 'Optimisation process et chantier', '', 'VRON', '2021', 'PONCHEL', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(48, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 11, 'Cegelec Nord Grands Projets', 'Stage', NULL, 'Apprentissage du metier de technicien d\'etudes en electricite', '', 'WASQUEHAL', '2021', 'AMMEUX', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(49, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 47, 'ENGIE INEO', 'Stage', NULL, 'Etudes d\'eclairement dans les villes', '', 'LESQUIN', '2021', 'DAEL', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(50, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 48, 'ALARM CHRISTIAN SECURITE', 'Stage', NULL, 'Maintenance de systemes de surveillance', '', 'PONT A MARCQ', '2021', 'OMBROUCK', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(51, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 49, 'DESORMEAUX', 'Stage', NULL, 'Travaux d\'electricite', '', 'LE GRAND-QUEVILLY', '2021', 'LECONTE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(52, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 50, 'ERM ELECTRONIQUE', 'Stage', NULL, 'Programmation d\'un automate et terminal Siemens avec TIA', '', 'FLEURBAIX', '2022', 'FEUTRIE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(53, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 51, 'SAS DV ELECTRONIQUE', 'Stage', NULL, 'Realisation de la maintenance preventive et curative des systemes', '', 'HESOIN', '2022', 'VANGENHOVE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(54, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 52, 'RENAULT ELECTRICITY', 'Stage', NULL, 'Plans de progres maintenance centrale des fluides', '', 'DOUAI', '2022', 'TANGRE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(55, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 53, 'DPE DESIGN', 'Stage', NULL, 'Analyse GPS pour tracker', '', 'COURRIERES', '2022', 'VANDEVYVER', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(56, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 54, 'Virmicro SAS', 'Stage', NULL, 'Developpement logiciel pour banc d\'instrumentation', '', 'LILLE', '2022', 'WALTER', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(57, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 55, 'STRATIFORME INDUSTRIES', 'Stage', NULL, 'Fabrication additive et digitalisation d\'unite', '', 'BERSEE', '2022', 'POLESE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(58, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 56, 'EDF', 'Stage', NULL, 'Infrastructure de management Barco XMS', '', 'VILLENEUVE D\'ASCQ', '2022', 'SARRAND', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(59, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 57, 'AXIMA REFRIGERATION', 'Stage', NULL, 'TECHNICIEN BEA', '', 'LESQUIN', '2022', 'DEVRIEZE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(60, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 58, 'ORANGE CYBERDEFENSE', 'Stage', NULL, 'Ingenieur Integration Securite', '', 'NANTERRE', '2022', 'BOMAL', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(61, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 59, 'SYSTELEC NORD', 'Stage', NULL, 'Installation DOMOTIQUE KNX', '', 'NIVELLE', '2022', 'HOGUET', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(62, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 60, 'DUNKERQUE PRODUCTION / MINAKEM', 'Stage', NULL, 'Automatisation d\'utilites (chimie)', '', 'DUNKERQUE', '2022', 'DURIEUX', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(63, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 61, 'EZS EAST SOLUTIONS', 'Stage', NULL, 'Support Charge d\'affaire et automaticien', '', 'HALLENES-LEZ HAUBOURDIN', '2022', 'BULTE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(64, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 62, 'SANTERNE Aeronautique et Defense', 'Stage', NULL, 'Systemes Courants Faibles sites sensibles', '', 'BEAURAINS', '2022', 'QUENNEHEN', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(65, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 32, 'KAMASE', 'Stage', NULL, 'Technicien H/F', '', 'ROUBAIX', '2022', 'LESAFFRE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(66, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 63, 'EES CLEMESSY', 'Stage', NULL, 'Supervision et plans electriques extracteurs', '', 'COUDEKERQUE', '2022', 'GESQUIERE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(67, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 64, 'Universite de Lille (CRISTAL)', 'Stage', NULL, 'Controle d\'une flottille de drones', '', 'LILLE', '2022', 'BORDET', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(68, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 65, 'CIUCH', 'Stage', NULL, 'Standards CIUCH et cahier de tests', '', 'TOURCOING', '2022', 'CIUCH', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(69, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 18, 'Keolis Lille Metropole', 'Stage', NULL, 'Maintenance curative et preventive', '', 'MARCQ EN BAROEUL', '2022', 'FREULON', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(70, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 66, 'SEGA Macul', 'Stage', NULL, 'Technicien Bureau d\'etudes', '', 'WATTRELOS', '2022', 'LEPORCO', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(71, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 67, 'AXIVITY', 'Stage', NULL, 'Developpement de programmes process automates', '', 'AVELIN', '2022', 'GRATEPANCHE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(72, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 68, 'Cegelec Nord Tertiaire', 'Stage', NULL, 'Technicien d\'etudes en electricite du batiment', '', 'WASQUEHAL', '2022', 'AMMEUX', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(73, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 69, 'AMVALOR LILLE', 'Stage', NULL, 'Debitmetre Ã  ultrason', '', 'LILLE', '2022', 'THUMEREL', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(74, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 70, 'AGS', 'Stage', NULL, 'Technicien de maintenance', '', 'MERY-SUR OISE', '2022', 'BOUVARD', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(75, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 71, 'SAVIME', 'Stage', NULL, 'Automatisme systemes de convoyage', '', 'LA CHAPELLE D\'ARMENTIERES', '2022', 'DEFLANDRE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(76, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 72, 'SAS BOUVE-LOCA SERVICE', 'Stage', NULL, 'Informatique industrielle et electronique', '', 'LA BASSEE', '2022', 'BOUVE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(77, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 73, 'Ideal Fibres & Fabrics COMINES', 'Stage', NULL, 'Filmeuse Signode RT', '', 'COMINES', '2022', 'WILMOT', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(78, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 74, 'ADE', 'Stage', NULL, 'Monteur CF', '', 'CAPPELLE EN PEVELE', '2022', 'CAUDERLIER', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(79, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 75, '4D Pioneers', 'Stage', NULL, 'Conception electronique sur imprimante 3D', '', 'VILLENEUVE D\'ASCQ', '2022', 'FLORENTIN', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(80, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 76, 'ALUMINIUM DUNKERQUE', 'Stage', NULL, 'Securisation alimentation electrique 20KV', '', 'LOON-PLAGE', '2023', 'STREBELLE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(81, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 77, 'CENTRE HOSPITALIER DE ROUBAIX', 'Stage', NULL, 'Plan de comptage consommations electriques', '', 'ROUBAIX', '2023', 'DEMORTIER', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(82, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 78, 'ARC FRANCE', 'Stage', NULL, 'Standards depannage electrique / automatisme', '', 'ARQUES', '2023', 'MASCLIN', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(83, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 79, 'CSI CONNECTIC SERVICE INDUSTRIE', 'Stage', NULL, 'Automatismes industriels (energies)', '', 'BOUC-BEL-AIR', '2023', 'EMONIN', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(84, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:07', 32, 'KAMASE', 'Stage', NULL, 'Systemes de protection photovoltaÃ¯que', '', 'HEM', '2023', 'OUJABER', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:07', '2026-04-04 12:46:37', NULL, 1),
(85, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 80, 'FRANCELOG', 'Stage', NULL, 'Essais et tests de cartes electroniques', '', 'LINSELLES', '2023', 'JAFFRE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(86, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 81, 'EZS EASY SOLUTIONS', 'Stage', NULL, 'Modification ligne d\'embouteillage', '', 'HALLENNES-LEZ-HAUBOURDIN', '2023', 'MAIRESSE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(87, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 82, 'TECHNOMECANIC', 'Stage', NULL, 'Automaticien Cableur', '', 'SERVINS', '2023', 'BAJEUX', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(88, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 83, 'DV ELECTRONIQUE', 'Stage', NULL, 'Mesures et depannage sur machines', '', 'DOUVRIN', '2023', 'DECOSTER', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(89, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 84, 'TACQUET INDUSTRIES', 'Stage', NULL, 'Automatisation machine de fraisage', '', 'CARVIN', '2023', 'MASSET', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(90, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 85, 'JEUMONT ELECTRIC', 'Stage', NULL, 'Programmes automates ET200SP', '', 'JEUMONT', '2023', 'AOUZAL', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(91, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 86, 'RATP', 'Stage', NULL, 'Test de redondance TIGROU.RA', '', 'SAINT-OUEN', '2023', 'BOUTELOUP', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(92, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 87, 'NAVAL GROUP', 'Stage', NULL, 'Amelioration banc de simulation', '', 'BREST', '2023', 'SEBERT', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(93, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 88, 'ELIS NORD', 'Stage', NULL, 'Verification installations electriques (Q18)', '', 'MARCQ-EN-BAROEUL', '2023', 'SALINGUE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(94, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 64, 'UNIVERSITE DE LILLE (CRISTAL)', 'Stage', NULL, 'Recherche bras robotique mobile', '', 'VILLENEUVE-D\'ASCQ', '2023', 'DHERBOMEZ', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(95, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 89, 'CEGELEC NORD GRANDS PROJET', 'Stage', NULL, 'Bureau d\'etudes electricite', '', 'WASQUEHAL', '2023', '(Non specifie)', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(96, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 90, 'Pouchain', 'Stage', NULL, 'Electrotechnicien', '', 'NIEPPE', '2024', 'HONORE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(97, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 64, 'Universite de Lille (CRISTAL)', 'Stage', NULL, 'Integration capteur Time Of Flight sur robot', '', 'VILLENEUVE D\'ASCQ', '2024', 'DHERBOMEZ', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(98, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 91, '2BDM INGENIERIE', 'Stage', NULL, 'Gestion energies bungalow autonome', '', 'DUNKERQUE', '2024', 'CROWYN', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(99, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 92, 'Formation Action Recherche', 'Stage', NULL, 'Installation boitiers electroniques', '', 'MONS EN BAROEUL', '2024', 'BEN REBAH', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(100, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 93, 'SICA / ALSTOM', 'Stage', NULL, 'Armoire electrique / Banc de programmation', '', 'CHALINY / VILLENEUVE D\'ASCQ', '2024', 'BRIQUET', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(101, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 94, 'SOC JL CORP LCI LOOS', 'Stage', NULL, 'Ã‰tude automatisme projet industriel', '', 'LILLE', '2024', 'CARLIER', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(102, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 95, 'CHAUDIERES INDUSTRIELLES', 'Stage', NULL, 'Automatisme industriel', '', 'CARVIN', '2024', 'ROELANDT', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(103, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 96, 'CEGELEC TERTIAIRE', 'Stage', NULL, 'Gestion Technique du Batiment CHU Lens', '', 'WASQUEHAL', '2024', 'NOVELLE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(104, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 97, 'Revor Group N.V.', 'Stage', NULL, 'Decouverte de la maintenance', '', 'KUURNE', '2024', 'SALOMEZ', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(105, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 98, 'HIOLLE TECHNOLOGIES', 'Stage', NULL, 'Software systeme automatique casiers', '', 'ERQUINGHEM LYS', '2024', 'DASSONNEVILLE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(106, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 99, 'Alliance Healthcare', 'Stage', NULL, 'Maintenance equipements automatisation', '', 'LEZENNES', '2024', 'MUSMEAUX', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(107, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 100, 'EIFFAGE ENERGIE SYSTEMES', 'Stage', NULL, 'Etude de distribution basse tension', '', 'WASQUEHAL', '2024', 'BRAEMS', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(108, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 101, 'Universite de Lille', 'Stage', NULL, 'Cockpit de conduite / Simulateur CARLA', '', 'VILLENEUVE D\'ASCQ', '2024', 'DHERBOMEZ / VANOVERSCHELDE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(109, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 101, 'Universite de Lille', 'Stage', NULL, 'Realisation de procedures', '', 'VILLENEUVE D\'ASCQ', '2024', 'Vanoverschelde', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(110, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 102, 'Technicentre d\'Hellemmes', 'Stage', NULL, 'Cartes electroniques AFFICHEUR', '', 'LILLE', '2024', 'COLIN', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(111, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 103, 'Universite de Lille (IUT)', 'Stage', NULL, 'Etude du DSP', '', 'VILLENEUVE D\'ASCQ', '2024', 'ALIN', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(112, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 104, 'CHATEAU BLANC', 'Stage', NULL, 'Stage en automatisme', '', 'MARCQ EN BAROEUL', '2024', 'DECROIX', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(113, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 105, 'Universite de Lille (IRCICA)', 'Stage', NULL, 'Recuperation d\'energie objets communicants', '', 'VILLENEUVE D\'ASCQ', '2024', 'KASSI', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(114, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 106, 'Centrale Lille / IEMN', 'Stage', NULL, 'Instrumentation et Automatisation', '', 'VILLENEUVE D\'ASCQ', '2024', 'TALBI', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(115, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 107, 'FIVES GROUP', 'Stage', NULL, 'Anticollision portiques d\'usinage', '', 'CAPDENAC-GARE', '2024', 'FOURNIGUET', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(116, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 108, 'SPIE CITYNETWORKS', 'Stage', NULL, 'Stage conventionne', '', 'LESQUIN', '2024', 'PASZKIEWICZ', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(117, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 109, 'Kiomda', 'Stage', NULL, 'Maintenance compteurs velos/pietons', '', 'LILLE', '2024', 'VERHAEGHE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(118, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 110, 'CRITT M2A', 'Stage', NULL, 'Essais batteries / Developpement application', '', 'BRUAY LA BUISSIERE', '2024', 'FILIPIAK', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(119, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 111, 'E-MOBILITY EXPERT', 'Stage', NULL, 'Modules formation PhotovoltaÃ¯que et IRVE', '', 'ROOST WARENDIN', '2024', 'KOVACS', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(120, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 32, 'Kamase', 'Stage', NULL, 'Coupure et protection photovoltaÃ¯que', '', 'HEM', '2024', 'LEVASSEUR', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(121, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 67, 'Axivity', 'Stage', NULL, 'Programmation systemes automatises', '', 'AVELIN', '2024', 'GRATEPANCHE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(122, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 112, 'Inetum', 'Stage', NULL, 'Tierce maintenance d\'exploitation', '', 'LILLE', '2024', 'MACAIGNE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(123, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 113, 'Lucibel SA', 'Stage', NULL, 'Developpement produit pole R&D', '', 'BARENTIN', '2024', 'MOA', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(124, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 18, 'Keolis Lille metropole', 'Stage', NULL, 'Pupitre alimentation', '', 'TOURCOING', '2024', 'LEFEBVRE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(125, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 114, 'CHUBB FRANCE', 'Stage', NULL, 'Verification systemes securite incendie', '', 'WASQUEHAL', '2024', 'CABY', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(126, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 101, 'UNIVERSITE DE LILLE', 'Stage', NULL, 'Impression 3D et robotique', '', 'VILLENEUVE D\'ASCQ', '2024', 'DUQUESNE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(127, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 3, 'ENSAIT', 'Stage', NULL, 'Automatisation banc de traction', '', 'ROUBAIX', '2024', 'LEGRAND', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(128, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 105, 'UNIVERSITE DE LILLE (IRCICA)', 'Stage', NULL, 'Reveil radio ultra faible consommation', '', 'VILLENEUVE D\'ASCQ', '2024', 'KASSI', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(129, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 115, 'Enedis (Lorraine)', 'Stage', NULL, 'Analyse de donnees et tableaux de bord', '', 'VILLERS LES NANCY', '2024', 'GIRARD', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(130, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 105, 'UNIVERSITE DE LILLE (IRCICA)', 'Stage', NULL, 'Instrumentations hyperfrequences', '', 'VILLENEUVE D\'ASCQ', '2024', 'SEBBACHE', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(131, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 116, 'SOLARPASS', 'Stage', NULL, 'Assistance bureau d\'etude photovoltaÃ¯que', '', 'LILLE', '2024', 'PLOUY', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(132, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 117, 'Thales SIX GTS France', 'Stage', NULL, 'Conception Cartes Electroniques Analogiques', '', 'GENNEVILLIERS', '2024', 'HARAN', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(133, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 102, 'Technicentre d\'Hellemmes', 'Stage', NULL, 'Depannage et reparation cartes electroniques', '', 'LILLE', '2024', 'COLIN / MARANT', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(134, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 118, 'SITUACTION GEOLOC', 'Stage', NULL, 'Charge technique', '', 'MARCQ EN BAROEUL', '2024', 'LAURIER', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(135, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 119, 'DUBRULLE SAS', 'Stage', NULL, 'Schemas electriques ligne de reception', '', 'STE MARIE CAPPEL', '2024', 'DESCHODT', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(136, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 120, 'DV GROUP EEM', 'Stage', NULL, 'Maintenance moteur et reducteur industriel', '', 'TILLOY LEZ CAMBRAI', '2024', 'MALECKI', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(137, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 121, 'GMCE', 'Stage', NULL, 'Production electronique', '', 'LE PERRAY EN YVELINES', '2024', 'GONCALVES ROSA', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(138, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 122, 'CITC EuraRFID', 'Stage', NULL, 'Fabrication d\'une carte electronique', '', 'LILLE', '2024', 'COUTEL', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(139, '', 'Pas De Donnees', NULL, '', NULL, '2026-04-04 11:26:08', 123, 'CAMRAIL', 'Stage', NULL, 'Efficacite energetique', '', 'DOUALA', '2024', 'BOUNDA BOUNDA', NULL, NULL, '', NULL, NULL, '2026-04-04 11:26:08', '2026-04-04 12:46:37', NULL, 1),
(140, 'BOUNKHILA', 'Adam', 'Adam.bounkhila.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:02', 124, 'Inria', 'Stage', NULL, 'Stagiaire', 'Ã‰lectronique, R&D, Informatique, Telecommunications', 'Villeneuve-D\'ascq', '2025-2026', NULL, NULL, 'Comparaison et optimisation energetique des modules lora et ble sur esp32.', 'C/C++, Microcontroleurs , Outil De Cao/Dao', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:46:37', NULL, 1),
(141, 'DUBOIS', 'Alexis', 'alexis.dubois.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:02', 65, 'CIUCH', 'Stage', NULL, 'Stagiaire en automatisme', 'Automatisme', 'Tourcoing', '2025', NULL, NULL, 'Realisation dâ€™un banc de test ; configuration et test de scanner.', 'Automates', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:40:45', NULL, 1),
(142, 'BANOWICZ', 'Alhan', 'alhan.banowicz.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:02', 125, 'HOME ELECTRO', 'Stage', NULL, 'Technicien', 'Ã‰lectronique', 'Tourcoing', '2025-2026', NULL, NULL, 'Installation d\'OS, maintenance de pc portable, reparation, prepation de colis destine Ã  expedition.', 'Ils Possedent Une Cle Usb Pour Teste Les Pc Portable', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:46:37', NULL, 1),
(143, 'SOUMAHORO', 'Ali', 'ali.soumahoro.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:02', 57, 'Axima Refrigeration', 'Alternance', NULL, 'Technicien Bureau dâ€™etudes', 'Ã‰lectricite Et Automatisme', 'Lesquin', '2025-2026', NULL, NULL, 'Assistance au bureau dâ€™etudes.', 'Automates', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:40:45', NULL, 1),
(144, 'POCCHIO', 'Anna', 'anna.pocchio.etu@univ-lille.fr', 'Anna Pocchio', NULL, '2026-04-04 12:07:02', 126, 'TECHNORD', 'Alternance', NULL, 'Dessinatrice projeteuse', 'Ã‰nergie', 'Villeneuve D\'ascq 59491', '2025-2027', NULL, NULL, 'Conception (schemas), tests (des armoires en atelier), verifications (sur chantier).', 'Eplan, Propanel', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:46:37', NULL, 1),
(145, 'HOFFET', 'Arthur', 'arthur.hoffet.etu@univ-lille.fr', 'Linkedin.com/In/Arthur-Hoffet-507A2A2Ba', NULL, '2026-04-04 12:07:02', 110, 'CRITT M2A', 'Stage', NULL, 'technicien d\'essai electrique', 'Automobile', 'Bruay La Buissiere', '2024', NULL, NULL, 'Redaction de documents techniques, caracterisation d\'un cycleur de cellules batterie.', '', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:40:45', NULL, 1),
(146, 'POMMART', 'Auxence', 'auxence.pommart-vinet.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:02', 127, 'Omega equipements', 'Stage', NULL, 'Tableautier tertiaire et industriel', 'Ã‰lectronique', 'Isques', '2025', NULL, NULL, 'Cablage.', '', NULL, NULL, '2026-04-04 12:07:02', NULL, NULL, 1),
(147, 'DEPLA', 'Axel', 'axel.depla.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:02', 128, 'MCI', 'Alternance', NULL, 'Chef de Projet Automatisme', 'Automatisme', '5 Chemin Du Pave Napoleon, 59260 Lille', '2024-2026', NULL, NULL, 'Programmation automates industriels et CVC | Vue supervision | Analyse fonctionnelle | Mise en service | Suivi chantier | Mise en reseau...', 'Automates', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:40:45', NULL, 1),
(148, 'ANTOINE', 'Axel', 'axel.antoine.etu@univ-lille.fR', 'Ras', NULL, '2026-04-04 12:07:02', 129, 'Bureau Veritas', 'Alternance', NULL, 'Inspecteur electricite', 'Tout', 'Marcq-En-BarÅ“ul', '2025-2027', NULL, NULL, 'Inspecter lâ€™installation electrique des clients.', 'Logiciel Opale', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:46:37', NULL, 1),
(149, 'HAUTECOEUR', 'Baptiste', 'baptiste.hautecoeur.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:02', 130, 'Semeru', 'Alternance', NULL, 'Dessinateurs', 'CAO / Dessin Industriel', 'Lesquin', '2025-2027', NULL, NULL, 'Creer et modifier des schema electrique.', 'Autocad', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:46:37', NULL, 1),
(150, 'BARROIS', 'Benoit', 'benoit.barrois.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:02', 131, 'Brioche Pasquier', 'Alternance', NULL, 'automaticien', 'Agroalimentaire', 'Zone D\'activites Legeres, 227 Allee De La, 62690 Aubigny-En-Artois', '2025-2027', NULL, NULL, 'Refection d\'IHM.', '', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:17:55', NULL, 1),
(151, 'DRUESNE', 'Cyprien', 'cyprien.druesne.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:02', 132, 'Maxicoffee', 'Stage', NULL, 'Technicien Atelier', 'Maintenance Industrielle', 'Neuville-En-Ferrain', '2025-2026', NULL, NULL, 'Repararation et installation de machines.', 'Automates , Base De Donnees Sql, Nosql', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:46:37', NULL, 1),
(152, 'DERNONCOURT', 'Elie', 'elie.dernoncourt.etu@univ-lille.fR', '', NULL, '2026-04-04 12:07:02', 133, 'Maxicoffe', 'Alternance', NULL, 'Technicien en atelier', 'Industrie Manufacturiere', 'Neuville En Ferrain', '2025-2027', NULL, NULL, 'Maintenance et mise en service de machines.', 'Automates , Microcontroleurs , Base De Donnees Sql, Nosql', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:46:37', NULL, 1),
(153, 'DILLER', 'Emeric', 'Emeric.diller.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:02', 134, 'SNCF', 'Stage', NULL, 'Agent electronicien', 'Ã‰lectronique', 'Hellemes', '2025-2026', NULL, NULL, 'Reparation de PRM au sein de la travee afficheurs.', '', NULL, NULL, '2026-04-04 12:07:02', NULL, NULL, 1),
(154, 'SPETEBROODT', 'Evan', 'evan.spetebroodt.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:02', 135, 'IRCICA', 'Stage', NULL, 'Ingenieur', 'Ã‰lectronique, R&D, Informatique, Telecommunications', 'Villeneuve D\'ascq', '2025-2026', NULL, NULL, 'Conception d\'un noeud de capteur multi-physique utilisant une communication Zigbee.', 'Python, C/C++, Microcontroleurs , Outil De Cao/Dao', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:46:37', NULL, 1),
(155, 'CAPELLE', 'Fannie', 'fannie.capelle.etu@univ-lille.fr', 'Fannie Capelle', NULL, '2026-04-04 12:07:02', 136, 'RTE', 'Alternance', NULL, 'Assistante charge d\'etudes en direction ingenieurie', 'Ã‰nergie', 'Marq En Baroeul', '2025-2027', NULL, NULL, 'Ã‰tude de raccordement cliant et Redaction de cahier des charges.', '', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:55:53', NULL, 1),
(156, 'BACHELET', 'Gabin', 'gabin.bachelet.etu@univ-lille.fr', 'Gabin Bachelet', NULL, '2026-04-04 12:07:02', 137, 'cnpe gravelines (edf)', 'Alternance', NULL, 'charge affaire', 'Production Ã‰lectrique', 'Gravelines', '2025_2027', NULL, NULL, 'Maintenance prev et corrective, preparation dossier intervention technicien.', 'Automates', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:40:45', NULL, 1),
(157, 'ALAHOM', 'IsmaÃ¯l', 'ismail.alahom.etu@univ-lille.fr', 'https://www.linkedin.com/in/ismail-alahom', NULL, '2026-04-04 12:07:02', 138, 'IEMN', 'Stage', NULL, 'Assistant chercheur', 'Ã‰lectronique, R&D, Telecommunications', 'Lille', '2025-2026', NULL, NULL, 'Conception d\'un PCB HF avec adaptation d\'impedance pour un AFM.', 'Ads Keysight', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:46:37', NULL, 1),
(158, 'ROUSSELLE', 'Joseph', 'joseph.rousselle.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:02', 56, 'EDF', 'Alternance', NULL, 'Charge d\'affaires electricite haute tension en arret de tranche', 'Ã‰nergie', 'Gravelines', '2025-2026', NULL, NULL, 'Preparation et suivie des activites de maintenance en electricite haute tension durant les arret de tranche sur l\'ensemble du site que ce soit avec  des agents EDF ou des prestataires.', 'Outil Du Sdin Edf Systeme D\'information Du Nucleaire', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:46:37', NULL, 1),
(159, 'NEWITECKI', 'Joseph', 'joseph.newitecki.etu@univ-lille.fr', 'Joseph Newitecki', NULL, '2026-04-04 12:07:02', 139, 'NEU-JKF', 'Alternance', NULL, 'Developpeur systemes automatises', 'Tertiaire, Automatisme, Informatique, Reseaux, Ã‰nergie', 'La Chapelle Armentieres', '2025-2027', NULL, NULL, 'Supervision gestion technique batiment / Programmation gestion eclairage, stores et CVC / cloisonnement batiment / ...', 'C/C++, Automates , Reseaux , Outil De Cao/Dao , Supervision Pcvue', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:46:37', NULL, 1),
(160, 'MOMNOUGUI', 'Joseph-Clint', 'joseph-clint.momnougui.etu@univ-lille.fr', 'Joseph-Clint Momnougui', NULL, '2026-04-04 12:07:02', 140, 'L2EP', 'Stage', NULL, 'Technicien concepteur stagiaire', 'Ã‰nergie', 'Villeneuve D\'ascq', '2025-2026', NULL, NULL, 'Aide Ã  la conception d\'un onduleur de puissance.', 'Matlab/Simulink, Outil De Cao/Dao', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:46:37', NULL, 1),
(161, 'AHDAD', 'Laifa', 'laifa.ahdad.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:02', 141, 'Chubb Sicli', 'Stage', NULL, 'Technicien de maintenance des SSI', 'Maintenance', 'Wasquehal', '2025-2026', NULL, NULL, 'Assurer la maintenance des systemes de securite incendie.', 'Multimetre', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:23:40', NULL, 1),
(162, 'JAGODIC', 'Lazar', 'lazar.jagodic.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:02', 142, 'CRIEtAl', 'Stage', NULL, 'Stagiaire', 'Automatisme', 'Villeneuve-D\'ascq', '2025-2026', NULL, NULL, 'Instrumentation.', 'Python, C/C++, Microcontroleurs', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:40:46', NULL, 1),
(163, 'GACHET', 'Leo', 'leo.gachet.etu@univ-lille.fr', 'https://www.linkedin.com/in/l%C3%A9o-gachet-352653359?utm_source=share_via&utm_content=profile&utm_medium=member_ios', NULL, '2026-04-04 12:07:02', 143, 'Ciuch solutions', 'Stage', NULL, 'Informaticien : Integration et gestion dâ€™accessoires electromecanique pour automate proprietaire', 'Automatisme, Ã‰lectronique, R&D, Informatique', 'Tourcoing', '2025-2026', NULL, NULL, 'Realiser un driver en C++ pour qu\'un microcontroleur pilote un afficheur LED avec des trames modbus RTU.', 'C/C++, Microcontroleurs , Reseaux , Git, Mplab, Modbus Rtu.', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:46:37', NULL, 1),
(164, 'FOI', 'LoÃ¯c', 'loic.foi.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:02', 144, 'ASTERM', 'Stage', NULL, 'Assistant technicien de maintenance', 'Automatisme', 'Rungis', '2025', NULL, NULL, 'Installation de sondes LoRa.', 'Aree Building Et Milesight Toolbox', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:46:37', NULL, 1),
(165, 'PILLOY', 'Louis', 'louis.pilloy.etu@univ-lille.fr', 'Pilloy Louis', NULL, '2026-04-04 12:07:02', 56, 'EDF', 'Alternance', NULL, 'technicien de maintenance', 'Ã‰nergie', 'Gravelines', '2024-2026', NULL, NULL, 'Maintenance des equipemetnts de protection du site.', 'Q Electrotech', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:46:37', NULL, 1),
(166, 'DECLERCQ', 'Lucas', 'Lucas.declercq.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:02', 145, 'ENEDIS', 'Alternance', NULL, 'Charge de conception', 'Ã‰nergie', 'Villeneuve D\'ascq', '2025-2027', NULL, NULL, 'Realisation des solutions techniques pour les raccordements electrique des professionnels et particuliers.', 'Outil De Cao/Dao', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:46:37', NULL, 1),
(167, 'DONNAY', 'Mathilde', 'Mathilde.donnay.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:02', 146, 'KONE', 'Alternance', NULL, 'Technicienne de maintenance', 'Ascenseur Et Portes Automatique', 'Clermont-Ferrand', '2024-2026', NULL, NULL, 'Visite de maintenance.', '', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:17:55', NULL, 1),
(168, 'ROBERT', 'Mathis', 'mathis.robert.etu@univ-lille.fr', 'https://www.linkedin.com/in/mathis-robert-857991257/', NULL, '2026-04-04 12:07:02', 147, 'Keolis Lille', 'Stage', NULL, 'Technicien', 'Ã‰lectronique', 'Lille', '2024-2025', NULL, NULL, 'Realiser un banc de test pour les pieces electroniques de metro.', 'Microcontroleurs , Outil De Cao/Dao', NULL, NULL, '2026-04-04 12:07:02', '2026-04-04 12:46:37', NULL, 1),
(169, 'VILELA', 'Mattheo', 'mattheo.vilela.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:03', 148, 'eimi', 'Alternance', NULL, 'assistant responsable dâ€™affaires', 'Ã‰nergie', 'Douvrin', '2025-2026', NULL, NULL, 'Gestion dâ€™affaires, terrain.', 'Python, C/C++, Seeelectrical', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:46:37', NULL, 1),
(170, 'JACQUOT', 'Matthis', 'matthis.jacquot.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:03', 149, 'Siden-Sian Noreade', 'Alternance', NULL, 'Apprenti electricien', 'Collecte Et Traitement Des Eaux Usees', 'Orchies', '2025-2027', NULL, NULL, 'Realisation dâ€™armoire electrique.', 'Schemelec', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:23:40', NULL, 1);
INSERT INTO `experiences` (`id`, `etudiant_nom`, `etudiant_prenom`, `etudiant_email`, `etudiant_linkedin`, `email_verification_token`, `email_verified_at`, `entreprise_id`, `entreprise_nom`, `type`, `domaine_id`, `poste`, `domaine`, `ville`, `annee`, `tuteur_nom`, `description`, `missions`, `outils`, `technos`, `duree_mois`, `created_at`, `updated_at`, `deleted_at`, `is_approved`) VALUES
(171, 'BERCU', 'Maxime', 'maxime.bercu.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:03', 150, 'AMPERE electricity (RENAULT GROUPE)', 'Alternance', NULL, 'Assistant chef de maintenance', 'Automobile', 'Douai', '2025-2027', NULL, NULL, '- Suivi de consommation energetique de lignes de production \n- maintenance.', 'Automates , Reseaux', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:40:46', NULL, 1),
(172, 'MEBTOUL', 'Mehdi', 'mehdi.mebtoul', 'Mehdi Mebtoul', NULL, '2026-04-04 12:07:03', 151, 'Etria Manufacturing', 'Stage', NULL, 'Assistant technicien', 'Industrie Manufacturiere', 'Dieppe', '2025', NULL, NULL, 'Conception dâ€™un systeme de dosage ( cablages , programmation,â€¦).', 'Automates , Gx Works Pour Le Programme', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:46:37', NULL, 1),
(173, 'BLONDIAUX', 'Nathan', 'nathan.blonddd59@gmail.com', 'Nathan Blondiaux', NULL, '2026-04-04 12:07:03', 152, 'Siem', 'Stage', NULL, 'Automaticien', 'Automatisme', 'Lambersart', '2025-2025', NULL, NULL, 'Realisation de la partie automatisme dâ€™un banc de test de cuve Ã  ultrasons.', 'Automates', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:40:46', NULL, 1),
(174, 'TRAN', 'Nguyen Hung', 'nguyen-hung.tran.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:03', 138, 'IEMN', 'Stage', NULL, 'Technicien electronique', 'Ã‰lectronique', 'Villeneuve D\'ascq', '2025-2026', NULL, NULL, 'Realisation carte electronique+ + programmation embarque.', 'C/C++, Altium Designer/Kicad, Microcontroleurs', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:46:37', NULL, 1),
(175, 'DOSSAVI-YOVO', 'Onesime', 'onesime.dossavi-yovo.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:03', 136, 'RTE', 'Alternance', NULL, 'Technicien de Maintenance', 'Ã‰nergie', 'Amiens', '2025-2027', NULL, NULL, 'Maintenance des equipements HTB.', 'Automates', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:40:46', NULL, 1),
(176, 'TAKWI', 'Patrick', 'patrik.takwi-mabo.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:03', 134, 'SNCF', 'Stage', NULL, 'Reparation de robot moteur', '', 'Helleme', '2025-2026', NULL, NULL, 'Reparation de robot moteur.', '', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:46:37', NULL, 1),
(177, 'LEVIEL', 'Paul', 'paul.leviel.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:03', 153, 'CHU de Lille', 'Alternance', NULL, 'Technicien Biomedical', 'Biomedical', 'Lille', '2025-2027', NULL, NULL, 'â€¢	Controler et suivre lâ€™etat de fonctionnement des equipements et des installations.\nâ€¢	Realiser des interventions curatives\nâ€¢	Planifier et realiser les maintenances preventives des equipements.\nâ€¢	Assurer le suivi des operations effectuees par les fournisseurs ou les prestataires de maintenance.\nâ€¢	Mettre Ã  jour et suivre lâ€™inventaire, saisir dans la GMAO  les interventions de maintenance internes et externes.\n\nProjet propre : Etudier la faisabilite de reinternaliser la maintenance de certaines gammes dâ€™equipements (Analyser selon le point de vue Â« disponibilite des equipement Â»s et selon le point de vue Â« economique Â»).', 'Logiciel De Gmao : Assetplus', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:46:37', NULL, 1),
(178, 'HAYE', 'Pierre', 'pierre.haye.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:03', 154, 'Matra Ã‰lectronique', 'Alternance', NULL, 'Operateur de tests', 'Ã‰lectronique', 'Venette(60280)', '2025-2027', NULL, NULL, 'Teste le fonctionnement de cartes electroniques principalement pour le domaine militaire.', '', NULL, NULL, '2026-04-04 12:07:03', NULL, NULL, 1),
(179, 'SEILER', 'Pierre', 'pierre.seiler.etu@univ-lille.fr', 'Aucun', NULL, '2026-04-04 12:07:03', 155, 'Stellantis', 'Stage', NULL, 'Stagiaire en technicien de maintenance', 'Automobile', 'Charleville-Meziere', '2025-2026', NULL, NULL, 'Amelioration des lignes de productions, de la securite et formations robotiques.', 'Python, Automates', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:40:46', NULL, 1),
(180, 'PLANCKEEL', 'Remi', 'remi.planckeel.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:03', 156, 'Aesc France', 'Alternance', NULL, 'Technicien de Maintenance', 'Automobile', 'Lambres Les Douai', '2025-2027', NULL, NULL, 'Maintenance.', 'Automates', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:40:46', NULL, 1),
(181, 'DUBUS', 'Rhaven', 'rhaven.dubus.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:03', 135, 'IRCICA', 'Stage', NULL, 'Recherche', 'Ã‰lectronique', 'Villeneuve-D\'ascq', '2025-2026', NULL, NULL, 'Recherche, etude theorique, programmation.', 'Python, C/C++, Microcontroleurs', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:40:46', NULL, 1),
(182, 'GONÃ‡ALVES', 'Ricardo', 'ricardo.ferreira-goncalves.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:03', 157, 'ATON Ã‰nergies', 'Alternance', NULL, 'Technicien de pose panneaux photovoltaÃ¯ques', 'Ã‰nergie', 'Lambersart', '2025-2027', NULL, NULL, 'Pose de panneaux photovoltaÃ¯ques ainsi que leur raccordement au reseaux EDF.', '', NULL, NULL, '2026-04-04 12:07:03', NULL, NULL, 1),
(183, 'CARLE', 'Romain', 'romain.carle.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:03', 81, 'EZS EASY SOLUTIONS', 'Stage', NULL, 'electricien', 'Agroalimentaire', 'Hallennes-Lez-Haubourdin', '2025', NULL, NULL, 'Realisation dâ€™une armoire electrique.', '', NULL, NULL, '2026-04-04 12:07:03', NULL, NULL, 1),
(184, 'CABY', 'Romain', 'romain.caby.etu@univ-lille.fr', 'Romain Caby', NULL, '2026-04-04 12:07:03', 158, 'SNCF Voyageur', 'Alternance', NULL, 'Operateur de maintenance electronique', 'Ferroviaire', 'Hellemmes', '2025-2027', NULL, NULL, 'Reparer les afficheur de tgv.', '', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:40:46', NULL, 1),
(185, 'MÃ‰LIQUE', 'Romain', 'romain.melique.etu@univ-lille.fR', '', NULL, '2026-04-04 12:07:03', 136, 'RTE', 'Alternance', NULL, 'Technicien de maintenance', 'Ã‰nergie', 'Valenciennes', '2025-2027', NULL, NULL, 'Maintenance controle commande du reseau electrique.', 'Automates', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:40:46', NULL, 1),
(186, 'ROSSIGNOL', 'Romane', 'romane.rossignol.etu@univ-lille.fr', 'https://www.linkedin.com/in/rossignol-romane/', NULL, '2026-04-04 12:07:03', 159, 'Fives ECL', 'Alternance', NULL, 'Technicien Essais', 'Industrie Manufacturiere', '100 Rue Chalant, 59790 Ronchin', '2025-2027', NULL, NULL, 'Realiser des essais hors/sous tension sur machine, redaction de compte rendu d\'essais, amelioration de coffrets electrique, configuration d\'automate/variateur.', 'Automates , Reseaux', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:40:46', NULL, 1),
(187, 'VALVERDE', 'Ruben', 'ruben.valverde.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:03', 160, 'Philâ€™Energie', 'Alternance', NULL, 'Apprenti Electricien', 'Cvc', 'Noyelle-Sous-Lens', '2026', NULL, NULL, 'Realiser et Installer des coffrets electriques.', 'Automates', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:40:46', NULL, 1),
(188, 'DEBAVELAERE', 'Samuel', 'samuel.debavelaere.etu@univ-lille.fr', 'Samuel Debavelaere', NULL, '2026-04-04 12:07:03', 161, 'Spie', 'Stage', NULL, 'Ingenieur dâ€™affaireS & Methodes', 'La Maintenance Et L\'exploitation Technique Des Batiments Ainsi Que Le Facility Management', 'Lesquin', '2025', NULL, NULL, 'Rapport dematerialise (Kizzeo)\nChiffrage \nDocument technique et plan.', 'Kizeo', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:40:46', NULL, 1),
(189, 'BARETTE', 'Stanislas', 'Stanislas.barette.etu', '', NULL, '2026-04-04 12:07:03', 162, 'Eiffage Ã‰nergie system service', 'Stage', NULL, 'Assistant pole methode', 'Btp', 'Marcq En BarÅ“ul', '2025-2026', NULL, NULL, 'Recevoir et verifier la conformite des prises en charge techniques du nouveau contrat national avec la SNCF Mise en qualite et consolidation des prises en charge recues Remplissage des fichiers de suivi.', 'Excel ,Power Bi ,Kizeo', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:46:37', NULL, 1),
(190, 'ANDRIANARIVO', 'Tahiana', 'tahiana.andrianarivo.etu@univ-lille.fr', 'https://www.linkedin.com/in/tahiana-andrianarivo', NULL, '2026-04-04 12:07:03', 163, 'OPELLA', 'Alternance', NULL, 'Technicien de maintenance', 'Pharmaceutique', 'Lisieux', '2025-2027', NULL, NULL, 'Maintenance des utilitees.', 'Automates , Base De Donnees Sql, Nosql', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:46:37', NULL, 1),
(191, 'CALONNE BRUNEEL', 'Thelio', 'thelio.calonne-bruneel.etu@univ-lille.fr', 'https://www.linkedin.com/in/calonne-bruneel-th%C3%A9lio-000223349?utm_source=share_via&utm_content=profile&utm_medium=member_ios', NULL, '2026-04-04 12:07:03', 164, 'Toyota Boshoku Somain', 'Alternance', NULL, 'Alternant automaticien de maintenance', 'Automatisme', '270 Rue Pierre Lescot, 59490 Somain', '2025-2026', NULL, NULL, 'Ã‰laboration et mise en service d\'un systeme de convoyage de pieces en aciers | Missions et prises en mains sur differents automates (Siemens, Mitsubishi..) | Depannage.', 'Automates', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:40:46', NULL, 1),
(192, 'FERNANDEZ GARCIA', 'Theo', 'theo.fernandez-garcia.etu@univ-lille.fr', 'Theo Fernandez Garcia', NULL, '2026-04-04 12:07:03', 165, 'Central /iemn', 'Stage', NULL, 'Stagiaire recherche et developpement', 'Ã‰lectronique, R&D', 'Villeuneuve -Dâ€™ascq', '2025-2026', NULL, NULL, 'Realisation mini dâ€™un banc de teste acoustique sans retour dâ€™onde.', 'Matlab/Simulink, Python, C/C++, Microcontroleurs , Outil De Cao/Dao', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:46:37', NULL, 1),
(193, 'VANLIERDE', 'Theo', 'theo.vanlierde.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:03', 166, 'Rhea Ã‰lectronique', 'Stage', NULL, 'Developpement de programmes de test sur produits automates finis', 'Automatisme, Ã‰lectronique, Ã‰nergie', 'Erquinghem-Lys', '2025-2026', NULL, NULL, 'Creation de programmes de test automatiques pour automate Siemens et B&R.', 'Python, Automates , Tia Portal', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:46:37', NULL, 1),
(194, 'DUPIN', 'Thibaut', 'thibaut.dupin.etu@univ-lille.fr', 'Dupin Thibaut', NULL, '2026-04-04 12:07:03', 167, 'Smith detection', 'Alternance', NULL, 'Apprenti responsable de montage', 'Ã‰nergie', 'Vitry Sur Seine (94)', '2025-2027', NULL, NULL, 'Montage technique sur nos produits.', 'Automates', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:40:46', NULL, 1),
(195, 'GELLY', 'Thomas', 'thomas.gelly.etu', '', NULL, '2026-04-04 12:07:03', 90, 'POUCHAIN', 'Stage', NULL, 'Technicien en automatisme', 'Automatisme', 'Douai', '2025-2026', NULL, NULL, 'Apport d\'aide aux autres techniciens et ingenieurs dans un bureau d\'etude en automatisme.', 'Automates , Pcs7 / Tia Portal', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:46:37', NULL, 1),
(196, 'DELHAYE', 'Thomas', 'thomas.delhaye2.etu@univ-lille.cr', '', NULL, '2026-04-04 12:07:03', 136, 'RTE', 'Alternance', NULL, 'Assistant Etudes et Projet', 'Ã‰nergie', 'Marcq-En-Baroeul', '2024-2025', NULL, NULL, 'Pilotage de projet, verifications techniques.', '', NULL, NULL, '2026-04-04 12:07:03', NULL, NULL, 1),
(197, 'TIJDGAT', 'Tom', 'Tom.tijdgat.etu@univ-lille.fr', 'Tom Tijdgat', NULL, '2026-04-04 12:07:03', 168, 'Caterpillar', 'Stage', NULL, 'Support Newtwork solutions & engineering team', 'Logistique', 'Grimbergen (Belgique)', '2024-2025', NULL, NULL, '', '', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:40:46', NULL, 1),
(198, 'JACQUEMART', 'Valentin', 'valentin.jacquemart.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:03', 169, 'Ecole Centrale de LILLE', 'Stage', NULL, 'Conception de banc d\'essai de tube acoustique miniature', 'Ã‰ducation Superieur : Ã‰tablissement Public National Ã€ Caractere Scientifique Culturel Et Professionnel', 'Villeneuve D\'ascq', '2025-2026', NULL, NULL, 'Realiser un tube acoustique et essayer d\'en supprimer les ondes reflechies et parasite venant deteriore la prise de mesure.', 'C/C++, Altium Designer/Kicad, Microcontroleurs , Outil De Cao/Dao', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:46:37', NULL, 1),
(199, 'EL HADAD', 'Yasser', 'yasser.el-hadad.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:03', 132, 'MaxiCoffee', 'Alternance', NULL, 'Technicien de maintenance', 'Automatisme', '7 Rue Du Vert Bois, 59960 Neuville-En-Ferrain', '2025-2026', NULL, NULL, 'Technicien de maintenance en atelier \nReconditionnement de machine\nInstallation de machine\nEncadrement et securite \nGestion Stock / Monetique.', '', NULL, NULL, '2026-04-04 12:07:03', '2026-04-04 12:55:53', NULL, 1),
(200, 'ERTAM', 'Ylies', 'ylies.ertam.etu@univ-lille.fr', '', NULL, '2026-04-04 12:07:03', 170, 'Glassrepair ent', 'Stage', NULL, 'Techniciens maintenance', 'Ã‰lectronique', 'Mouscron', '2025-2026', NULL, NULL, 'Maintenance et Sav d\'appareils electronique.', '', NULL, NULL, '2026-04-04 12:07:03', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `logs_audit`
--

CREATE TABLE `logs_audit` (
  `id` int NOT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_id` int DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table `logs_audit` â€” vide (logs de developpement supprimes)
--

-- --------------------------------------------------------

--
-- Table structure for table `stats_visits`
--

CREATE TABLE `stats_visits` (
  `id` int NOT NULL,
  `page_url` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `visit_date` date NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table `stats_visits` â€” vide (donnees de visites supprimees)
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `annuaire_geii`
--
ALTER TABLE `annuaire_geii`
  ADD PRIMARY KEY (`ID_Societe`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `domaines`
--
ALTER TABLE `domaines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `entreprises`
--
ALTER TABLE `entreprises`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `experiences`
--
ALTER TABLE `experiences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_experience_entreprise` (`entreprise_id`),
  ADD KEY `fk_experience_domaine` (`domaine_id`),
  ADD KEY `idx_email_token` (`email_verification_token`),
  ADD KEY `idx_deleted_at` (`deleted_at`);

--
-- Indexes for table `logs_audit`
--
ALTER TABLE `logs_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_logs_admin` (`admin_id`);

--
-- Indexes for table `stats_visits`
--
ALTER TABLE `stats_visits`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `annuaire_geii`
--
ALTER TABLE `annuaire_geii`
  MODIFY `ID_Societe` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `domaines`
--
ALTER TABLE `domaines`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `entreprises`
--
ALTER TABLE `entreprises`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT for table `experiences`
--
ALTER TABLE `experiences`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT for table `logs_audit`
--
ALTER TABLE `logs_audit`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stats_visits`
--
ALTER TABLE `stats_visits`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `experiences`
--
ALTER TABLE `experiences`
  ADD CONSTRAINT `fk_experience_domaine` FOREIGN KEY (`domaine_id`) REFERENCES `domaines` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_experience_entreprise` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `logs_audit`
--
ALTER TABLE `logs_audit`
  ADD CONSTRAINT `fk_logs_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
