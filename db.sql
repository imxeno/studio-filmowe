-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 14 Wrz 2018, 23:30
-- Wersja serwera: 5.7.23-0ubuntu0.16.04.1
-- Wersja PHP: 7.0.30-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `studio`
--
CREATE DATABASE IF NOT EXISTS `studio` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `studio`;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `positions`
--

CREATE TABLE `positions` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `access_level` tinyint(4) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `positions`
--

INSERT INTO `positions` (`id`, `name`, `access_level`) VALUES
(1, 'Właściciel', 255),
(2, 'Klient', 0),
(4, 'Kierownik produkcji', 200),
(5, 'Producent filmowy', 200),
(6, 'Kierownik planu', 200),
(7, 'Reżyser', 200),
(8, 'Asystent reżysera', 200),
(9, 'Operator filmowy', 200),
(10, 'Operator kamer', 200),
(11, 'Scenarzysta', 200),
(12, 'Oświetleniowiec', 200),
(13, 'Charakteryzator', 200),
(14, 'Scenograf', 200),
(15, 'Kostiumolog', 200),
(16, 'Dźwiękowiec', 200),
(17, 'Montażysta', 200),
(18, 'Kompozytor', 200),
(19, 'Rekwizytor', 200),
(20, 'Klapster', 200),
(21, 'Dyżurny planu', 200),
(22, 'Aktor', 200),
(23, 'Dubler', 200),
(24, 'Statysta', 200);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `productions`
--

CREATE TABLE `productions` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `premiere` datetime NOT NULL,
  `genre` varchar(32) NOT NULL,
  `costs` double NOT NULL,
  `contracting_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `productions`
--

INSERT INTO `productions` (`id`, `name`, `premiere`, `genre`, `costs`, `contracting_id`) VALUES
(2, 'Przygody Dużego Krzysztofa', '2019-01-17 00:00:00', 'Thriller', 1234, 2),
(3, 'Testowy thriller', '2019-09-26 00:00:00', 'Horror', 2001, 2);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(32) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `salt` varchar(32) DEFAULT NULL,
  `first_name` mediumtext NOT NULL,
  `last_name` mediumtext NOT NULL,
  `phone` varchar(16) NOT NULL,
  `address` mediumtext NOT NULL,
  `position` int(11) NOT NULL DEFAULT '2',
  `salary` double DEFAULT NULL,
  `agreement_signed` datetime DEFAULT NULL,
  `agreement_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `salt`, `first_name`, `last_name`, `phone`, `address`, `position`, `salary`, `agreement_signed`, `agreement_expires`) VALUES
(1, 'wlasciciel', 'fb43b8614b258a1e7a0551f2edc77167433aac06323ad9e61fb9f66f0d0d0df8989b42a4d629f7bf881c36e29c7964656d0fe34c5e45e163cbde23cf0b591fad', 'OnSu26xojRJBF0XJF7yn3KzZXHa32W4R', 'Tester', 'Adamczyk', '+48123456789', 'ul. Właścicielska 2/10, 26-600 Wiocha', 1, 21234.56, '2018-09-01 00:00:00', NULL),
(2, NULL, NULL, NULL, 'Klientela', 'Klientowska', '+123123123456789', 'ul. Kliencka 6/9, 26-600 Wiocha', 2, 0, NULL, NULL),
(6, NULL, NULL, NULL, 'Aneta', 'Adamczyk', '123456789', 'ul. Księdza Ignacego Skorupki 6/9', 2, NULL, NULL, NULL),
(8, 'testowy', 'f0c66329e7f2fe7465d499a45e4707a56ae06557ad3c8f647c893290b6c532a14e3e39b51a106b4b73f3310cd6f29a9daf0995e2166eacaea4af883fc12ebd3a', 'cLXiz7SQSf1Vr27C22TwDdsDMlnc3i7B', 'Krzysztof', 'Adamczuk', '+48788045685', 'ul. Testowa 2/10, 12-264 Wiocha', 7, 20000, '1995-06-18 00:00:00', '2456-03-12 00:00:00');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productions`
--
ALTER TABLE `productions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contracting_id` (`contracting_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `position` (`position`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT dla tabeli `productions`
--
ALTER TABLE `productions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`position`) REFERENCES `positions` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
