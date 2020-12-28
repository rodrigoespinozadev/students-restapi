/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table api_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `api_users`;

CREATE TABLE `api_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(150) NOT NULL DEFAULT '',
  `token` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

LOCK TABLES `api_users` WRITE;
/*!40000 ALTER TABLE `api_users` DISABLE KEYS */;

INSERT INTO `api_users` (`id`, `username`, `password`, `token`)
VALUES
	(1,'admin','$2y$10$hPLbFp181rWebUIJDTjQWexzAmgcLP4KFzgk/btZXJSNvcJ3zesBi',NULL);

/*!40000 ALTER TABLE `api_users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table students
# ------------------------------------------------------------

DROP TABLE IF EXISTS `students`;

CREATE TABLE `students` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;

INSERT INTO `students` (`id`, `username`, `first_name`, `last_name`)
VALUES
	(1,'kctest00202','Bernando','Santini'),
	(2,'kctest00203','George','Quebedo'),
	(3,'kctest00204','Terry','Cruz'),
	(4,'kctest00205','David','Smith'),
	(5,'kctest00206','Rob','Shnneider'),
	(6,'kctest00201','Bernando','Santini'),
	(7,'kctest00207','George','Quebedo'),
	(8,'kctest00208','Terry','Cruz'),
	(9,'kctest00209','David','Smith'),
	(10,'kctest00210','Rob','Shnneider'),
	(11,'kctest00211','Bernando','Santini'),
	(12,'kctest00212','George','Quebedo'),
	(13,'kctest00213','Terry','Cruz'),
	(14,'kctest00214','David','Smith'),
	(15,'kctest00215','Rob','Shnneider');

/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
