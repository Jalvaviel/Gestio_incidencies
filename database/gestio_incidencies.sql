-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 07-11-2023 a las 10:44:23
-- Versión del servidor: 5.7.36
-- Versión de PHP: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gestio_incidencies`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devices`
--

DROP TABLE IF EXISTS `devices`;
CREATE TABLE IF NOT EXISTS `devices` (
  `id_device` int(11) NOT NULL,
  `os` varchar(200) COLLATE utf8mb4_spanish_ci DEFAULT NULL COMMENT 'Operative System',
  `code` varchar(200) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `description` varchar(2000) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `room` int(11) DEFAULT NULL,
  `ip` varchar(15) COLLATE utf8mb4_spanish_ci DEFAULT NULL COMMENT 'internet protocol v4',
  PRIMARY KEY (`id_device`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incidents`
--

DROP TABLE IF EXISTS `incidents`;
CREATE TABLE IF NOT EXISTS `incidents` (
  `id_incident` int(11) NOT NULL,
  `description` varchar(2000) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_spanish_ci DEFAULT NULL COMMENT 'resolved/unresolved',
  `date` date DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id_incident`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `incidents`
--

INSERT INTO `incidents` (`id_incident`, `description`, `status`, `date`, `id_user`) VALUES
(1, 'test', 'unresolved', '2023-11-23', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incidents_devices`
--

DROP TABLE IF EXISTS `incidents_devices`;
CREATE TABLE IF NOT EXISTS `incidents_devices` (
  `id` int(11) NOT NULL,
  `id_incident` int(11) NOT NULL,
  `id_device` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_incident` (`id_incident`),
  KEY `fk_device` (`id_device`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `surname` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `email` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `password` varchar(256) COLLATE utf8mb4_spanish_ci NOT NULL,
  `role` varchar(256) COLLATE utf8mb4_spanish_ci NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id_user`, `name`, `surname`, `email`, `password`, `role`) VALUES
(1, 'Admin', 'admin', 'admin@jviladoms.cat', '$2y$13$Pj55Y1Zyc0V4NGFxQURaSeKYn960V4t.mfoSLBK0PH6/DSHmXHKm6', 'admin');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `incidents_devices`
--
ALTER TABLE `incidents_devices`
  ADD CONSTRAINT `fk_device` FOREIGN KEY (`id_device`) REFERENCES `devices` (`id_device`),
  ADD CONSTRAINT `fk_incident` FOREIGN KEY (`id_incident`) REFERENCES `incidents` (`id_incident`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- Crear usuarios

DROP USER IF EXISTS 'treballador'@'127.0.0.1';
DROP USER IF EXISTS 'tecnic'@'127.0.0.1';
DROP USER IF EXISTS 'jalvabot'@'127.0.0.1';

/*
  Hay que poner las contaseñas sin proteger(hash) al importar, o da
  problemas al iniciar sesión.
*/

CREATE USER 'treballador'@'127.0.0.1' IDENTIFIED BY 'Xf4,5iB8£9q3%';

CREATE USER 'tecnic'@'127.0.0.1' IDENTIFIED BY 'H9t#11B}<$?0~>';

CREATE USER 'jalvabot'@'127.0.0.1' IDENTIFIED BY 'c0Oku)44:jV^|X}bv1O@£o?n)';


-- Privilegios para `jalvabot`@`127.0.0.1`

GRANT USAGE ON *.* TO 'jalvabot'@'127.0.0.1';

GRANT SELECT, INSERT, UPDATE, DELETE, CREATE ON `gestio_incidencies`.`users` TO 'jalvabot'@'127.0.0.1';

GRANT SELECT, INSERT, UPDATE, DELETE, CREATE ON `gestio_incidencies`.`devices` TO 'jalvabot'@'127.0.0.1';

GRANT SELECT, INSERT, UPDATE, DELETE, CREATE ON `gestio_incidencies`.`incidents` TO 'jalvabot'@'127.0.0.1';


-- Privilegios para `tecnic`@`127.0.0.1`

GRANT USAGE ON *.* TO 'tecnic'@'127.0.0.1';

GRANT SELECT ON `gestio_incidencies`.`users` TO 'tecnic'@'127.0.0.1';

GRANT SELECT ON `gestio_incidencies`.`devices` TO 'tecnic'@'127.0.0.1';

GRANT SELECT, INSERT, UPDATE, DELETE ON `gestio_incidencies`.`incidents` TO 'tecnic'@'127.0.0.1';


-- Privilegios para `treballador`@`127.0.0.1`

GRANT USAGE ON *.* TO 'treballador'@'127.0.0.1';

GRANT SELECT ON `gestio_incidencies`.`devices` TO 'treballador'@'127.0.0.1';

GRANT SELECT, INSERT ON `gestio_incidencies`.`incidents` TO 'treballador'@'127.0.0.1';


INSERT INTO users VALUES (1,'Admin','admin','admin@jviladoms.cat','$2y$13$Pj55Y1Zyc0V4NGFxQURaSeKYn960V4t.mfoSLBK0PH6/DSHmXHKm6','admin');