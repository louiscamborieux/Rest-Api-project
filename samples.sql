-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 29 mars 2023 à 09:45
-- Version du serveur : 5.7.36
-- Version de PHP : 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `api`
--

-- --------------------------------------------------------

--
-- Structure de la table `auteur`
--

DROP TABLE IF EXISTS `auteur`;
CREATE TABLE IF NOT EXISTS `auteur` (
  `id_auteur` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(64) NOT NULL,
  `role` set('publisher','moderator') NOT NULL,
  `password_hash` text NOT NULL,
  PRIMARY KEY (`id_auteur`),
  UNIQUE KEY `IN_LOGIN` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `auteur`
--

INSERT INTO `auteur` (`id_auteur`, `login`, `role`, `password_hash`) VALUES
(9, 'louiscmb', 'publisher', '$2y$10$FJoPGubZiLuYmDajQDMuCuqF4AbjO.el2Ebapuvgilp1EiPw9NQ5G'),
(10, 'davidp', 'publisher', '$2y$10$ExpkXbOz0jwS5t03RViSlemRSB/EaDKZnI/iUxmRrhiphpY2VtQkq'),
(11, 'blogModerator', 'moderator', '$2y$10$neKQOvdSOrzj73ZWHapPVuNMqLpz0U26KP1bDIf20B6EgezxISP3K'),
(12, 'user1', 'publisher', '$2y$10$NC83HsO9aCQ7InSny0NdQeA6GMRym37objvhvcQs6lMS0dTYsrvA6'),
(13, 'user2', 'publisher', '$2y$10$V30XnZpnsaSeycu/mJxZZ.RFGB7OI77zlVauaijtfZrDh/7iwBi.e'),
(14, 'user3', 'publisher', '$2y$10$3QjAuB27kqRuxEoc48elZe9WbZUdJZSaZUxnsXCLIwE9mrEnwXE5W'),
(15, 'EBorneEM', 'moderator', '$2y$10$g27cTIofLrDoU6Afl.4ESuQnV5RIE5cYZT73wM17SMG72hfysYjxe');

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_auteur` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `date_publication` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modification` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_post_auteur` (`id_auteur`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`id`, `id_auteur`, `contenu`, `date_publication`, `date_modification`) VALUES
(32, 9, 'Le Boudienny  est une race de chevaux créée au xxe siècle par le maréchal Semion Boudienny, dans l\'oblast de Rostov en Russie, pour la cavalerie soviétique. Cette race est issue en premier lieu de croisements entre le cheval du Don et le Pur-sang, d\'où son ancien nom d\'Anglo-don. Sélectionné à l\'origine sur ses performances militaires, le Boudienny est réorienté vers les sports équestres dans les années 1950, époque de sa reconnaissance officielle.', '2023-03-29 06:47:48', NULL),
(33, 9, 'ABC News Now est une chaîne de télévision d\'information numérique du groupe American Broadcasting Company. Elle est disponible par Internet sur le site ABCNews.com via streaming, sur des téléphones portables et aussi à travers certaines offres de cablo-opérateurs.', '2023-03-29 06:49:00', NULL),
(34, 10, 'Mazée (en wallon Måzêye) est un village de la vallée du Viroin en province de Namur (Belgique). A l\'extrémité nord-orientale du Parc naturel Viroin-Hermeton et en bordure de la frontière française il fait administrativement partie de la commune de Viroinval dans la province de Namur en Région wallonne de Belgique. C\'était une commune à part entière avant la fusion des communes de 1977.La commune est entourée, au nord par Niverlée et Vaucelles, à l’est par Hierges (France), à l’ouest et au sud par Treignes; le hameau de Najauge estarrosée par le Viroin.', '2023-03-29 06:50:36', NULL),
(35, 10, 'La ligne 7 du métro de Hangzhou  est une ligne du métro de Hangzhou qui permettra de joindre l\'aéroport international de Hangzhou-Xiaoshan et centre-ville de Hangzhou. La ligne comprend 24 stations, avec une longueur de 47,48km. Elle part de la place de Wushan et se termine rue Jiangdong\'er1. La ligne utilise un train de Type A2 dont la capacité est plus grande que celle des lignes en service. La section entre rue Jiangdong\'er et le Centre Olympique a ouvert le 30 décembre 2020. La station Centre de Citoyen a ouvert le 17 septembre 2021, ainsi que la section sous la rivière Qiantang.', '2023-03-29 06:51:12', NULL),
(36, 10, 'Successeurs désignés des navires de 80 canons de la classe Bucentaure conçus par Jean Tupinier, ils apportent une innovation : ils possèdent une coque droite, au lieu du frégatage qui prévaut alors à l\'époque. Cela a pour conséquence de remonter le centre de gravité du navire, mais permet d\'avoir plus d\'espace de stockage sur les ponts supérieurs. Les problèmes de stabilité sont résolus en rajoutant des stabilisateurs immergés sous la coque.', '2023-03-29 06:52:08', NULL),
(37, 9, 'Richard Seymour-Conway, 4e marquis d\'Hertford, aussi connu sous le nom de Lord Hertford, est un collectionneur d\'art et francophile anglais né le 22 février 1800 et mort le 25 août 1870, fils de Francis Seymour-Conway (3e marquis d\'Hertford) et de Maria Seymour-Conway.', '2023-03-29 06:52:57', NULL),
(38, 10, 'Hendrik Abbé, baptisé le 28 février 1639 à Anvers, est un peintre flamand, graveur et architecte. Hendrik Abbé est baptisé le 28 février 16391, dans la cathédrale d\'Anvers2. On ne sait rien de sa formation et son nom ne figure pas dans les registres de la guilde de Saint-Luc à Anvers3. Quelques estampes de lui ont été publiés à Anvers en 16702. Une version des Metamorpheses d\'Ovide, publiée par François Foppens à Bruxelles en 1677, fut en partie illustrée de planches par d\'autres graveurs d\'après les dessins d\'Abbé4. On cite de lui le dessin du portrait de Petrus van Bredae !, que grave son compatriote Conrad Lauwers5.', '2023-03-29 06:53:38', NULL),
(39, 9, 'Armature Studio, LLC est un studio américain de développement de jeux vidéo basé à Austin, au Texas. En avril 2008, après avoir achevé Metroid Prime 3: Corruption en 2007 pour Nintendo, l\'ancien directeur de jeu Mark Pacini, le directeur artistique Todd Keller et l\'ingénieur principal en technologie Jack Matthews ont quitté Retro Studios pour créer Armature, fondée en septembre 20081. Le premier jeu de Armature était Metal Gear Solid HD Collection pour la PlayStation Vita en 20122. En avril 2013, Warner Bros. Interactive Entertainment a annoncé que Batman: Arkham Origins Blackgate serait développé par Armature en tant que leur première nouvelle œuvre et publié pour la PlayStation Vita et la Nintendo 3DS le 25 octobre 20131. Le troisième jeu développé est l\'édition PlayStation Vita de Injustice: Gods Among Us3.', '2023-03-29 06:54:25', NULL),
(40, 9, 'Les pierres runiques grecques (en suédois : Greklandsstenarna) sont une trentaine de pierres runiques mentionnant des voyages réalisés par des Vikings dans l\'Empire byzantin. Réalisées au cours de l\'âge des Vikings, elles sont gravées en vieux norrois avec des runes scandinaves. La plupart le sont en mémoire de membres de leGarde varangienne qui ne sont jamais rentrés chez eux. Sur ces pierres runiques, les mots Grikkland (« Grèce »), Grikk(j)ar (« Grecs »), grikkfari (« voyageur en Grèce ») et Grikkhafnir (« ports grecs ») apparaissent dans plusieurs inscriptions, ce qui donne leur nom. Les pierres se trouvent en majorité en Uppland (18) et en Södermanland (7).', '2023-03-29 06:58:19', NULL),
(41, 9, 'Le marbre de Purbeck est un calcaire fossilifère affleurant dans l\'île de Purbeck, péninsule du sud-est du Dorset, en Angleterre. C\'est une variété de roche de Purbeck exploitée comme pierre de parement au moins depuis l\'occupation romaine.', '2023-03-29 06:58:50', NULL),
(42, 9, 'Winter Heat  est un jeu vidéo de sports d\'hiver sorti en novembre 1997 sur le système d\'arcade Sega Titan Video, puis sur Sega Saturn à partir de février 1998. Le jeu a été développé par Sega AM3 sur ST-V et par Data East sur Saturn, et édité sur cette console par Sega, sauf au Brésil où il a été commercialisé par Tec Toy. Il fait partie de la série DecAthlete, dont il constitue le deuxième épisode après Athlete Kings et est suivi par Virtua Athlete 2K.', '2023-03-29 06:59:40', NULL),
(44, 11, ' !A lire avant de Poster! Bonjour, je tiens à vous rappeler les règles que nous avons établies pour maintenir une communauté saine et respectueuse . Tout d\'abord, les posts doivent être pertinents et en lien avec le sujet de l\'article. Les insultes, les propos haineux, racistes ou discriminatoires ne sont pas tolérés et seront supprimés immédiatement. De plus, la politesse et le respect sont des valeurs importantes dans notre communauté. Nous vous encourageons donc à vous exprimer de manière courtoise et respectueuse envers les autres membres du blog. Enfin, je tiens à vous informer que le spam et la publicité sont prohibés ici. Il en va de soi que tout manquement à ces règles  peut entraîner la suppression de votre commentaire et même le bannissement de votre compte en cas de récidive. Nous vous remercions de votre compréhension et nous espérons que vous continuerez à contribuer de manière positive à notre communauté.', '2023-03-25 19:10:58', '2023-03-29 07:25:46'),
(45, 9, 'La bataille de Formigny est une bataille de la guerre de Cent Ans qui opposa les Français et leurs alliés Bretons aux Anglais le 15 avril 1450 à proximité de Formigny en Normandie.Elle se solde par une victoire décisive du royaume de France. Elle met également un terme aux ambitions de la couronne d\'Angleterre sur la Normandie.', '2023-03-29 07:15:12', NULL),
(46, 10, 'Ventouse est une commune du Sud-Ouest de la France, située dans le département de la Charente (région Nouvelle-Aquitaine).Ses habitants sont les Ventousiens et les Ventousiennes1. Ventouse est une commune du Nord Charente située à 10 km à l\'ouest de Saint-Claud et 31 km au nord-est d\'Angoulême.Le bourg de Ventouse est aussi à 6 km au nord-est de Saint-Angeau, 12 km à l\'est de Mansle, le chef-lieu de son canton, 12 km au sud-ouest de Champagne-Mouton, 18 km au sud-est de Ruffec et 29 km au sud-ouest de Confolens2.La D.739 entre Mansle et Saint-Claud passe en limite sud de la commune, près du bourg. La commune est traversée par des routes départementales de moindre importance. La D 15, entre Valence et Beaulieu, longe la rive droite de la Sonnette et passe à la mairie. La D 340 relie la D 739, le bourg et la D 15 en traversant la vallée du Son et de la Sonnette. Elle continue en direction de Couture3.', '2023-03-29 07:16:00', NULL),
(47, 10, 'Farncombe est un village du Surrey, en Angleterre. Il appartient administrativement à Godalming.Farncombe semble avoir été un campement ancien, puisque des objets archéologiques datant de l’Âge du bronze ont été retrouvés vers Northbourne Estate1.Farncombe apparaît ensuite dans le Domesday Book en 1086 sous le nom de Fernecombe. C’est alors la propriété d’Odon de Bayeux. On apprend que le village compte 61 000 m2 de prairies, et quelques forêts2.Peu d’anciens bâtiments subsistent pour attester de l’histoire du village : les plus vieux sont une série de bâtiments servant d’asile pour les indigents (almshouses), construits en 16223.Le bâtiment de la Farncombe Infants School, érigé par souscription en 1905, et situé sur Gray Roads, à côté de la gare, est aussi un des plus anciens édifices. Il servait d’école de garçons, avant d’être rattaché comme annexe à l’école en 1975.En 2013, un des quartiers du village, Waverley, est désigné comme offrant la meilleure qualité de vie en Grande-Bretagne4,5.', '2023-03-29 07:16:52', NULL),
(48, 9, 'Chudowola  est un village polonais de la gmina de Michów dans le powiat de Lubartów de la voïvodie de Lublin dans l\'est de la Pologne2.Il se situe à environ 5 kilomètres au nord de Michów (siège de la gmina), 23 kilomètres au nord-ouest de Lubartów (siège du powiat) et 40 kilomètres au nord-ouest de Lublin (capitale de la voïvodie).Le village comptait approximativement une population de 140 habitants en 20093..', '2023-03-29 07:17:46', '2023-03-29 07:19:04'),
(49, 10, 'Le plafond de verre (de l\'anglais glass ceiling) désigne le fait que, dans une structure hiérarchique, les niveaux supérieurs ne sont pas accessibles à certaines catégories de personnes essentiellement en raison de mépris de classe, de discrimination raciale ou de sexisme. Il peut, de manière intersectionnelle, être le résultat de plusieurs de ces discriminations subies simultanément.', '2023-03-29 07:19:29', NULL),
(50, 9, 'L’église du Saint-Esprit est un édifice religieux catholique de Margao, à Goa, en Inde. Une première église construite par les Jésuites en 1564 fut remplacée en 1675 par l’édifice actuel, de style baroque colonial portugais. L’église est le lieu de culte de la paroisse catholique principale de la ville de Margao, chef-lieu du district de South-Goa.', '2023-03-29 07:20:30', NULL),
(51, 9, 'L’église du Saint-Esprit est un édifice religieux catholique de Margao, à Goa, en Inde. Une première église construite par les Jésuites en 1564 fut remplacée en 1675 par l’édifice actuel, de style baroque colonial portugais. L’église est le lieu de culte de la paroisse catholique principale de la ville de Margao, chef-lieu du district de South-Goa.', '2023-03-29 07:20:35', NULL),
(52, 10, 'La saison 1964 du Championnat du Chili de football est la trente-deuxième édition du championnat de première division au Chili. Les dix-huit meilleures équipes du pays sont regroupées au sein d\'une poule unique, où elles s\'affrontent deux fois, à domicile et à l\'extérieur; à la fin de la compétition, le dernier du classement est relégué et remplacé par le champion de Segunda Division, la deuxième division chilienne.C\'est le club de CF Universidad de Chile qui remporte la compétition, après avoir terminé en tête du classement final, avec neuf points d\'avance sur un duo composé du CD Universidad Católica et des Santiago Wanderers. C\'est le quatrième titre de champion du Chili de l\'histoire du club.', '2023-03-29 07:21:35', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `reaction`
--

DROP TABLE IF EXISTS `reaction`;
CREATE TABLE IF NOT EXISTS `reaction` (
  `id_auteur` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `reac` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_auteur`,`id_post`),
  KEY `FK_REAC_POST` (`id_post`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `reaction`
--

INSERT INTO `reaction` (`id_auteur`, `id_post`, `reac`) VALUES
(9, 32, NULL),
(9, 33, NULL),
(9, 37, NULL),
(9, 39, NULL),
(9, 40, NULL),
(9, 41, NULL),
(9, 42, NULL),
(9, 44, 0),
(9, 45, NULL),
(9, 48, NULL),
(9, 50, NULL),
(9, 51, NULL),
(10, 32, 1),
(10, 33, 1),
(10, 34, NULL),
(10, 35, NULL),
(10, 36, NULL),
(10, 37, 1),
(10, 38, NULL),
(10, 39, 0),
(10, 40, 0),
(10, 41, 0),
(10, 42, 0),
(10, 44, 0),
(10, 46, NULL),
(10, 47, NULL),
(10, 49, NULL),
(10, 52, NULL),
(11, 32, 1),
(11, 33, 1),
(11, 34, 1),
(11, 35, 0),
(11, 36, 0),
(11, 37, 1),
(11, 38, 1),
(11, 40, 1),
(11, 44, NULL),
(12, 44, 0),
(13, 44, 0),
(15, 44, 1);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `FK_post_auteur` FOREIGN KEY (`id_auteur`) REFERENCES `auteur` (`id_auteur`);

--
-- Contraintes pour la table `reaction`
--
ALTER TABLE `reaction`
  ADD CONSTRAINT `FK_REAC_AUTEUR` FOREIGN KEY (`id_auteur`) REFERENCES `auteur` (`id_auteur`),
  ADD CONSTRAINT `FK_REAC_POST` FOREIGN KEY (`id_post`) REFERENCES `post` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
