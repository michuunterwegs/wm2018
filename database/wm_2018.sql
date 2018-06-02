-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2018 at 12:06 PM
-- Server version: 10.1.29-MariaDB
-- PHP Version: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wm_2018`
--
CREATE DATABASE IF NOT EXISTS `wm_2018` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `wm_2018`;

-- --------------------------------------------------------

--
-- Table structure for table `remembered_logins`
--

CREATE TABLE `remembered_logins` (
  `token_hash` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` tinyint(4) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` tinyint(4) NOT NULL,
  `user_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_reset_hash` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_reset_expires_at` datetime DEFAULT NULL,
  `activation_hash` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `login_count` smallint(6) DEFAULT '0',
  `creation_date` datetime NOT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_matches`
-- (See below for the actual view)
--
CREATE TABLE `view_matches` (
`match_start` datetime
,`team_1` varchar(20)
,`team_1_id` int(11)
,`team_2` varchar(20)
,`team_2_id` int(11)
,`match_type` varchar(25)
,`match_type_id` int(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `wc_groups`
--

CREATE TABLE `wc_groups` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wc_groups`
--

INSERT INTO `wc_groups` (`id`, `name`) VALUES
(1, 'A'),
(2, 'B'),
(3, 'C'),
(4, 'D'),
(5, 'E'),
(6, 'F'),
(7, 'G'),
(8, 'H'),
(9, 'Z');

-- --------------------------------------------------------

--
-- Table structure for table `wc_matches`
--

CREATE TABLE `wc_matches` (
  `id` int(11) NOT NULL,
  `match_start` datetime NOT NULL,
  `team_1_id` int(11) DEFAULT NULL,
  `team_2_id` int(11) DEFAULT NULL,
  `match_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wc_matches`
--

INSERT INTO `wc_matches` (`id`, `match_start`, `team_1_id`, `team_2_id`, `match_type_id`) VALUES
(1, '2018-05-14 17:00:00', 23, 24, 1),
(2, '2018-05-15 14:00:00', 1, 32, 1),
(3, '2018-05-15 17:00:00', 16, 11, 1),
(4, '2018-05-15 20:00:00', 22, 29, 1),
(5, '2018-05-16 12:00:00', 10, 3, 1),
(6, '2018-05-16 15:00:00', 2, 12, 1),
(7, '2018-05-16 18:00:00', 20, 7, 1),
(8, '2018-05-16 21:00:00', 15, 18, 1),
(9, '2018-05-17 14:00:00', 6, 28, 1),
(10, '2018-05-17 17:00:00', 8, 17, 1),
(11, '2018-05-17 20:00:00', 5, 26, 1),
(12, '2018-05-18 14:00:00', 25, 30, 1),
(13, '2018-05-18 17:00:00', 4, 19, 1),
(14, '2018-05-18 20:00:00', 31, 9, 1),
(15, '2018-05-19 14:00:00', 14, 13, 1),
(16, '2018-05-19 17:00:00', 21, 27, 1),
(17, '2018-05-19 20:00:00', 23, 1, 2),
(18, '2018-05-20 14:00:00', 22, 16, 2),
(19, '2018-05-20 17:00:00', 32, 24, 2),
(20, '2018-05-20 20:00:00', 11, 29, 2),
(21, '2018-05-21 14:00:00', 7, 3, 2),
(22, '2018-05-21 17:00:00', 10, 20, 2),
(23, '2018-05-21 20:00:00', 2, 15, 2),
(24, '2018-05-22 14:00:00', 5, 6, 2),
(25, '2018-05-22 17:00:00', 18, 12, 2),
(26, '2018-05-22 20:00:00', 28, 26, 2),
(27, '2018-05-23 14:00:00', 4, 31, 2),
(28, '2018-05-23 17:00:00', 30, 17, 2),
(29, '2018-05-23 20:00:00', 8, 25, 2),
(30, '2018-05-24 14:00:00', 9, 19, 2),
(31, '2018-05-24 17:00:00', 13, 27, 2),
(32, '2018-05-24 20:00:00', 21, 14, 2),
(33, '2018-05-25 16:00:00', 32, 23, 3),
(34, '2018-05-25 16:00:00', 24, 1, 3),
(35, '2018-05-25 20:00:00', 29, 16, 3),
(36, '2018-05-25 20:00:00', 11, 22, 3),
(37, '2018-05-26 16:00:00', 3, 20, 3),
(38, '2018-05-26 16:00:00', 7, 10, 3),
(39, '2018-05-26 20:00:00', 18, 2, 3),
(40, '2018-05-26 20:00:00', 12, 15, 3),
(41, '2018-05-27 16:00:00', 30, 8, 3),
(42, '2018-05-27 16:00:00', 17, 25, 3),
(43, '2018-05-27 20:00:00', 28, 5, 3),
(44, '2018-05-27 20:00:00', 26, 6, 3),
(45, '2018-05-28 16:00:00', 13, 21, 3),
(46, '2018-05-28 16:00:00', 27, 14, 3),
(47, '2018-05-28 20:00:00', 19, 31, 3),
(48, '2018-05-28 20:00:00', 9, 4, 3),
(49, '2018-06-30 16:00:00', 33, 33, 4),
(50, '2018-06-30 20:00:00', 33, 33, 4),
(51, '2018-07-01 16:00:00', 33, 33, 4),
(52, '2018-07-01 20:00:00', 33, 33, 4),
(53, '2018-07-02 16:00:00', 33, 33, 4),
(54, '2018-07-02 20:00:00', 33, 33, 4),
(55, '2018-07-03 16:00:00', 33, 33, 4),
(56, '2018-07-03 20:00:00', 33, 33, 4),
(57, '2018-07-06 16:00:00', 33, 33, 5),
(58, '2018-07-06 20:00:00', 33, 33, 5),
(59, '2018-07-07 16:00:00', 33, 33, 5),
(60, '2018-07-07 20:00:00', 33, 33, 5),
(61, '2018-07-10 20:00:00', 33, 33, 6),
(62, '2018-07-11 20:00:00', 33, 33, 6),
(63, '2018-07-14 16:00:00', 33, 33, 7),
(64, '2018-07-15 17:00:00', 33, 33, 8);

-- --------------------------------------------------------

--
-- Table structure for table `wc_match_types`
--

CREATE TABLE `wc_match_types` (
  `id` int(11) NOT NULL,
  `type` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wc_match_types`
--

INSERT INTO `wc_match_types` (`id`, `type`) VALUES
(1, '1. Gruppenphase'),
(2, '2. Gruppenphase'),
(3, '3. Gruppenphase'),
(4, 'Achtelfinale'),
(5, 'Viertelfinale'),
(6, 'Halbfinale'),
(7, 'Spiel um Platz 3'),
(8, 'Finale');

-- --------------------------------------------------------

--
-- Table structure for table `wc_teams`
--

CREATE TABLE `wc_teams` (
  `id` int(11) NOT NULL,
  `country` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_id` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wc_teams`
--

INSERT INTO `wc_teams` (`id`, `country`, `group_id`) VALUES
(1, 'Ägypten', 1),
(2, 'Argentinien', 4),
(3, 'Australien', 3),
(4, 'Belgien', 7),
(5, 'Brasilien', 5),
(6, 'Costa Rica', 5),
(7, 'Dänemark', 3),
(8, 'Deutschland', 6),
(9, 'England', 7),
(10, 'Frankreich', 3),
(11, 'Iran', 2),
(12, 'Island', 4),
(13, 'Japan', 8),
(14, 'Kolumbien', 8),
(15, 'Kroatien', 4),
(16, 'Marokko', 2),
(17, 'Mexiko', 6),
(18, 'Nigeria', 4),
(19, 'Panama', 7),
(20, 'Peru', 3),
(21, 'Polen', 8),
(22, 'Portugal', 2),
(23, 'Russland', 1),
(24, 'Saudi-Arabien', 1),
(25, 'Schweden', 6),
(26, 'Schweiz', 5),
(27, 'Senegal', 8),
(28, 'Serbien', 5),
(29, 'Spanien', 2),
(30, 'Südkorea', 6),
(31, 'Tunesien', 7),
(32, 'Uruguay', 1),
(33, 'Unbekannt', 9);

-- --------------------------------------------------------

--
-- Structure for view `view_matches`
--
DROP TABLE IF EXISTS `view_matches`;

CREATE VIEW `view_matches`  AS  select `wc_matches`.`match_start` AS `match_start`,`wc_teams_1`.`country` AS `team_1`,`wc_matches`.`team_1_id` AS `team_1_id`,`wc_teams_2`.`country` AS `team_2`,`wc_matches`.`team_2_id` AS `team_2_id`,`wc_match_types`.`type` AS `match_type`,`wc_matches`.`match_type_id` AS `match_type_id` from (((`wc_matches` join `wc_teams` `wc_teams_1` on((`wc_teams_1`.`id` = `wc_matches`.`team_1_id`))) join `wc_teams` `wc_teams_2` on((`wc_teams_2`.`id` = `wc_matches`.`team_2_id`))) join `wc_match_types` on((`wc_match_types`.`id` = `wc_matches`.`match_type_id`))) order by `wc_matches`.`match_start` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `remembered_logins`
--
ALTER TABLE `remembered_logins`
  ADD PRIMARY KEY (`token_hash`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_hash` (`password_reset_hash`),
  ADD UNIQUE KEY `activation_hash` (`activation_hash`);

--
-- Indexes for table `wc_groups`
--
ALTER TABLE `wc_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `wc_matches`
--
ALTER TABLE `wc_matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `team_1_id` (`team_1_id`),
  ADD KEY `team_2_id` (`team_2_id`),
  ADD KEY `type` (`match_type_id`);

--
-- Indexes for table `wc_match_types`
--
ALTER TABLE `wc_match_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wc_teams`
--
ALTER TABLE `wc_teams`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `country` (`country`),
  ADD KEY `group_id` (`group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wc_groups`
--
ALTER TABLE `wc_groups`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `wc_matches`
--
ALTER TABLE `wc_matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `wc_match_types`
--
ALTER TABLE `wc_match_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `wc_teams`
--
ALTER TABLE `wc_teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `remembered_logins`
--
ALTER TABLE `remembered_logins`
  ADD CONSTRAINT `remembered_logins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wc_matches`
--
ALTER TABLE `wc_matches`
  ADD CONSTRAINT `wc_matches_ibfk_1` FOREIGN KEY (`team_1_id`) REFERENCES `wc_teams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `wc_matches_ibfk_2` FOREIGN KEY (`team_2_id`) REFERENCES `wc_teams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `wc_matches_ibfk_3` FOREIGN KEY (`match_type_id`) REFERENCES `wc_match_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wc_teams`
--
ALTER TABLE `wc_teams`
  ADD CONSTRAINT `wc_teams_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `wc_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
