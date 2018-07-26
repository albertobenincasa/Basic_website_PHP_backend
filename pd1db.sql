-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Lug 18, 2018 alle 16:27
-- Versione del server: 10.1.33-MariaDB
-- Versione PHP: 7.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pd1db`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `gameboard`
--

CREATE TABLE `gameboard` (
  `user` varchar(100) NOT NULL,
  `insert_time` datetime NOT NULL,
  `x0` int(11) NOT NULL,
  `y0` int(11) NOT NULL,
  `x1` int(11) NOT NULL,
  `y1` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `gameboard`
--

INSERT INTO `gameboard` (`user`, `insert_time`, `x0`, `y0`, `x1`, `y1`) VALUES
('u1@p.it', '2018-07-18 16:23:54', 0, 3, 0, 6),
('u2@p.it', '2018-07-18 16:25:34', 3, 1, 6, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`id`, `email`, `password`) VALUES
(1, 'u1@p.it', 'bf81c4f4f47d5b6cc747bb62597abfb3'),
(2, 'u2@p.it', 'b36a15671c6d3d2afd5a0b1290c4e341');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `gameboard`
--
ALTER TABLE `gameboard`
  ADD PRIMARY KEY (`user`,`insert_time`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
