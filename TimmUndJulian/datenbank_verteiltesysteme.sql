-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 03. Mrz 2025 um 14:46
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `datenbank_verteiltesysteme`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bestellpositionen`
--

CREATE TABLE `bestellpositionen` (
  `PositionID` int(11) NOT NULL,
  `BestellungID` int(11) DEFAULT NULL,
  `ProduktID` int(11) DEFAULT NULL,
  `ProduktName` varchar(255) DEFAULT NULL,
  `Preis` decimal(10,2) DEFAULT NULL,
  `Menge` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `bestellpositionen`
--

INSERT INTO `bestellpositionen` (`PositionID`, `BestellungID`, `ProduktID`, `ProduktName`, `Preis`, `Menge`) VALUES
(1, 1, 36, 'Kalbfleisch Wrap', 9.50, 1),
(2, 1, 82, 'Red Bull 0,25l', 3.00, 1),
(3, 2, 122, 'Chicken Nuggets 10 Stk.', 8.50, 1),
(5, 3, 118, 'Köfte Sandwich Menü', 13.50, 2),
(6, 4, 23, 'Lahmacun Classic', 6.00, 1),
(7, 4, 42, 'Hähnchen Box', 7.50, 1),
(8, 5, 47, 'Hähnchen Teller', 13.00, 1),
(9, 5, 49, 'Falafel Teller', 12.00, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bestellungentest`
--

CREATE TABLE `bestellungentest` (
  `BestellungID` int(11) NOT NULL,
  `KundeName` varchar(255) NOT NULL,
  `KundeEmail` varchar(255) NOT NULL,
  `KundeAdresse` varchar(255) NOT NULL,
  `Gesamtbetrag` decimal(10,2) NOT NULL,
  `Lieferkosten` decimal(10,2) NOT NULL,
  `Bestelldatum` timestamp NULL DEFAULT current_timestamp(),
  `bestell_typ` enum('Abholen','Liefern') NOT NULL,
  `Zahlungsmethode` enum('Bar','Karte') DEFAULT NULL,
  `Anmerkungen` text DEFAULT NULL,
  `KundeTelefonnummer` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `bestellungentest`
--

INSERT INTO `bestellungentest` (`BestellungID`, `KundeName`, `KundeEmail`, `KundeAdresse`, `Gesamtbetrag`, `Lieferkosten`, `Bestelldatum`, `bestell_typ`, `Zahlungsmethode`, `Anmerkungen`, `KundeTelefonnummer`) VALUES
(1, 'Testkunde', 'Test@mail.com', 'Testadresse', 24.00, 0.00, '2025-03-03 08:38:50', 'Abholen', 'Bar', '', '15234186421'),
(2, 'Testkunde2', 'Test2@mail.com', 'Testadresse 2', 25.00, 2.00, '2025-03-02 23:03:10', 'Liefern', 'Karte', 'Test', '242424242'),
(3, 'Testkunde3', 'Test3@mail.com', 'Testadresse3', 27.00, 0.00, '2025-03-03 08:38:49', 'Abholen', 'Bar', 'Test', '152341864245'),
(4, 'Testkunde4', 'Test4mail.com', 'Testadresse 4', 13.50, 0.00, '2025-03-03 13:15:59', 'Abholen', 'Karte', NULL, '024374673635'),
(5, 'Testkunde5', 'Test4mail.com', 'Testadresse 5', 25.00, 0.00, '2025-03-03 13:16:35', 'Abholen', 'Karte', NULL, '024374673635');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `produkte`
--

CREATE TABLE `produkte` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `kategorie` enum('Pizza','Wraps','Sandwiches','Grillspezialitaeten','Pide','Brosbox','Tellerportionen','Menu','Beilagen','Nachspeise','Drinks') NOT NULL,
  `preis` decimal(10,2) NOT NULL,
  `bild` varchar(255) NOT NULL,
  `produktbeschreibung` text DEFAULT NULL,
  `allergien` set('a','b','c','d','e','f','g','h','i','j','1','2','3','4','5','6','7','8','9','10','12','14') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `produkte`
--

INSERT INTO `produkte` (`id`, `name`, `kategorie`, `preis`, `bild`, `produktbeschreibung`, `allergien`) VALUES
(17, 'Pizza Margherita', 'Pizza', 8.50, 'pexels-renestrgar-18431672.jpg', 'Tomatensoße, Käse', 'a,c,g'),
(22, 'Pommes Classic', 'Beilagen', 4.50, 'pexels-biabrg-15656541.jpg', '', ''),
(23, 'Lahmacun Classic', 'Pide', 6.00, 'ground-beef-6235554_1280.jpg', '', 'a,g'),
(24, 'Durum', 'Wraps', 12.00, 'kebab-meat-sandwich-7414529_1280.jpg', NULL, 'a,c,f,g,4'),
(27, 'Hähnchen Döner', 'Sandwiches', 7.50, '2443.jpg', '', 'a,c,f,g,4'),
(28, 'Hähnchen Gemüse Döner', 'Sandwiches', 7.80, '2443.jpg', '', 'a,c,f,g,4'),
(29, 'Kalbfleisch Gemüse Döner', 'Sandwiches', 8.30, '1230.jpg', '', 'a,c,f,g,4'),
(34, 'Hähnchen Wrap', 'Wraps', 9.00, 'wrap-7061741_640.jpg', '', 'a,c,f,g,4'),
(35, 'Hähnchen Gemüse Wrap', 'Wraps', 9.50, '33509.jpg', '', 'a,c,f,g,4'),
(36, 'Kalbfleisch Wrap', 'Wraps', 9.50, '2151805431.jpg', '', 'a,c,f,g,4'),
(37, 'Kalbfleisch Gemüse Wrap', 'Wraps', 9.90, 'defaultpic.jpg', '', 'a,c,f,g,4'),
(40, 'Veggie Gemüse Wrap', 'Wraps', 7.50, '14523.jpg', '', 'a,c,f,g,4'),
(41, 'Nog Wrap', 'Wraps', 8.50, 'Nog-Duerum.jpg', '', 'a,c,f,g,4'),
(42, 'Hähnchen Box', 'Brosbox', 7.50, 'defaultpic.jpg', 'Reis, Pommes, Süßkartoffeln oder Salat', 'f,4'),
(43, 'Kalbfleisch Box', 'Brosbox', 8.00, 'defaultpic.jpg', 'Reis, Pommes, Süßkartoffeln oder Salat', 'f,4'),
(47, 'Hähnchen Teller', 'Tellerportionen', 13.00, 'pexels-harry-dona-2418486.jpg', 'Hähnchenfleisch mit gemischten Salat und Beilage nach Wahl', 'c,f,g,4'),
(49, 'Falafel Teller', 'Tellerportionen', 12.00, '2148814498.jpg', 'Kichererbsenbällchen mit gemischten Salat und Beilage nach Wahl', 'c,f,g,4'),
(50, 'Veggie Teller', 'Tellerportionen', 11.00, '2148833783.jpg', 'Grillgemüse mit gemischten Salat und Beilage nach Wahl', 'c,f,g,4'),
(51, 'Nog Teller', 'Tellerportionen', 11.00, '2148648497.jpg', 'Kichererbsen mit gemischten Salat und Beilage nach Wahl', 'c,f,g,4'),
(52, 'Lahmacun mit Salat', 'Pide', 7.00, 'lahmacun-6235579_1280.jpg', '', 'a,f,g,4'),
(54, 'Hackfleisch Pide mit Beilage', 'Pide', 12.00, 'pexels-enginakyurt-7813739.jpg', '', 'a,f,g,4'),
(55, 'Käse Pide', 'Pide', 9.00, 'pexels-enginakyurt-7813740.jpg', '', 'a,g'),
(57, 'Süßkartoffel Pommes', 'Beilagen', 5.50, 'pexels-valeriya-1893556.jpg', '', ''),
(59, 'Portion Reis', 'Beilagen', 4.50, 'rice-bowl.jpg', '', ''),
(60, 'Extra Käse', 'Beilagen', 1.00, 'pexels-polina-tankilevitch-4109946.jpg', '', ''),
(61, 'Extra Fleisch', 'Beilagen', 1.50, 'doner-kebab-6993381_1280.jpg', '', ''),
(62, 'Extra Jalapenos', 'Beilagen', 1.00, 'jalapeno-5435170_1280.jpg', '', ''),
(63, 'Extra Weizenbrot oder Vollkornbrot', 'Beilagen', 1.00, 'slices-bread-white-plate-top-view.jpg', '', ''),
(64, 'Adana Sandwich', 'Grillspezialitaeten', 9.50, 'defaultpic.jpg', '', 'a,g'),
(65, 'Adana Wrap ', 'Grillspezialitaeten', 11.50, 'defaultpic.jpg', '', 'a,g'),
(66, 'Adana Teller', 'Grillspezialitaeten', 18.00, '3279.jpg', 'Gegrillter Rind- und Lammfleischspieß mit gemischten Salat, Beilage nach Wahl und Brot', 'a,g'),
(67, 'Köfte Sandwich', 'Grillspezialitaeten', 9.00, 'defaultpic.jpg', '', 'a,g'),
(68, 'Köfte Wrap', 'Grillspezialitaeten', 11.50, 'defaultpic.jpg', '', 'a,g'),
(69, 'Köfte Teller', 'Grillspezialitaeten', 17.00, 'meatballs-2023247_640.jpg', 'Gegrillte Rinderfrikadellen nach türkischer Art mit gemischten Salat, Beilage nach Wahl und Brot', 'a,g'),
(70, 'Hähnchenspieß Sandwich', 'Grillspezialitaeten', 8.50, 'defaultpic.jpg', '', 'a,g'),
(72, 'Hähnchenspieß Teller', 'Grillspezialitaeten', 16.00, 'pexels-enesfilm-8104900.jpg', 'Gegrillter Hähnchenspieß nach türkischer Art mit gemischten Salat, Beilage nach Wahl und Brot', 'a,g'),
(75, 'Ayran 0,25l', 'Drinks', 2.00, '21398.jpg', '', 'g'),
(77, 'Softdrinks Glasflasche 0,33l', 'Drinks', 2.75, 'coca-cola-473780_640.jpg', '', '1,12'),
(78, 'Softdrinks Dose0,33l', 'Drinks', 2.25, 'pexels-gustavo-santana-3928789-5860659.jpg', '', '1,12'),
(81, 'Eistee Pfirsich oder Zitrone 0,5l', 'Drinks', 2.75, '2242.jpg', '', '1,5,10'),
(82, 'Red Bull 0,25l', 'Drinks', 3.00, '77c94afd-7dbc-4ac3-b4c8-e108a1c76312.JPG', '', '1,10'),
(86, 'Pizza Salami', 'Pizza', 8.50, 'pizza-7863713_640.jpg', 'Tomatensoße, Käse, Salami', 'a,c,g,4'),
(87, 'Pizza Döner', 'Pizza', 10.00, '5400.jpg', 'Tomatensoße, Käse, Dönerfleisch, Zwiebeln', 'a,c,f,g,4'),
(88, 'Pizza Thunfisch', 'Pizza', 10.00, '50e4e769-2815-487e-a1dc-11521fb266f3.JPG', 'Tomatensoße, Käse, Thunfisch, Zwiebeln', 'a,c,d,f,g'),
(89, 'Kalbfleisch Menü', 'Menu', 13.00, '2728fffa-c98d-45f1-9b8d-94687ab31d83.JPG', 'Kalbfleisch Sandwich\r\n + Portion Pommes o. Reis\r\n + Softdrink nach Wahl', ''),
(93, 'Family Pizza Menü', 'Menu', 22.00, 'pizza-3007395_640.jpg', 'Family Pizza nach Wahl + 1 Liter Getränk + Gratis Salat', ''),
(96, 'Baklava', 'Nachspeise', 3.50, 'close-up-view-traditional-turkish-baklava-with-pistachio-black-board.jpg', '2 Stück', 'a,c,f,g,h'),
(99, 'Pizza Brot', 'Pizza', 8.00, 'pexels-roman-odintsov-6147832.jpg', 'Tomatensoße, Knoblauch ', 'a,c,g'),
(100, 'Pizza Funghi', 'Pizza', 9.01, 'pexels-roman-odintsov-6147824.jpg', 'Tomatensoße, Käse, Champignons', 'a,c,g'),
(101, 'Pizza Gemüse', 'Pizza', 9.00, 'pexels-renestrgar-13814644.jpg', 'Tomatensoße, Käse, Paprika, Aubergine, Karotten, Kartoffeln', 'a,c,g'),
(102, 'Pizza Sucuk', 'Pizza', 10.00, 'pexels-vince-2147491.jpg', 'Tomatensoße, Käse, Sucuk', 'a,c,g,4'),
(103, 'Pizza Tomate Mozarella ', 'Pizza', 9.00, 'pizza-tomate-mozarella.jpg', 'Tomatensoße, Käse, Tomaten, Mozzarella ', 'a,c,g'),
(114, 'Kalbfleisch Döner', 'Sandwiches', 8.00, '1230.jpg', '', 'a,c,f,g,4'),
(115, 'Falafel Wrap', 'Wraps', 8.50, '20526.jpg', '', 'a,c,f,g,4'),
(117, 'Adana Sandwich Menü', 'Menu', 14.00, 'defaultpic.jpg', 'Adana Sandwich + Portion Pommes o. Reis + Softdrink nach Wahl', ''),
(118, 'Köfte Sandwich Menü', 'Menu', 14.00, 'defaultpic.jpg', 'Köfte Sandwich + Portion Pommes o. Reis + Softdrink nach Wahl', ''),
(119, 'Pizza Schinken', 'Pizza', 10.00, '5400.jpg', 'Tomatensoße, Käse, Schinken', ''),
(122, 'Chicken Nuggets 10 Stk.', 'Beilagen', 8.51, '2149187942.jpg', '', 'a,g'),
(123, 'Chicken Fingers 10 Stk.', 'Beilagen', 9.50, '2926.jpg', '', 'a,g');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `staedte`
--

CREATE TABLE `staedte` (
  `id` int(11) NOT NULL,
  `stadt_name` varchar(100) NOT NULL,
  `zone_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `staedte`
--

INSERT INTO `staedte` (`id`, `stadt_name`, `zone_id`) VALUES
(2, '88161 - Lindenberg', 1),
(3, '88171 - Weiler', 1),
(4, '88175 - Scheidegg', 2),
(5, '88145 - Heimenkirch', 2),
(6, '88178 - Heimenkirch', 2),
(7, '88145 - Opfenbach', 2),
(9, '88167 - Röthenbach Ort', 3),
(10, '88175 - Scheffau', 3),
(11, '88167 - Gestratz', 4),
(12, '88145 - Hergatz', 4),
(13, '88167 - Grünenbach', 4),
(14, '88145 - Wohmbrechts', 4),
(15, '88167 - Stiefenhofen', 4),
(16, '88179 - Oberreute', 4),
(17, '88239 - Wangen', 5),
(18, '88138 - Hergensweiler', 5),
(19, '88316 - Isny', 5),
(20, '88167 - Maierhöfen', 5),
(21, '87534 - Oberstaufen', 5);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zonen`
--

CREATE TABLE `zonen` (
  `zonen_id` int(11) NOT NULL,
  `mindestbestellwert` decimal(5,2) NOT NULL,
  `lieferkosten` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `zonen`
--

INSERT INTO `zonen` (`zonen_id`, `mindestbestellwert`, `lieferkosten`) VALUES
(1, 15.00, 2.50),
(2, 25.00, 4.50),
(3, 30.00, 6.00),
(4, 50.00, 6.50),
(5, 70.00, 7.50);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `bestellpositionen`
--
ALTER TABLE `bestellpositionen`
  ADD PRIMARY KEY (`PositionID`),
  ADD KEY `FK_Bestellpositionen_Bestellungentest` (`BestellungID`),
  ADD KEY `fk_bestellpositionen_produkte` (`ProduktID`);

--
-- Indizes für die Tabelle `bestellungentest`
--
ALTER TABLE `bestellungentest`
  ADD PRIMARY KEY (`BestellungID`);

--
-- Indizes für die Tabelle `produkte`
--
ALTER TABLE `produkte`
  ADD PRIMARY KEY (`id`);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `bestellpositionen`
--
ALTER TABLE `bestellpositionen`
  ADD CONSTRAINT `fk_bestellpositionen_produkte` FOREIGN KEY (`ProduktID`) REFERENCES `produkte` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
