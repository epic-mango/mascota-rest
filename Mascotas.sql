-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-06-2021 a las 04:52:02
-- Versión del servidor: 10.4.16-MariaDB
-- Versión de PHP: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mascotas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dispositivos`
--

CREATE TABLE `dispositivos` (
  `mac` varchar(30) NOT NULL,
  `pass` varchar(64) NOT NULL,
  `serie` int(11) NOT NULL,
  `mascota` int(11) DEFAULT NULL,
  `alimento` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `dispositivos`
--

INSERT INTO `dispositivos` (`mac`, `pass`, `serie`, `mascota`, `alimento`) VALUES
('68:C6:3A:D7:3A:7E', '202cb962ac59075b964b07152d234b70', 1, NULL, 'Croquetas'),
('Hola', '202cb962ac59075b964b07152d234b70', 1, NULL, 'Agua');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id` int(11) NOT NULL,
  `mac` varchar(30) NOT NULL,
  `minuto` int(11) NOT NULL,
  `gramos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mascotas`
--

CREATE TABLE `mascotas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `especie` int(11) DEFAULT NULL,
  `raza` int(11) DEFAULT NULL,
  `nacimiento` mediumtext DEFAULT NULL,
  `usuario` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mascotas`
--

INSERT INTO `mascotas` (`id`, `nombre`, `especie`, `raza`, `nacimiento`, `usuario`) VALUES
(20, 'Bagheera', 0, 252, '1622709345624', 'atlmango'),
(24, 'Kiwi', 0, 123, '1047189684840', 'atlmango');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sensores`
--

CREATE TABLE `sensores` (
  `mac` varchar(30) NOT NULL,
  `tipo` varchar(3) NOT NULL,
  `valor` float NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `sensores`
--

INSERT INTO `sensores` (`mac`, `tipo`, `valor`, `fecha`) VALUES
('68:C6:3A:D7:3A:7E', 'tan', 0, '2021-05-12 00:00:00'),
('68:C6:3A:D7:3A:7E', 'tan', 3, '2021-05-12 00:00:00'),
('68:C6:3A:D7:3A:7E', 'tan', 3, '2021-05-12 00:00:00'),
('68:C6:3A:D7:3A:7E', 'tan', 3, '2021-05-12 00:00:00'),
('68:C6:3A:D7:3A:7E', 'tan', 3, '2021-05-12 00:00:00'),
('68:C6:3A:D7:3A:7E', 'tan', 3, '2021-05-12 00:00:00'),
('68:C6:3A:D7:3A:7E', 'tan', 3, '2021-05-12 00:00:00'),
('68:C6:3A:D7:3A:7E', 'tan', 3, '2021-05-12 07:26:03'),
('68:C6:3A:D7:3A:7E', 'tan', 3, '2021-05-12 07:28:21'),
('68:C6:3A:D7:3A:7E', 'tan', 3, '2021-05-12 07:34:28'),
('68:C6:3A:D7:3A:7E', 'tan', 7, '2021-05-12 07:41:45'),
('68:C6:3A:D7:3A:7E', 'tan', 7, '2021-05-12 07:49:29'),
('68:C6:3A:D7:3A:7E', 'tan', 1, '2021-05-12 08:15:08'),
('68:C6:3A:D7:3A:7E', 'tan', 1, '2021-05-12 08:16:59'),
('68:C6:3A:D7:3A:7E', 'tan', 1, '2021-05-12 08:18:16'),
('68:C6:3A:D7:3A:7E', 'tan', 1, '2021-05-12 08:24:15'),
('68:C6:3A:D7:3A:7E', 'tan', 1, '2021-05-12 08:24:22'),
('68:C6:3A:D7:3A:7E', 'tan', 0, '2021-05-12 18:09:21'),
('68:C6:3A:D7:3A:7E', 'tan', 7, '2021-05-12 18:10:40'),
('68:C6:3A:D7:3A:7E', 'tan', 7, '2021-05-12 18:11:26'),
('68:C6:3A:D7:3A:7E', 'tan', 7, '2021-05-12 18:12:16'),
('68:C6:3A:D7:3A:7E', 'tan', 7, '2021-05-12 18:13:32'),
('68:C6:3A:D7:3A:7E', 'tan', 0, '2021-05-14 06:51:05'),
('68:C6:3A:D7:3A:7E', 'tan', 0, '2021-05-14 06:56:26'),
('68:C6:3A:D7:3A:7E', 'tan', 0, '2021-05-14 06:57:33'),
('68:C6:3A:D7:3A:7E', 'tan', 0, '2021-05-14 06:58:50'),
('68:C6:3A:D7:3A:7E', 'tan', 0, '2021-05-14 06:59:04'),
('68:C6:3A:D7:3A:7E', 'tan', 1, '2021-05-14 07:28:48'),
('68:C6:3A:D7:3A:7E', 'tan', 1, '2021-05-14 07:34:47'),
('68:C6:3A:D7:3A:7E', 'tan', 7, '2021-05-24 05:06:18'),
('68:C6:3A:D7:3A:7E', 'tan', 1, '2021-05-24 05:07:11'),
('68:C6:3A:D7:3A:7E', 'tan', 1, '2021-05-24 05:08:26'),
('68:C6:3A:D7:3A:7E', 'tan', 0, '2021-05-27 00:01:06'),
('68:C6:3A:D7:3A:7E', 'tan', 0, '2021-05-27 00:03:29'),
('68:C6:3A:D7:3A:7E', 'tan', 7, '2021-05-28 00:45:02'),
('68:C6:3A:D7:3A:7E', 'tan', 0, '2021-05-28 00:53:27'),
('68:C6:3A:D7:3A:7E', 'tan', 0, '2021-05-31 01:28:26'),
('68:C6:3A:D7:3A:7E', 'tan', 0, '2021-05-31 01:29:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` varchar(20) NOT NULL,
  `pass` varchar(45) NOT NULL,
  `nombres` varchar(45) DEFAULT NULL,
  `apellidos` varchar(45) DEFAULT NULL,
  `apodo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `pass`, `nombres`, `apellidos`, `apodo`) VALUES
('atlmango', '202cb962ac59075b964b07152d234b70', '', '', 'atlmango'),
('atlmango2', '202cb962ac59075b964b07152d234b70', '', '', 'atlmango2'),
('mango', '202cb962ac59075b964b07152d234b70', '', '', 'mango');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `dispositivos`
--
ALTER TABLE `dispositivos`
  ADD PRIMARY KEY (`mac`),
  ADD KEY `FK_mascota` (`mascota`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mascotas`
--
ALTER TABLE `mascotas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Mascota_Usuario` (`usuario`);

--
-- Indices de la tabla `sensores`
--
ALTER TABLE `sensores`
  ADD KEY `FK_dispositivos` (`mac`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `mascotas`
--
ALTER TABLE `mascotas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `dispositivos`
--
ALTER TABLE `dispositivos`
  ADD CONSTRAINT `FK_mascota` FOREIGN KEY (`mascota`) REFERENCES `mascotas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `mascotas`
--
ALTER TABLE `mascotas`
  ADD CONSTRAINT `Mascota_Usuario` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `sensores`
--
ALTER TABLE `sensores`
  ADD CONSTRAINT `FK_dispositivos` FOREIGN KEY (`mac`) REFERENCES `dispositivos` (`mac`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
