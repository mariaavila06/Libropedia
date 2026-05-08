-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-05-2026 a las 02:47:42
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `libropedia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `generos`
--

CREATE TABLE `generos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `generos`
--

INSERT INTO `generos` (`id`, `nombre`) VALUES
(2, 'Clásico'),
(1, 'Fantasía'),
(4, 'Misterio'),
(3, 'Novela contempo'),
(5, 'real');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE `libros` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `autor` varchar(25) NOT NULL,
  `descripcion` text NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `precio_bs` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pdf_path` varchar(255) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `editorial` varchar(100) NOT NULL,
  `anio_publicacion` int(10) UNSIGNED NOT NULL,
  `numero_edicion` int(10) UNSIGNED NOT NULL,
  `lugar_publicacion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `libros`
--

INSERT INTO `libros` (`id`, `nombre`, `autor`, `descripcion`, `imagen`, `precio_bs`, `pdf_path`, `creado_en`, `editorial`, `anio_publicacion`, `numero_edicion`, `lugar_publicacion`) VALUES
(1, 'Cien años de soledad', 'Gabriel García Márquez', 'La obra maestra del realismo mágico latinoamericano.', 'assets/img/libro_69b7c0f0276b7.png', 250.00, 'storage/pdfs/cien-anos-de-soledad.pdf', '2026-03-16 07:26:16', '', 0, 0, ''),
(2, 'Don Quijote de la Mancha', 'Miguel de Cervantes', 'Las aventuras del ingenioso hidalgo Don Quijote y su escudero Sancho Panza.', 'assets/img/libro_69b7a59e35407.png', 300.00, 'storage/pdfs/libro_1773643166_don-quijote-de-la-mancha.pdf', '2026-03-16 07:26:16', '', 0, 0, ''),
(3, 'La sombra del viento', 'Carlos Ruiz Zafón', 'Un misterio literario ambientado en la Barcelona de la posguerra.', 'assets/img/libro_69b7c1440dd0e.jpg', 220.00, 'storage/pdfs/la-sombra-del-viento.pdf', '2026-03-16 07:26:16', '', 0, 0, ''),
(4, 'El nombre del viento', 'Patrick Rothfuss', 'La historia de Kvothe, un joven con un talento extraordinario.', 'assets/img/libro_1773642115_el-nombre-del-viento-cronica-del-asesino-de-reyes-1.jpg', 280.00, 'storage/pdfs/libro_1773642115_el-nombre-del-viento.pdf', '2026-03-16 07:26:16', '', 0, 0, ''),
(8, 'Biblia', 'Papa Dio', 'Libro antiguo moya', 'assets/img/libro_69b7cac425a05.jpg', 1000.00, '', '2026-03-16 13:17:56', 'El cielo', 2001, 1, 'Venezuela');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro_genero`
--

CREATE TABLE `libro_genero` (
  `libro_id` int(10) UNSIGNED NOT NULL,
  `genero_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `libro_genero`
--

INSERT INTO `libro_genero` (`libro_id`, `genero_id`) VALUES
(1, 2),
(2, 2),
(3, 1),
(4, 1),
(8, 2),
(8, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `correo` varchar(40) NOT NULL,
  `cedula` varchar(15) NOT NULL,
  `contraseña_hash` varchar(255) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `usuario`, `correo`, `cedula`, `contraseña_hash`, `creado_en`) VALUES
(1, 'Maria', 'Avila', 'Maria1', 'mariaavila0414@gmail.com', '29883897', '$2y$10$UjscFzwCi.uIKDbrimkpMe23na7lT4CjqXr0.kgfsDUM95yH1PUPG', '2026-03-16 07:15:05'),
(2, 'Admin', 'Sistema', 'Admin123', 'admin@libropedia.local', '0', '$2y$10$UjscFzwCi.uIKDbrimkpMe23na7lT4CjqXr0.kgfsDUM95yH1PUPG', '2026-03-16 09:49:32'),
(3, 'Majo', 'lopez', 'Majo', 'mariaavila0414@gmail.com', '31691344', '$2y$10$UjscFzwCi.uIKDbrimkpMe23na7lT4CjqXr0.kgfsDUM95yH1PUPG', '2026-03-16 12:14:39'),
(4, 'jose', 'avila', 'joseavila', 'jose@g.com', '3269', '$2y$10$VHc/4AF57U6nu3fxpyu1ROrQc9O7Lkt82dMsLjXpfPeZmJQ5Qlmvu', '2026-03-16 12:26:56'),
(5, 'Maria', 'Avola', 'mariaavila', 'mariaavila0414@gmail.com', '31691344k', '$2y$10$YjNWMaMrhAIddYd6wh3H6OfLUW9gygVAdE26PvmouOW7NS4o.EoWK', '2026-05-07 20:41:43'),
(6, 'jose', 'ramon', 'joseito', 'jose@gmail.com', '3222222', '$2y$10$DhQ1DCBbUWjTrOWT7rwkwOD.gXKHWcohGfkirwrg5WCDKAIJJ6CIe', '2026-05-07 23:21:26'),
(7, 'ana', 'lopez', 'anita', 'anita@gmail.com', '9872727', '$2y$10$l.YgFv2aeCPgIEcQgwy3AeunZZ681eMFuG4fBrzcSjmokIG9Qsomi', '2026-05-07 23:35:32'),
(8, 'kiwi', 'mamon', 'mariaaaavila', 'mariaa@gmail.com', '12345678', '$2y$10$Z3fUl..VIbRGI7moMKmVb.aB6TSlpICFTOeKLoj2JRkHgn27KYmSa', '2026-05-07 23:55:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `libro_id` int(10) UNSIGNED NOT NULL,
  `precio_bs` decimal(10,2) NOT NULL,
  `fecha_venta` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `usuario_id`, `libro_id`, `precio_bs`, `fecha_venta`) VALUES
(1, 1, 4, 280.00, '2026-03-16 14:58:26'),
(2, 1, 3, 220.00, '2026-03-16 14:58:26'),
(3, 1, 2, 300.00, '2026-03-16 15:01:25'),
(4, 1, 4, 280.00, '2026-03-16 15:01:25'),
(5, 1, 2, 300.00, '2026-03-16 15:02:56'),
(6, 1, 4, 280.00, '2026-03-16 15:02:56'),
(7, 1, 2, 300.00, '2026-03-16 15:12:56'),
(8, 1, 4, 280.00, '2026-03-16 15:12:56'),
(9, 1, 2, 300.00, '2026-03-16 15:14:18'),
(10, 1, 4, 280.00, '2026-03-16 15:14:18'),
(11, 1, 2, 300.00, '2026-03-16 15:16:00'),
(12, 1, 4, 280.00, '2026-03-16 15:16:00'),
(13, 1, 2, 300.00, '2026-03-16 15:26:13'),
(14, 1, 2, 300.00, '2026-03-16 15:28:08'),
(15, 1, 1, 250.00, '2026-03-16 15:30:02'),
(16, 1, 4, 280.00, '2026-03-16 15:30:02'),
(17, 4, 1, 250.00, '2026-03-16 12:30:03'),
(18, 4, 8, 100.00, '2026-03-16 12:31:05'),
(19, 4, 8, 100.00, '2026-03-16 12:31:05'),
(20, 4, 1, 250.00, '2026-03-16 12:31:35'),
(21, 4, 2, 300.00, '2026-03-16 12:31:35'),
(22, 4, 8, 200.00, '2026-03-16 13:27:02'),
(23, 4, 8, 200.00, '2026-03-16 13:27:03'),
(24, 4, 3, 220.00, '2026-03-16 13:27:03'),
(25, 5, 8, 200.00, '2026-05-07 20:43:36'),
(26, 5, 1, 250.00, '2026-05-07 20:44:31'),
(27, 5, 4, 280.00, '2026-05-07 20:45:14'),
(28, 6, 8, 200.00, '2026-05-07 23:28:49'),
(29, 8, 2, 300.00, '2026-05-07 23:55:58'),
(30, 8, 4, 280.00, '2026-05-07 23:58:19'),
(31, 8, 1, 250.00, '2026-05-07 23:58:49'),
(32, 5, 4, 280.00, '2026-05-08 00:17:04'),
(33, 7, 8, 1000.00, '2026-05-08 00:40:52');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `generos`
--
ALTER TABLE `generos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `libro_genero`
--
ALTER TABLE `libro_genero`
  ADD PRIMARY KEY (`libro_id`,`genero_id`),
  ADD KEY `fk_genero` (`genero_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_venta_usuario` (`usuario_id`),
  ADD KEY `fk_venta_libro` (`libro_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `generos`
--
ALTER TABLE `generos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `libros`
--
ALTER TABLE `libros`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `libro_genero`
--
ALTER TABLE `libro_genero`
  ADD CONSTRAINT `fk_genero` FOREIGN KEY (`genero_id`) REFERENCES `generos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_libro` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `fk_venta_libro` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_venta_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
