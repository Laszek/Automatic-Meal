-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 18 Lis 2019, 02:48
-- Wersja serwera: 10.1.37-MariaDB
-- Wersja PHP: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `automatic_meal`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `menu`
--

CREATE TABLE `menu` (
  `idproduktu` int(11) NOT NULL,
  `typ` text COLLATE utf8_bin NOT NULL,
  `nazwa` text COLLATE utf8_bin NOT NULL,
  `opis` text COLLATE utf8_bin NOT NULL,
  `cena` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Zrzut danych tabeli `menu`
--

INSERT INTO `menu` (`idproduktu`, `typ`, `nazwa`, `opis`, `cena`) VALUES
(1, 'przystawki', 'ŁOSOŚ JURAJSKI', '200 g <br>\r\nW chrzanie i burakach marynowany, na pumperniklu z sosem koperkowym serwowany', '38.00'),
(2, 'przystawki', 'RYDZE NA MAŚLE SMAŻONE', '250g<br>\r\nserem Grana Padano okryte', '40.00'),
(3, 'przystawki', 'ŚLIWKI I MORELE W BOCZEK ZAWIJANE', '8 szt.', '24.00'),
(4, 'przystawki', 'GÓRSKIE PLACUSZKI Z MASŁEM CZOSNKOWYM', '200g', '20.00'),
(5, 'zupy', 'ZUPA BOROWIKOWA Z KLUSECZKAMI KŁADZIONYMI', '500ml', '26.00'),
(6, 'zupy', 'KWAŚNICA Z ŻEBERKIEM', '500ml<br>\r\ndla zdrożonych', '18.00'),
(7, 'zupy', 'KREM BROKUŁOWO – CZOSNKOWY', '500ml<br>\r\nz grzankami', '18.00'),
(8, 'zupy', 'KRUPNIK NA PIERSI KURCZAKA ROBIONY', '500ml', '18.00'),
(9, 'zupy', 'GÓRSKI KOCIOŁEK', '500ml<br>\r\nzupa z kawałkami mięsa, warzywami i kładzionymi kluseczkami', '36.00'),
(10, 'zupy', 'KREM Z BURAKÓW PIECZONYCH', '300ml/100g<br>\r\nz oscypkową nutą, z raviołami jagnięciną podhalańską nadziewanymi', '32.00'),
(11, 'salaty', 'SAŁATKA WĘDROWCA', '400g<br>\r\nsałata lodowa z pomidorem, cebulą, ogórkiem, papryką, rzodkiewką, oliwkami i fetą oraz sosem', '26.00'),
(12, 'salaty', 'SZWAJCARSKA SAŁATA ZIEMNIACZANA', '400g<br>\r\nz szynką, korniszonami, cebulą i sosem majonezowym, białym winem i tymiankiem', '22.00'),
(13, 'nalesniki', 'ALPEJSKI DIABEŁ', '450g<br>\r\nnaleśniki w wołowiną i czerwoną fasolą na ostro, z serem zapiekane, pikantnym sosem pomidorowym polane', '30.00'),
(14, 'nalesniki', 'SZYBUJĄCY PRZYSMAK', '400g<br>\r\nnaleśniki z kurczakiem, z grzybami i cebulą, sosem grzybowym serwowane', '29.00'),
(15, 'nalesniki', 'WEGETARIAŃSKI SPECJAŁ', '400g<br>\r\nnaleśniki z różnymi warzywami z serem zapiekane, sosem koperkowym polane', '28.00'),
(16, 'makarony', 'MAKARON MATYLDY', '400 g<br>\r\nPappardelle w sosie śmietanowym z cielęciną i kurkami', '38.00'),
(18, 'napoje', 'HERBATA PO GÓRALSKU', '400ml<br>\r\nherbata z prundem', '18.00'),
(21, 'napoje', 'Kawa', '250ml<br>Wybrany typ kawy', '12.00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pracownicy`
--

CREATE TABLE `pracownicy` (
  `ID` int(11) NOT NULL,
  `Imie` text COLLATE utf8_bin NOT NULL,
  `Nazwisko` text COLLATE utf8_bin NOT NULL,
  `Stanowisko` text COLLATE utf8_bin NOT NULL,
  `Login` text COLLATE utf8_bin NOT NULL,
  `Haslo` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Zrzut danych tabeli `pracownicy`
--

INSERT INTO `pracownicy` (`ID`, `Imie`, `Nazwisko`, `Stanowisko`, `Login`, `Haslo`) VALUES
(1, 'Maciej', 'Łaszewski', '1', 'guest', '1');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `stanowiska`
--

CREATE TABLE `stanowiska` (
  `ID` int(11) NOT NULL,
  `Nazwa` varchar(11) COLLATE utf8_bin NOT NULL,
  `Poziom uprawnień` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Zrzut danych tabeli `stanowiska`
--

INSERT INTO `stanowiska` (`ID`, `Nazwa`, `Poziom uprawnień`) VALUES
(1, 'Kierownik', 1),
(2, 'Kelner', 2),
(3, 'Kucharz', 3);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zamowienia`
--

CREATE TABLE `zamowienia` (
  `idzamowienia` int(11) NOT NULL,
  `nr_stolika` int(11) NOT NULL,
  `zamowiono` text COLLATE utf8_bin NOT NULL,
  `obsluga` text COLLATE utf8_bin NOT NULL,
  `status` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Zrzut danych tabeli `zamowienia`
--

INSERT INTO `zamowienia` (`idzamowienia`, `nr_stolika`, `zamowiono`, `obsluga`, `status`) VALUES
(12, 9, '6,8,21,21,', 'xd', 'przygotowywanie');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`idproduktu`);

--
-- Indeksy dla tabeli `pracownicy`
--
ALTER TABLE `pracownicy`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `stanowiska`
--
ALTER TABLE `stanowiska`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `zamowienia`
--
ALTER TABLE `zamowienia`
  ADD PRIMARY KEY (`idzamowienia`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `menu`
--
ALTER TABLE `menu`
  MODIFY `idproduktu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT dla tabeli `pracownicy`
--
ALTER TABLE `pracownicy`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT dla tabeli `stanowiska`
--
ALTER TABLE `stanowiska`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT dla tabeli `zamowienia`
--
ALTER TABLE `zamowienia`
  MODIFY `idzamowienia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
