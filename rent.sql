-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 19 Lut 2019, 00:07
-- Wersja serwera: 10.1.8-MariaDB
-- Wersja PHP: 5.6.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `rent`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `parameters`
--

CREATE TABLE `parameters` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `parameters`
--

INSERT INTO `parameters` (`id`, `name`) VALUES
(46, '2GB RAM'),
(48, 'Dysk 250 Gb'),
(155, 'Zasilanie 12 v'),
(157, 'Skóra'),
(158, 'Ekoskóra'),
(159, 'Zamsz'),
(160, 'Udarowa'),
(161, 'Koła 20\"'),
(162, 'Aluminiowa rama');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parameters` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `endRentDate` datetime NOT NULL,
  `thumb` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `products`
--

INSERT INTO `products` (`id`, `name`, `parameters`, `status`, `endRentDate`, `thumb`) VALUES
(64, 'Rower Giat lekka rama aluminiowa', '{\"params\":[\"162\",\"161\",\"157\"]}', 2, '0000-00-00 00:00:00', 'bike.jpg'),
(66, 'Zegarek elektroniczny Casio B244', '{\"params\":[\"46\",\"48\",\"158\",\"155\"]}', 1, '2019-02-23 15:00:00', 'zegarek.jpg'),
(69, 'AGR Tablet 32GB', '{\"params\":[\"46\",\"158\",\"160\",\"155\"]}', 0, '0000-00-00 00:00:00', 'tablet.jpg'),
(70, 'Klucz udarowy Bosch', '{\"params\":[\"160\",\"155\",\"46\",\"158\"]}', 0, '0000-00-00 00:00:00', 'klucz.jpg'),
(71, 'Rozrusznik do malucha', '{\"params\":[\"46\",\"157\",\"159\",\"155\"]}', 0, '0000-00-00 00:00:00', 'rozrusznik.jpg'),
(72, 'Buty Nike Jordan 4', '{\"params\":[\"157\"]}', 0, '0000-00-00 00:00:00', 'jordan.jpg'),
(107, 'aaaa franek kimono', '{\"params\":[\"162\",\"46\",\"48\",\"161\",\"157\",\"160\",\"159\",\"155\",\"158\"]}', 0, '0000-00-00 00:00:00', 'bizu.jpg'),
(108, 'test', '{\"params\":[\"46\",\"162\",\"48\",\"158\",\"161\",\"157\",\"160\",\"155\",\"159\"]}', 0, '0000-00-00 00:00:00', ''),
(110, 'test 23', '{\"params\":[\"158\",\"48\",\"162\",\"155\",\"160\"]}', 1, '2019-02-19 15:00:00', '');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `rent`
--

CREATE TABLE `rent` (
  `id` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `rentDate` datetime NOT NULL,
  `days` int(11) NOT NULL,
  `price` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `rent`
--

INSERT INTO `rent` (`id`, `productId`, `rentDate`, `days`, `price`) VALUES
(43, 22, '2019-02-06 15:00:00', 4, '200'),
(44, 22, '2019-02-16 15:00:00', 3, '100'),
(45, 16, '2019-02-20 15:00:00', 2, '2'),
(46, 16, '2019-02-21 15:00:00', 2, '2'),
(47, 16, '2019-02-06 15:00:00', 2, '2'),
(48, 16, '2019-02-10 15:00:00', 7, '2'),
(49, 16, '2019-02-10 15:00:00', 7, '2'),
(50, 16, '2019-02-10 15:00:00', 7, '2'),
(51, 16, '2019-02-17 15:00:00', 4, '200'),
(52, 16, '2019-02-16 15:00:00', 5, '100'),
(53, 16, '2019-02-19 15:00:00', 2, '122'),
(54, 16, '2019-02-19 15:00:00', 3, '123'),
(55, 16, '2019-02-20 15:00:00', 4, '222'),
(56, 16, '2019-02-14 15:00:00', 3, '2'),
(57, 16, '2019-02-20 15:00:00', 2, '222'),
(58, 16, '2019-02-20 15:00:00', 4, '123'),
(59, 16, '2019-02-20 15:00:00', 14, '231'),
(60, 14, '2019-02-17 15:00:00', 3, '12'),
(61, 58, '2019-02-15 15:00:00', 2, '2'),
(62, 66, '2019-02-16 15:00:00', 2, '122'),
(63, 14, '2019-02-16 15:00:00', 4, '122'),
(64, 66, '2019-02-18 15:00:00', 1, '130'),
(65, 66, '2019-02-18 15:00:00', 5, '140'),
(66, 110, '2019-02-18 15:00:00', 1, '200');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `parameters`
--
ALTER TABLE `parameters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `status` (`id`);

--
-- Indexes for table `rent`
--
ALTER TABLE `rent`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `parameters`
--
ALTER TABLE `parameters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT dla tabeli `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT dla tabeli `rent`
--
ALTER TABLE `rent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
