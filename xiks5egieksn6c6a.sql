-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 08. Aug 2023 um 18:25
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
(0, 'Get VIP', 'It is important to keep your code tidy. Find a way to get the admin role and submit the flag displayed in the admin section.', 10, 15, 0, 'f06d3e9c250cc7da9866e3f1801c5b32e175f63cf2e0b6d6d7da653081a2427abedf64cdccc36939d79dcc45e1ccf7374ee074acccef22960911520714c2c88e'),
(1, 'What a wierd name', 'Find a deactivated user with an wierd username and submit it as a flag.', 20, 15, 0, 'ecc7dccbf51f5d0c719d7d03842f508a1fbea5df098971c3a29367bd8d5b35c6d5378dd44cd8052b73cea77e9a382a6274099f28369f62891274c603523a9149'),
(2, 'Arbitrary File Read', 'Leak the contents of the file flag.txt and submit it here. ', 30, 20, 0, '6c19fdbf5a7c53a3b0c7c75bb86bd11fdcefe7c2fca6bfd29d68d14d5c8d2654dab44ff4ff7c57a5eec0ee5701b717a868a28e3bf8707c22fa9d96bf1aeaeb57');

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
(36, '1', '1', 1, 'uploads/../flag.txt'),
(37, 'test', '1', 1, 'uploads/../api.php');

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
(7, 'admin', 'b1bdb5e53a6afd54d4f5d4bbebb984c96b55b2892a63448a463971c41124b84ec0cfdc2fae3b5149ffc2652ad074082e01bd88a6ed2866dfeae97d90e210c134', 'adminmail@thisisnuts.de', 'admin', 1),
(12, 'C{PrE11yW1eRdN4Me}', 'f69fcda0e4e3b794f81c3d56819e35d8483fe337ca226aa6d14ae2021a97a2bfa5c415b7570ccaa859d9fb001fd576b6d74bc3803b69c08547aa53f305fb7abc', 'wierduser@thisisnuts.de', 'user', 0),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
