SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `zf2`
--
CREATE DATABASE IF NOT EXISTS `zf2` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `zf2`;

-- --------------------------------------------------------

--
-- Structure de la table `album`
--

DROP TABLE IF EXISTS `album`;
CREATE TABLE IF NOT EXISTS `album` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `artist` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `album_user_id_index` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Vider la table avant d'insérer `album`
--

TRUNCATE TABLE `album`;
--
-- Contenu de la table `album`
--

INSERT INTO `album` (`id`, `artist`, `title`, `user_id`) VALUES
(2, 'Adele', '25', NULL),
(3, 'Bruce  Springsteen', 'Wrecking Ball (Deluxe)', NULL),
(4, 'Lana  Del  Rey', 'Born  To  Die', NULL),
(5, 'Gotye', 'Making  Mirrors', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `answer`
--

DROP TABLE IF EXISTS `answer`;
CREATE TABLE IF NOT EXISTS `answer` (
  `idAnswer` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(254) DEFAULT NULL,
  `idQuestion` int(11) NOT NULL,
  PRIMARY KEY (`idAnswer`),
  KEY `FK_GOT` (`idQuestion`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Vider la table avant d'insérer `answer`
--

TRUNCATE TABLE `answer`;
--
-- Contenu de la table `answer`
--

INSERT INTO `answer` (`idAnswer`, `title`, `idQuestion`) VALUES
(2, 'Reponse', 1),
(4, 'Réponse 2', 1);

-- --------------------------------------------------------

--
-- Structure de la table `question`
--

DROP TABLE IF EXISTS `question`;
CREATE TABLE IF NOT EXISTS `question` (
  `idQuestion` int(11) NOT NULL AUTO_INCREMENT,
  `idUser` int(11) NOT NULL,
  `title` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`idQuestion`),
  KEY `FK_CREATE` (`idUser`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Vider la table avant d'insérer `question`
--

TRUNCATE TABLE `question`;
--
-- Contenu de la table `question`
--

INSERT INTO `question` (`idQuestion`, `idUser`, `title`) VALUES
(1, 1, 'Question ?');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `idUser` int(11) NOT NULL,
  `email` varchar(254) DEFAULT NULL,
  `password` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Vider la table avant d'insérer `user`
--

TRUNCATE TABLE `user`;
--
-- Contenu de la table `user`
--

INSERT INTO `user` (`idUser`, `email`, `password`) VALUES
(0, 'test@test.com', 'c3402aa8e08083903e88ced9ebf72146ce5307f0'),
(1, NULL, '90c62b7c360038ecf0764fbf2e1035d87f39eeb7');

-- --------------------------------------------------------

--
-- Structure de la table `useranswer`
--

DROP TABLE IF EXISTS `useranswer`;
CREATE TABLE IF NOT EXISTS `useranswer` (
  `idAnswer` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  PRIMARY KEY (`idAnswer`,`idUser`),
  KEY `FK_ANSWER_USER` (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Vider la table avant d'insérer `useranswer`
--

TRUNCATE TABLE `useranswer`;
--
-- Contenu de la table `useranswer`
--

INSERT INTO `useranswer` (`idAnswer`, `idUser`) VALUES
(2, 1),
(4, 1);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `album`
--
ALTER TABLE `album`
  ADD CONSTRAINT `album_user_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `FK_GOT` FOREIGN KEY (`idQuestion`) REFERENCES `question` (`idQuestion`);

--
-- Contraintes pour la table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `FK_CREATE` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`);

--
-- Contraintes pour la table `useranswer`
--
ALTER TABLE `useranswer`
  ADD CONSTRAINT `FK_ANSWER` FOREIGN KEY (`idAnswer`) REFERENCES `answer` (`idAnswer`),
  ADD CONSTRAINT `FK_ANSWER_USER` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
