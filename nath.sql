-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-03-2022 a las 09:48:38
-- Versión del servidor: 10.4.22-MariaDB
-- Versión de PHP: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `nath`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activitats`
--

CREATE TABLE `activitats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `treballador` bigint(20) UNSIGNED DEFAULT NULL,
  `dia` date DEFAULT NULL,
  `tasca` bigint(20) UNSIGNED DEFAULT NULL,
  `total` double(8,2) DEFAULT NULL,
  `tipusTasca` bigint(20) UNSIGNED DEFAULT NULL,
  `geolocation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `info` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iniciTasca` datetime DEFAULT NULL,
  `fiTasca` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jornades`
--

CREATE TABLE `jornades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dia` date DEFAULT NULL,
  `treballador` bigint(20) UNSIGNED DEFAULT NULL,
  `total` double(8,2) DEFAULT NULL,
  `geolocation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `info` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tasks_type`
--

CREATE TABLE `tasks_type` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tipus` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tasks_type`
--

INSERT INTO `tasks_type` (`id`, `tipus`, `created_at`, `updated_at`) VALUES
(1, 'Pedidos', '2022-03-25 09:36:47', '2022-03-25 09:36:47'),
(2, 'Recepciones', '2022-03-25 09:36:47', '2022-03-25 09:36:47'),
(3, 'Reoperaciones', '2022-03-25 09:36:47', '2022-03-25 09:36:47'),
(4, 'Inventario', '2022-03-25 09:36:47', '2022-03-25 09:36:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tasques`
--

CREATE TABLE `tasques` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tasca` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipusTasca` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tasques`
--

INSERT INTO `tasques` (`id`, `tasca`, `tipusTasca`, `created_at`, `updated_at`) VALUES
(1, 'Preparación pedido', 1, '2022-03-25 09:36:47', '2022-03-25 09:36:47'),
(2, 'Revisión pedido', 1, '2022-03-25 09:36:47', '2022-03-25 09:36:47'),
(3, 'Expedición', 1, '2022-03-25 09:36:47', '2022-03-25 09:36:47'),
(4, 'SAF', 1, '2022-03-25 09:36:47', '2022-03-25 09:36:47'),
(5, 'Recepcio1', 2, '2022-03-25 09:36:47', '2022-03-25 09:36:47'),
(6, 'Recepcio2', 2, '2022-03-25 09:36:47', '2022-03-25 09:36:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `torns`
--

CREATE TABLE `torns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jornada` date NOT NULL,
  `treballador` bigint(20) UNSIGNED NOT NULL,
  `total` double(8,2) DEFAULT NULL,
  `geolocation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `info` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iniciTorn` datetime DEFAULT NULL,
  `fiTorn` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `magatzem` tinyint(1) NOT NULL,
  `administrador` tinyint(1) DEFAULT NULL,
  `DNI` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `activitats`
--
ALTER TABLE `activitats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activitats_treballador_foreign` (`treballador`),
  ADD KEY `activitats_tasca_foreign` (`tasca`),
  ADD KEY `activitats_tipustasca_foreign` (`tipusTasca`);

--
-- Indices de la tabla `jornades`
--
ALTER TABLE `jornades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jornades_treballador_foreign` (`treballador`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `tasks_type`
--
ALTER TABLE `tasks_type`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tasques`
--
ALTER TABLE `tasques`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasques_tipustasca_foreign` (`tipusTasca`);

--
-- Indices de la tabla `torns`
--
ALTER TABLE `torns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `torns_treballador_foreign` (`treballador`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_dni_unique` (`DNI`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `activitats`
--
ALTER TABLE `activitats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jornades`
--
ALTER TABLE `jornades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tasks_type`
--
ALTER TABLE `tasks_type`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tasques`
--
ALTER TABLE `tasques`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `torns`
--
ALTER TABLE `torns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `activitats`
--
ALTER TABLE `activitats`
  ADD CONSTRAINT `activitats_tasca_foreign` FOREIGN KEY (`tasca`) REFERENCES `tasques` (`id`),
  ADD CONSTRAINT `activitats_tipustasca_foreign` FOREIGN KEY (`tipusTasca`) REFERENCES `tasks_type` (`id`),
  ADD CONSTRAINT `activitats_treballador_foreign` FOREIGN KEY (`treballador`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `jornades`
--
ALTER TABLE `jornades`
  ADD CONSTRAINT `jornades_treballador_foreign` FOREIGN KEY (`treballador`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `tasques`
--
ALTER TABLE `tasques`
  ADD CONSTRAINT `tasques_tipustasca_foreign` FOREIGN KEY (`tipusTasca`) REFERENCES `tasks_type` (`id`);

--
-- Filtros para la tabla `torns`
--
ALTER TABLE `torns`
  ADD CONSTRAINT `torns_treballador_foreign` FOREIGN KEY (`treballador`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
