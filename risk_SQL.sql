-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 24-10-2024 a las 18:35:39
-- Versión del servidor: 8.3.0
-- Versión de PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `risk`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partidas`
--

DROP TABLE IF EXISTS `partidas`;
CREATE TABLE IF NOT EXISTS `partidas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `estado` tinyint(1) NOT NULL COMMENT '0 - creado\r\n1-distribuido\r\n2-ganada\r\n3-perdida',
  `num_tropas` int NOT NULL,
  `ultimo_jugador` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_partidas_usuarios_id` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `partidas`
--

INSERT INTO `partidas` (`id`, `id_usuario`, `estado`, `num_tropas`, `ultimo_jugador`) VALUES
(52, 1, 1, 30, -1),
(54, 1, 1, 30, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `territorios`
--

DROP TABLE IF EXISTS `territorios`;
CREATE TABLE IF NOT EXISTS `territorios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `posicion` int NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `propietario` int NOT NULL,
  `id_partida` int NOT NULL,
  `tropas` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_territorios_partidas_id` (`id_partida`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=593 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `territorios`
--

INSERT INTO `territorios` (`id`, `posicion`, `nombre`, `propietario`, `id_partida`, `tropas`) VALUES
(533, 1, 'Suecia', -1, 52, 2),
(534, 2, 'Vietnam', 1, 52, 2),
(535, 3, 'Irán', 1, 52, 1),
(536, 4, 'Nigeria', 1, 52, 2),
(537, 5, 'Egipto', -1, 52, 1),
(538, 6, 'Colombia', 1, 52, 1),
(539, 7, 'Brasil', 1, 52, 2),
(540, 8, 'Venezuela', 1, 52, 1),
(541, 9, 'Alemania', -1, 52, 4),
(542, 10, 'Japón', -1, 52, 1),
(543, 11, 'Chile', 1, 52, 1),
(544, 12, 'India', -1, 52, 2),
(545, 13, 'Vietnam', 1, 52, 1),
(546, 14, 'Indonesia', 1, 52, 1),
(547, 15, 'Rusia', 1, 52, 1),
(548, 16, 'Mongolia', -1, 52, 1),
(549, 17, 'Finlandia', -1, 52, 1),
(550, 18, 'Chile', -1, 52, 1),
(551, 19, 'Islandia', -1, 52, 1),
(552, 20, 'Finlandia', -1, 52, 1),
(573, 1, 'Australia', 1, 54, 10),
(574, 2, 'Perú', -1, 54, 1),
(575, 3, 'Irán', -1, 54, 2),
(576, 4, 'Noruega', -1, 54, 1),
(577, 5, 'Alaska', 1, 54, 12),
(578, 6, 'Noruega', 1, 54, 8),
(579, 7, 'China', 1, 54, 9),
(580, 8, 'Mongolia', 1, 54, 11),
(581, 9, 'Siberia', 1, 54, 11),
(582, 10, 'Siria', -1, 54, 1),
(583, 11, 'Vietnam', 1, 54, 6),
(584, 12, 'Francia', 1, 54, 8),
(585, 13, 'Rusia', -1, 54, 1),
(586, 14, 'Grecia', 1, 54, 11),
(587, 15, 'Groenlandia', -1, 54, 2),
(588, 16, 'Reino Unido', -1, 54, 1),
(589, 17, 'Sudáfrica', 1, 54, 15),
(590, 18, 'Grecia', -1, 54, 1),
(591, 19, 'Grecia', -1, 54, 3),
(592, 20, 'Canadá', 1, 54, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `admin` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `name`, `email`, `password`, `admin`) VALUES
(1, 'pepe', 'pepe@gmail.com', '1234', 1),
(6, 'angel', 'angelcg225@gmail.com', '4321', 1);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `partidas`
--
ALTER TABLE `partidas`
  ADD CONSTRAINT `fk_partidas_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `territorios`
--
ALTER TABLE `territorios`
  ADD CONSTRAINT `fk_tableros_partidas_id` FOREIGN KEY (`id_partida`) REFERENCES `partidas` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
