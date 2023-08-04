-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 04. Aug 2023 um 14:31
-- Server-Version: 10.4.28-MariaDB
-- PHP-Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `xiks5egieksn6c6a`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cart`
--

CREATE TABLE `cart` (
  `cart_item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `challenges`
--

CREATE TABLE `challenges` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(2000) NOT NULL,
  `points` int(11) NOT NULL,
  `time_minutes` int(11) NOT NULL,
  `solved` tinyint(1) NOT NULL DEFAULT 0,
  `solution` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `challenges`
--

INSERT INTO `challenges` (`id`, `name`, `description`, `points`, `time_minutes`, `solved`, `solution`) VALUES
(1, 'What a wierd name', 'Find a deactivated user with an wierd username and submit it as a flag.', 0, 0, 0, '8679e8d418ed5a517da62829bf5064f78d49a1479818bbe69598b65a20c151f17e231d9b1bb48ff40dc7ac69bada186aa7ada0b1b0eb3ef8d83bef70a8abc7cf'),
(2, 'Directory Listing', 'Leak a file with an uncommon name and submit it here. ', 20, 20, 0, 'b82dceada27026617d176e258bdd1425990ef7935af02731955d2aba7d9d3caad144cc11987be9a0308e9bd917c839e3afedede7b996bbedd3ed5597c8da7f75');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL,
  `quantity` int(5) NOT NULL,
  `image_path` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `quantity`, `image_path`) VALUES
(1, 'Haselnüsse', 'Wer kennt sie nicht? Die Haselnüsse. Sind schön Vitamin B haltig und schmecken einfach gut.', 88, 'images/haselnuss.jpg'),
(2, 'Mandeln', 'Die stecken nicht im Hals.', 98, 'images/mandeln.jpg'),
(3, 'Paranüsse', 'Diese Form erinnert mich an Bananen...', 79, 'images/paranüsse.jpg'),
(4, 'Walnüsse', 'Die schmecken einfach gut.', 51, 'images/walnuss.jpg');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `active`) VALUES
(7, 'admin', '683d696ae7f5ee157bbbe01a339126d597dde353599641e5209c8e3ccc7b1b4d849f2ec19ba60a5983a8aaa98bbfeffe94729bbc5a08734c8e01927b01bd5312', 'adminmail@thisisnuts.de', 'admin', 1),
(12, 'C-THATS-AWIER-DNAME', 'f69fcda0e4e3b794f81c3d56819e35d8483fe337ca226aa6d14ae2021a97a2bfa5c415b7570ccaa859d9fb001fd576b6d74bc3803b69c08547aa53f305fb7abc', 'wierduser@thisisnuts.de', 'user', 0),
(20, 'user', '2c2b90a03e67f2b04455a61d3e1465b74b7745d5b4dc640a119c8726e24ccb7f33c77d9e7041ae91ca0ec72298564ed9c5928b60f07b48c1e77a70a18c65c94a', 'user@thisisnuts.de', 'user', 1);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_item_id`);

--
-- Indizes für die Tabelle `challenges`
--
ALTER TABLE `challenges`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT für Tabelle `challenges`
--
ALTER TABLE `challenges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
