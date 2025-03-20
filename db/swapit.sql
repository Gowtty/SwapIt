-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-03-2025 a las 03:43:28
-- Versión del servidor: 11.5.2-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `swapit`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `image_url` text DEFAULT NULL,
  `status` enum('Disponible','Reservado','Intercambiado') DEFAULT 'Disponible',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `offers`
--

CREATE TABLE `offers` (
  `id` int(11) NOT NULL,
  `offered_by` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `offered_item_id` int(11) NOT NULL,
  `status` enum('Pendiente','Aceptada','Rechazada') DEFAULT 'Pendiente',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` text NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `state` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `phone`, `created_at`, `state`, `city`) VALUES
(7, 'Jose10', 'josem10@gmail.com', '$2y$10$rQlXB7j5m9q3DMHW3e8nuuedwATIyaJK3RUb9LteiURNKWTOc8Cb.', '6682455684', '2025-03-15 04:31:27', 'Jalisco', 'Zapopan'),
(8, 'pepe', 'pepe@gmail.com', '$2y$10$kuC47.G4OJEhIyLMvzBJM.db3u04/KkSAGjFVUZzu.NLuQlPKoTEu', '6682455684', '2025-03-15 04:32:56', 'Jalisco', 'Guadalajara'),
(9, 'juan20', 'juan2002@gmail.com', '$2y$10$cii6F.sWN2I4UsY0wlMjp.Ckr/bAFlPKDOTN.2fguyuKRkuX20FFW', '6682455684', '2025-03-15 04:34:06', 'Jalisco', 'Guadalajara'),
(10, 'josue2002', 'josue2002@gmail.com', '$2y$10$9JRcNu0xuIkGEEjzhHe8tuz.BAO0MjvBj0CCSlWGtMct.BVBdGkla', '6682455684', '2025-03-15 04:52:24', 'Jalisco', 'Guadalajara'),
(11, 'pepe500', 'pepito500@gmail.com', '$2y$10$Nro1Z6k7yROifrh4m3xSTOSqrYCJsXuxNJRB0NzWSGNurBlVuVyv6', '6682455684', '2025-03-15 04:53:45', 'Jalisco', 'Tlaquepaque'),
(12, 'juan2000', 'juani2000@gmail.com', '$2y$10$mta0CGkZHOZNZhbWs0Lvm.4vBsruuG0qNRuesT3MrLFZDs4CcQjFa', '6682345743', '2025-03-15 04:54:26', 'Jalisco', 'Zapopan'),
(13, 'julia10', 'juli10@gmail.com', '$2y$10$uomglYmg1VU1zYgpADBZ0.4q54T9K361Zdx04LWisVbkEHCytAVBy', '6682486582', '2025-03-15 04:55:40', 'Nuevo LeÃ³n', 'Monterrey'),
(14, 'kat1621', 'kat16@gmail.com', '$2y$10$vsyUFlSAK5PBdAJ35hyGn.oMyV4rMJkz6aPJgQX/onKbY.3PXkcR2', '668238544', '2025-03-15 05:13:59', 'CDMX', 'CoyoacÃ¡n');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indices de la tabla `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indices de la tabla `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offered_by` (`offered_by`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `offered_item_id` (`offered_item_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `items_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Filtros para la tabla `offers`
--
ALTER TABLE `offers`
  ADD CONSTRAINT `offers_ibfk_1` FOREIGN KEY (`offered_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `offers_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `offers_ibfk_3` FOREIGN KEY (`offered_item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
