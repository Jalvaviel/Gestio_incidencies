-- MySQL dump 10.13  Distrib 8.0.31, for Win64 (x86_64)
--
-- Host: localhost    Database: gestio_incidencies
-- ------------------------------------------------------
-- Server version	8.0.31

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `devices`
--

DROP TABLE IF EXISTS `devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `devices` (
  `id_device` int NOT NULL AUTO_INCREMENT,
  `os` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL COMMENT 'Operative System',
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `description` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `room` int DEFAULT NULL,
  `ip` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL COMMENT 'internet protocol v4',
  `id_incident` int,
  PRIMARY KEY (`id_device`),
  UNIQUE KEY `ip` (`ip`),
  UNIQUE KEY `code` (`code`),
  KEY `id_incident` (`id_incident`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devices`
--

LOCK TABLES `devices` WRITE;
/*!40000 ALTER TABLE `devices` DISABLE KEYS */;
/*!40000 ALTER TABLE `devices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `incidents`
--

DROP TABLE IF EXISTS `incidents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `incidents` (
  `id_incident` int NOT NULL AUTO_INCREMENT,
  `description` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL COMMENT 'resolved/unresolved',
  `date` date DEFAULT NULL,
  `id_user` int NOT NULL,
  PRIMARY KEY (`id_incident`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `incidents`
--

LOCK TABLES `incidents` WRITE;
/*!40000 ALTER TABLE `incidents` DISABLE KEYS */;
/*!40000 ALTER TABLE `incidents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `surname` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `password` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `role` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','admin','admin@jviladoms.cat','$2y$13$Pj55Y1Zyc0V4NGFxQURaSeKYn960V4t.mfoSLBK0PH6/DSHmXHKm6','admin');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-11-13 12:12:17

-- Creación de usuarios

DROP USER IF EXISTS 'treballador'@'localhost';
DROP USER IF EXISTS 'tecnic'@'localhost';
DROP USER IF EXISTS 'jalvabot'@'localhost';
DROP USER IF EXISTS 'login'@'localhost';

/*
  Hay que poner las contaseñas sin proteger(hash) al importar, o da
  problemas al iniciar sesión.
*/

CREATE USER 'jalvabot'@'localhost' IDENTIFIED BY 'c0Oku)44:jV^|X}bv1O@£o?n)';

CREATE USER 'tecnic'@'localhost' IDENTIFIED BY 'H9t#11B}<$?0~>';

CREATE USER 'treballador'@'localhost' IDENTIFIED BY 'Xf4,5iB8£9q3%';

CREATE USER 'login'@'localhost' IDENTIFIED BY '63Gg.j9~LI|l4Q{APws'; 

-- Privilegios para `jalvabot`@`localhost`

GRANT USAGE ON *.* TO 'jalvabot'@'localhost';

GRANT SELECT, INSERT, UPDATE, DELETE, CREATE ON `gestio_incidencies`.`users` TO 'jalvabot'@'localhost';

GRANT SELECT, INSERT, UPDATE, DELETE, CREATE ON `gestio_incidencies`.`devices` TO 'jalvabot'@'localhost';

GRANT SELECT, INSERT, UPDATE, DELETE, CREATE ON `gestio_incidencies`.`incidents` TO 'jalvabot'@'localhost';


-- Privilegios para `tecnic`@`localhost`

GRANT USAGE ON *.* TO 'tecnic'@'localhost';

GRANT SELECT, UPDATE ON `gestio_incidencies`.`users` TO 'tecnic'@'localhost';

GRANT SELECT, UPDATE ON `gestio_incidencies`.`devices` TO 'tecnic'@'localhost';

GRANT SELECT, INSERT, UPDATE, DELETE ON `gestio_incidencies`.`incidents` TO 'tecnic'@'localhost';


-- Privilegios para `treballador`@`localhost`

GRANT USAGE ON *.* TO 'treballador'@'localhost';

GRANT SELECT, UPDATE ON `gestio_incidencies`.`users` TO 'treballador'@'localhost';

GRANT SELECT ON `gestio_incidencies`.`devices` TO 'treballador'@'localhost';

GRANT SELECT, INSERT, UPDATE, DELETE ON `gestio_incidencies`.`incidents` TO 'treballador'@'localhost';

-- Privilegios para `login`@`localhost`

GRANT USAGE ON *.* TO 'login'@'localhost';

GRANT SELECT ON `gestio_incidencies`.`users` TO `login`@`localhost`;