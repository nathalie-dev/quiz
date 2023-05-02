CREATE TABLE IF NOT EXISTS `wp_nat_quiz_admin` (
  `id_admin` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS `wp_nat_quiz_questions` (
  `id_questions` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `descriptif` varchar(255) NOT NULL,
  `image` text NOT NULL,
  `date_creation` date NOT NULL,
  PRIMARY KEY (`id_questions`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS `wp_nat_quiz_reponses` (
  `id_reponses` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) NOT NULL,
  `score` int(11) NOT NULL,
  PRIMARY KEY (`id_reponses`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS `wp_nat_quiz_themes` (
  `id_themes` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `descriptif` varchar(255) NOT NULL,
  `date_creation` date NOT NULL DEFAULT current_timestamp(),
  `image` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS `wp_nat_quiz_utilisateurs` (
  `id_utilisateurs` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  PRIMARY KEY (`id_utilisateurs`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


