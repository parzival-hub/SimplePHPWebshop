-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 16. Aug 2022 um 12:19
-- Server-Version: 10.4.24-MariaDB
-- PHP-Version: 8.1.6

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
-- Tabellenstruktur für Tabelle `admin`
--

CREATE TABLE `admin` (
  `name` varchar(255) NOT NULL,
  `quantity` int(3) DEFAULT NULL,
  `description` varchar(500) NOT NULL,
  `image_path` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `billboard`
--

CREATE TABLE `billboard` (
  `name` varchar(255) NOT NULL,
  `quantity` int(3) DEFAULT NULL,
  `description` varchar(500) NOT NULL,
  `image_path` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
--
-- Tabellenstruktur für Tabelle `products`
--

CREATE TABLE `products` (
  `name` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL,
  `quantity` int(5) NOT NULL,
  `image_path` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `products`
--

INSERT INTO `products` (`name`, `description`, `quantity`, `image_path`) VALUES
('Haselnüsse', 'Wer kennt sie nicht? Die Haselnüsse. Sind schön Vitamin B haltig und schmecken einfach gut.', 93, 'images/haselnuss.jpg'),
('Mandeln', 'Die stecken nicht im Hals.', 18, 'images/mandeln.jpg'),
('Paranüsse', 'Diese Form erinnert mich an Bananen...', 22, 'images/paranüsse.jpg'),
('Walnüsse', 'Die schmecken einfach gut.', 51, 'images/walnuss.jpg');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `username` varchar(100) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`username`, `password`, `email`, `role`) VALUES
('admin', '33f61a1722992f7817fbb37e671464746aadfe68e4ec6f117a214c7cf895aa08144b0d0c6c1e44206da649ae28a01aa7ac6953205edb0dda300beb63f380fde5', 'admin@thisisnuts.de', '  admin  '),
('JimJizz', '3f701479f7e1ffce4dc44576f284bb5ee458372863b5aac7926bb07fcebd33408b67d55c605a49bf3a6f62cd24823c84c1778822fbaf1414c14d49bbe479f28d', 'jim.jizz@thisisnuts.de', '  user  '),
('MistyGurl', '64a240daed99995ed28309fa4ee7e4a3464139fb5f320596e8b29948999dd83de01c59dbf415ea33d5de7a5633b5d4ccbc51170ca1b1b665c793046211f81513', 'misty.gurl@thisisnuts.de', '  user  '),
('BillBoard', '9151838deab3292c202e92e00592e6bd7aaa61c4830ca70b01335d1bc584133f5474a0caf12ec1412d23c2b01cc3c7c54f9e62765de479353643959497a70e29', 'bill.board@thisisnuts.de', '  user  ');


--
-- Indizes für die Tabelle `products`
--
ALTER TABLE `products`
  ADD UNIQUE KEY `name` (`name`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
