-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Cze 19, 2025 at 07:12 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `game_library`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'RPG', 'Gry fabularne'),
(2, 'Akcja', 'Gry akcji'),
(3, 'Strzelanka', 'Gry z elementami strzelania'),
(4, 'Przygodowa', 'Gry przygodowe'),
(5, 'Logiczna', 'Gry logiczne i zagadki'),
(6, 'Symulacja', 'Gry symulacyjne'),
(7, 'Indie', 'Gry niezależne'),
(8, 'Strategia', 'Gry strategiczne'),
(9, 'Sandbox', 'Gry z otwartym światem'),
(10, 'Survival', 'Gry survivalowe'),
(11, 'Kooperacja', 'Gry do wspólnej gry'),
(12, 'MMO', 'Gry wieloosobowe online'),
(13, 'Sportowa', 'Gry sportowe'),
(14, 'Wyścigi', 'Gry wyścigowe'),
(15, 'Horror', 'Gry grozy'),
(16, 'Platformowa', 'Gry platformowe'),
(17, 'Fantasy', 'Gry fantasy'),
(18, 'Science Fiction', 'Gry science fiction'),
(19, 'Retro', 'Gry w stylu retro'),
(20, 'Party', 'Gry imprezowe'),
(21, 'Śmieszne', 'Smieszne gierki');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `developer` varchar(100) DEFAULT NULL,
  `category_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `title`, `description`, `price`, `image`, `release_date`, `developer`, `category_id`) VALUES
(1, 'The Witcher 3: Wild Hunt', 'Epicka gra RPG w otwartym świecie.', 119.99, 'assets/images/68532b61505dawiedzmin3.jpg', '2015-05-19', 'CD Projekt RED', 1),
(2, 'Grand Theft Auto V', 'Kultowa gra akcji z otwartym światem.', 89.99, 'assets/images/6853272b192e8gtav.jpg', '2013-09-17', 'Rockstar Games', 1),
(3, 'Minecraft', 'Kreatywna gra survivalowa z budowaniem świata.', 99.99, 'assets/images/685328253a47bminecraft.jpg', '2011-11-18', 'Mojang Studios', 1),
(4, 'Cyberpunk 2077', 'Futurystyczna gra RPG akcji.', 149.99, 'assets/images/684ef79693372cyberpunk.jfif', '2020-12-10', 'CD Projekt RED', 1),
(5, 'Portal 2', 'Logiczna gra z portalami i zagadkami.', 49.99, 'assets/images/portal2.jpg', '2011-04-19', 'Valve', 1),
(6, 'Red Dead Redemption 2', 'Przygodowa gra akcji na Dzikim Zachodzie.', 139.99, 'assets/images/rdr2.jpg', '2018-10-26', 'Rockstar Games', 1),
(7, 'Hollow Knight', 'Metroidvania z piękną grafiką i wymagającą walką.', 34.99, 'assets/images/68532783789cdhollow.webp', '2017-02-24', 'Team Cherry', 1),
(8, 'Stardew Valley', 'Symulator farmy z elementami RPG.', 39.99, 'assets/images/stardewvalley.jpg', '2016-02-26', 'ConcernedApe', 1),
(9, 'DOOM Eternal', 'Dynamiczna strzelanka FPP.', 119.99, 'assets/images/doometernal.jpg', '2020-03-20', 'id Software', 1),
(10, 'Among Us', 'Gra towarzyska o zdradzie i dedukcji.', 19.99, 'assets/images/684ef67d4a587amongus.jfif', '2018-11-16', 'Innersloth', 1),
(11, 'Civilization VI', 'Strategiczna gra turowa.', 129.99, 'assets/images/684ef774ec45fcivilization6.jpg', '2016-10-21', 'Firaxis Games', 1),
(12, 'FIFA 22', 'Popularna gra sportowa – piłka nożna.', 179.99, 'assets/images/685326de81d5cfifa22.jpg', '2021-10-01', 'EA Sports', 1),
(13, 'Need for Speed: Heat', 'Wyścigi uliczne w otwartym świecie.', 99.99, 'assets/images/nfsheat.jpg', '2019-11-08', 'Ghost Games', 1),
(14, 'Outlast', 'Horror psychologiczny z perspektywy pierwszej osoby.', 39.99, 'assets/images/outlast.jpg', '2013-09-04', 'Red Barrels', 1),
(15, 'Terraria', 'Sandboksowa gra przygodowa 2D.', 29.99, 'assets/images/terraria.jpg', '2011-05-16', 'Re-Logic', 1),
(16, 'League of Legends', 'Popularna gra MOBA.', 0.00, 'assets/images/lol.jpg', '2009-10-27', 'Riot Games', 1),
(17, 'Tetris Effect', 'Nowoczesna wersja klasycznej gry logicznej.', 59.99, 'assets/images/68532acc74f0atetris.jfif', '2018-11-09', 'Monstars Inc.', 1),
(18, 'Celeste', 'Platformowa gra indie o trudnej wspinaczce.', 39.99, 'assets/images/684ef751cec79celeste.png', '2018-01-25', 'Matt Makes Games', 1),
(19, 'Football Manager 2022', 'Zaawansowany symulator zarządzania klubem piłkarskim.', 179.99, 'assets/images/685327058c066fm22.jpeg', '2021-11-09', 'Sports Interactive', 1),
(20, 'Overcooked! 2', 'Imprezowa gra kooperacyjna o gotowaniu.', 89.99, 'assets/images/68532941d13bbovercooked2.jfif', '2018-08-07', 'Ghost Town Games', 1),
(21, 'Baldur\'s Gate 3', 'Epicka gra RPG osadzona w świecie D&D, z rozbudowaną fabułą i wyborami.', 249.99, 'assets/images/685324dee5f87bg3.webp', '2023-08-03', 'Larian Studios', 1),
(22, 'The Elder Scrolls V: Skyrim', 'Otwarty świat fantasy, eksploracja, smoki i nieskończone przygody.', 99.99, 'assets/images/68532ad402bbeskyrim.jpg', '2011-11-11', 'Bethesda Game Studios', 1),
(23, 'Dragon Age: Origins', 'Klasyczne RPG z głęboką fabułą i systemem wyborów moralnych.', 59.99, 'assets/images/685326726fb4fdao.jpg', '2009-11-03', 'BioWare', 1),
(24, 'Assassin\'s Creed II', 'Przygoda w renesansowych Włoszech, parkour i skrytobójstwa.', 49.99, 'assets/images/6853245b61d5cac2.jfif', '2009-11-17', 'Ubisoft', 2),
(25, 'Assassin\'s Creed: Brotherhood', 'Kontynuacja przygód Ezio, rozbudowa bractwa asasynów.', 59.99, 'assets/images/685324acc1a9aacB.jpg', '2010-11-16', 'Ubisoft', 2),
(26, 'Dying Light', 'Survival horror z parkourem i walką z zombie w otwartym świecie.', 89.99, 'assets/images/685326a1a42eadl.jfif', '2015-01-27', 'Techland', 2),
(27, 'Valheim', 'Kooperacyjny survival w świecie nordyckich mitów.', 69.99, 'assets/images/68532b688b8b5valheim.jpg', '2021-02-02', 'Iron Gate Studio', 6),
(28, 'Hades', 'Dynamiczny roguelike z mitologii greckiej, szybka akcja i fabuła.', 79.99, 'assets/images/6853275305b55hades.jpg', '2020-09-17', 'Supergiant Games', 2),
(29, 'Disco Elysium', 'Detektywistyczne RPG z unikalnym systemem dialogów i wyborów.', 89.99, 'assets/images/6853262c75958disco.jpg', '2019-10-15', 'ZA/UM', 1),
(30, 'Subnautica', 'Survival w podwodnym świecie pełnym tajemnic i niebezpieczeństw.', 69.99, 'assets/images/68532a7c9aebasubnautica.jpg', '2018-01-23', 'Unknown Worlds', 6),
(31, 'Ori and the Blind Forest', 'Platformowa przygoda z piękną grafiką i wzruszającą historią.', 49.99, 'assets/images/6853289c38b43ori.jpg', '2015-03-11', 'Moon Studios', 4),
(32, 'Cuphead', 'Trudna platformówka w stylu retro z unikalną oprawą graficzną.', 59.99, 'assets/images/6853251c70984cuphead.jpg', '2017-09-29', 'Studio MDHR', 16),
(33, 'The Forest', 'Survival horror z budowaniem i eksploracją lasu pełnego mutantów.', 49.99, 'assets/images/68532b2d5dfeetheforest.jpg', '2018-04-30', 'Endnight Games', 10),
(34, 'Slay the Spire', 'Karciany roguelike z elementami strategii i deckbuildingu.', 49.99, 'assets/images/685329e18d30eslaythespire.jpg', '2019-01-23', 'MegaCrit', 5),
(35, 'Dead Cells', 'Roguelike platformówka z dynamiczną walką i proceduralnymi poziomami.', 59.99, 'assets/images/685325bce2d6cdeadcells.jpg', '2018-08-07', 'Motion Twin', 2),
(36, 'RimWorld', 'Symulator kolonii na obcej planecie z rozbudowaną sztuczną inteligencją.', 99.99, 'assets/images/685329ce28c96rimworld.jpg', '2018-10-17', 'Ludeon Studios', 6),
(37, 'Factorio', 'Gra logiczna o budowaniu fabryk i automatyzacji produkcji.', 89.99, 'assets/images/685326c2a4201factorio.jpg', '2020-08-14', 'Wube Software', 5),
(38, 'Dark Souls III', 'Trudna gra akcji RPG z mrocznym światem fantasy.', 119.99, 'assets/images/685325a09225cds3.jpg', '2016-04-12', 'FromSoftware', 1),
(39, 'The Legend of Zelda: BOTW', 'Otwarty świat fantasy, eksploracja i zagadki.', 199.99, 'assets/images/68532b35a8381zelda.jpg', '2017-03-03', 'Nintendo', 4),
(40, 'Hades II', 'Kontynuacja hitu roguelike w świecie mitologii greckiej.', 99.99, 'assets/images/6853275c0eba2hades2.jpg', '2025-05-01', 'Supergiant Games', 2),
(41, 'It Takes Two', 'Kooperacyjna platformówka z kreatywną rozgrywką dla dwóch graczy.', 89.99, 'assets/images/685327aadc610ittakestwo.webp', '2021-03-26', 'Hazelight Studios', 11),
(42, 'Satisfactory', 'Gra o budowaniu fabryk w otwartym świecie z perspektywy FPP.', 99.99, 'assets/images/685329d7736e2satisfactory.jpg', '2020-06-08', 'Coffee Stain Studios', 6),
(43, 'Little Nightmares', 'Platformowy horror z unikalnym klimatem i zagadkami.', 49.99, 'assets/images/6853280813901littlenightmares.jpg', '2017-04-28', 'Tarsier Studios', 15),
(44, 'Ori and the Will of the Wisps', 'Kontynuacja przygód Ori, jeszcze piękniejsza i bardziej emocjonalna.', 69.99, 'assets/images/685328c0c9349ori2.webp', '2020-03-11', 'Moon Studios', 4),
(45, 'Outer Wilds', 'Eksploracyjna gra przygodowa z otwartym światem i zagadkami.', 79.99, 'assets/images/68532932505d2outerwilds.jfif', '2019-05-28', 'Mobius Digital', 4),
(46, 'Return of the Obra Dinn', 'Detektywistyczna gra logiczna z unikalną oprawą graficzną.', 59.99, 'assets/images/685329c00c92freturnoftheobra.jpg', '2018-10-18', 'Lucas Pope', 5),
(47, 'Deep Rock Galactic', 'Kooperacyjna strzelanka w kopalniach pełnych obcych.', 79.99, 'assets/images/685325e292aa4deeprock.webp', '2020-05-13', 'Ghost Ship Games', 3),
(48, 'Monster Hunter: World', 'Akcja RPG z polowaniem na potwory w otwartym świecie.', 129.99, 'assets/images/6853284211fddmhworld.jpg', '2018-01-26', 'Capcom', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `game_categories`
--

CREATE TABLE `game_categories` (
  `game_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `game_categories`
--

INSERT INTO `game_categories` (`game_id`, `category_id`) VALUES
(1, 1),
(1, 4),
(1, 9),
(1, 17),
(2, 2),
(2, 4),
(2, 9),
(2, 11),
(3, 6),
(3, 7),
(3, 9),
(3, 10),
(4, 1),
(4, 2),
(4, 9),
(4, 18),
(5, 5),
(5, 11),
(5, 16),
(5, 18),
(6, 2),
(6, 4),
(6, 9),
(6, 17),
(7, 4),
(7, 7),
(7, 16),
(7, 17),
(8, 6),
(8, 7),
(8, 9),
(8, 10),
(9, 2),
(9, 3),
(9, 11),
(9, 18),
(10, 7),
(10, 11),
(10, 20),
(11, 8),
(11, 9),
(12, 13),
(13, 2),
(13, 9),
(13, 14),
(14, 15),
(15, 4),
(15, 7),
(15, 9),
(16, 2),
(16, 11),
(16, 12),
(17, 5),
(17, 19),
(18, 7),
(18, 16),
(19, 6),
(19, 13),
(20, 7),
(20, 11),
(20, 20),
(21, 1),
(21, 4),
(21, 17),
(22, 1),
(22, 4),
(22, 9),
(22, 17),
(23, 1),
(23, 4),
(23, 17),
(24, 2),
(24, 4),
(24, 9),
(25, 2),
(25, 4),
(25, 9),
(26, 2),
(26, 9),
(26, 10),
(26, 15),
(27, 6),
(27, 9),
(27, 10),
(27, 17),
(28, 2),
(28, 7),
(28, 17),
(29, 1),
(29, 4),
(29, 7),
(30, 6),
(30, 10),
(30, 18),
(31, 4),
(31, 7),
(31, 16),
(32, 7),
(32, 16),
(32, 19),
(33, 9),
(33, 10),
(33, 15),
(34, 5),
(34, 7),
(34, 8),
(35, 2),
(35, 7),
(35, 16),
(35, 19),
(36, 6),
(36, 8),
(36, 18),
(37, 5),
(37, 6),
(37, 8),
(38, 1),
(38, 2),
(38, 17),
(39, 4),
(39, 9),
(39, 17),
(40, 2),
(40, 7),
(40, 17),
(41, 7),
(41, 11),
(41, 16),
(42, 6),
(42, 8),
(42, 9),
(43, 4),
(43, 15),
(43, 16),
(44, 4),
(44, 7),
(44, 16),
(45, 4),
(45, 9),
(45, 18),
(46, 4),
(46, 5),
(46, 7),
(47, 3),
(47, 7),
(47, 11),
(47, 18),
(48, 1),
(48, 2),
(48, 9),
(48, 17);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `purchase_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `user_id`, `game_id`, `purchase_date`) VALUES
(3, 4, 4, '2025-06-15 17:10:53'),
(4, 4, 1, '2025-06-15 17:15:20'),
(5, 4, 16, '2025-06-15 17:16:31'),
(6, 4, 12, '2025-06-15 17:31:47'),
(7, 4, 9, '2025-06-15 17:42:37'),
(8, 4, 2, '2025-06-15 17:47:58'),
(9, 4, 2, '2025-06-15 17:47:58'),
(10, 4, 13, '2025-06-15 17:48:23'),
(11, 4, 13, '2025-06-15 17:48:23'),
(12, 4, 10, '2025-06-15 17:51:59'),
(13, 4, 10, '2025-06-15 17:51:59'),
(14, 4, 18, '2025-06-15 17:52:29'),
(15, 4, 18, '2025-06-15 17:52:29'),
(16, 4, 7, '2025-06-15 17:52:42'),
(17, 4, 7, '2025-06-15 17:52:42'),
(18, 4, 14, '2025-06-15 17:53:14'),
(19, 4, 14, '2025-06-15 17:53:14'),
(20, 4, 11, '2025-06-15 17:57:05'),
(21, 4, 11, '2025-06-15 17:57:05'),
(22, 4, 8, '2025-06-15 17:57:10'),
(23, 4, 8, '2025-06-15 17:57:10'),
(24, 4, 6, '2025-06-15 17:59:20'),
(25, 4, 15, '2025-06-15 17:59:43'),
(26, 5, 18, '2025-06-15 18:04:39'),
(27, 5, 10, '2025-06-15 18:04:53'),
(28, 5, 7, '2025-06-15 18:12:09'),
(29, 5, 9, '2025-06-15 18:15:57'),
(30, 5, 2, '2025-06-15 18:16:39'),
(31, 5, 8, '2025-06-15 18:17:40'),
(32, 5, 11, '2025-06-15 18:19:01'),
(33, 4, 20, '2025-06-17 00:04:18'),
(34, 6, 10, '2025-06-17 00:22:21'),
(35, 6, 4, '2025-06-17 00:42:16'),
(36, 6, 2, '2025-06-17 01:38:07'),
(37, 6, 16, '2025-06-17 01:50:48'),
(38, 6, 1, '2025-06-17 01:53:14'),
(39, 6, 15, '2025-06-17 01:53:28'),
(40, 6, 8, '2025-06-17 02:18:47'),
(41, 4, 23, '2025-06-18 23:15:42'),
(42, 4, 24, '2025-06-18 23:16:26'),
(43, 4, 34, '2025-06-19 18:27:57');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 10),
  `content` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `game_id`, `rating`, `content`, `image`, `created_at`) VALUES
(2, 4, 4, 9, 'Bardzo ciekawe przyszlosciowy swiat zze tak powiem', NULL, '2025-06-15 17:15:03'),
(3, 4, 1, 10, 'Bardzo fajna gra mozna jezdzic na koniu', NULL, '2025-06-15 17:15:34'),
(5, 4, 16, 2, 'niemili towarzysze nie polecam ggry komputeruwki', NULL, '2025-06-15 17:17:25'),
(7, 4, 10, 7, 'fajna gierka w podejrzenia', NULL, '2025-06-16 23:05:51'),
(8, 6, 8, 8, 'okok', NULL, '2025-06-17 02:19:01'),
(9, 4, 12, 5, 'srednio', NULL, '2025-06-19 17:43:13'),
(10, 4, 24, 9, 'asasin', NULL, '2025-06-19 18:31:42');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `wallet_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `favorite_game_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `avatar`, `created_at`, `wallet_balance`, `description`, `favorite_game_id`) VALUES
(3, 'dis', 'dis@lol.gg', '$2y$10$gd63lOdIIFRlA7Kz1bpIH.8yz.jYWcHsx.B37Rr/2sqyg164WI5Xy', 'user', 'uploads/684ec9fae3106qr.png', '2025-06-15 15:10:11', 0.00, NULL, NULL),
(4, 'admin11', 'admin@admin.pl', '$2y$10$ZDhHDPdjPNxiTAr5CHaCL.POm3bzqxXtpa5ron0H4AgrCMIXjf1f.', 'admin', 'uploads/685092a14c12cdel.jpg', '2025-06-15 15:30:14', 110.16, 'gracz gier kompputeruwek:)', 1),
(5, 'user2', 'user2@email.com', '$2y$10$9eAAZx32Xm8lApjna7AzKumLNrDjeFS9IM5cdpnV9bEV.cGa0Di.S', 'user', NULL, '2025-06-15 18:04:21', 275.07, NULL, NULL),
(6, 'haslo654321', 'hasl@poda.pl', '$2y$10$fQHGadoH.92e1rtdR1dgYOKhBMPs0brVE0bEWOCfuhXrJzmEpSZ5e', 'user', NULL, '2025-06-17 00:22:01', 50.06, 'asdaar', 10),
(7, 'uzytkownik', 'uzytkownik@email.com', '$2y$10$88EShWjV7yE7tryDLNcRmuy7LQs0qI3tpAqkVmS1W2m5UkhWuiwH2', 'user', NULL, '2025-06-19 02:56:40', 440.00, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wallet_transactions`
--

CREATE TABLE `wallet_transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('deposit','purchase') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `wallet_transactions`
--

INSERT INTO `wallet_transactions` (`id`, `user_id`, `type`, `amount`, `description`, `created_at`) VALUES
(1, 4, 'deposit', 33.00, 'Doładowanie portfela', '2025-06-15 17:31:42'),
(2, 4, 'purchase', -179.99, 'Zakup gry: FIFA 22', '2025-06-15 17:31:47'),
(3, 4, 'purchase', -119.99, 'Zakup gry: DOOM Eternal', '2025-06-15 17:42:37'),
(4, 4, 'purchase', -89.99, 'Zakup gry: Grand Theft Auto V', '2025-06-15 17:47:58'),
(5, 4, 'purchase', -99.99, 'Zakup gry: Need for Speed: Heat', '2025-06-15 17:48:23'),
(6, 4, 'purchase', -19.99, 'Zakup gry: Among Us', '2025-06-15 17:51:59'),
(7, 4, 'purchase', -39.99, 'Zakup gry: Celeste', '2025-06-15 17:52:29'),
(8, 4, 'purchase', -34.99, 'Zakup gry: Hollow Knight', '2025-06-15 17:52:43'),
(9, 4, 'deposit', 500.00, 'Doładowanie portfela', '2025-06-15 17:52:50'),
(10, 4, 'purchase', -39.99, 'Zakup gry: Outlast', '2025-06-15 17:53:14'),
(11, 4, 'purchase', -129.99, 'Zakup gry: Civilization VI', '2025-06-15 17:57:05'),
(12, 4, 'purchase', -39.99, 'Zakup gry: Stardew Valley', '2025-06-15 17:57:10'),
(13, 4, 'purchase', -139.99, 'Zakup gry: Red Dead Redemption 2', '2025-06-15 17:59:20'),
(14, 4, 'purchase', -29.99, 'Zakup gry: Terraria', '2025-06-15 17:59:43'),
(15, 5, 'deposit', 250.00, 'Doładowanie portfela', '2025-06-15 18:04:34'),
(16, 5, 'purchase', -39.99, 'Zakup gry: Celeste', '2025-06-15 18:04:39'),
(17, 5, 'purchase', -19.99, 'Zakup gry: Among Us', '2025-06-15 18:04:53'),
(18, 5, 'purchase', -34.99, 'Zakup gry: Hollow Knight', '2025-06-15 18:12:09'),
(19, 5, 'purchase', -119.99, 'Zakup gry: DOOM Eternal', '2025-06-15 18:15:57'),
(20, 5, 'deposit', 500.00, 'Doładowanie portfela', '2025-06-15 18:16:24'),
(21, 5, 'purchase', -89.99, 'Zakup gry: Grand Theft Auto V', '2025-06-15 18:16:39'),
(22, 5, 'purchase', -39.99, 'Zakup gry: Stardew Valley', '2025-06-15 18:17:40'),
(23, 5, 'purchase', -129.99, 'Zakup gry: Civilization VI', '2025-06-15 18:19:01'),
(24, 4, 'purchase', -89.99, 'Zakup gry: Overcooked! 2', '2025-06-17 00:04:18'),
(25, 6, 'deposit', 500.00, 'Doładowanie portfela', '2025-06-17 00:22:18'),
(26, 6, 'purchase', -19.99, 'Zakup gry: Among Us', '2025-06-17 00:22:21'),
(27, 6, 'purchase', -149.99, 'Zakup gry: Cyberpunk 2077', '2025-06-17 00:42:16'),
(28, 6, 'purchase', -89.99, 'Zakup gry: Grand Theft Auto V', '2025-06-17 01:38:07'),
(29, 6, 'purchase', 0.00, 'Zakup gry: League of Legends', '2025-06-17 01:50:48'),
(30, 6, 'purchase', -119.99, 'Zakup gry: The Witcher 3: Wild Hunt', '2025-06-17 01:53:14'),
(31, 6, 'purchase', -29.99, 'Zakup gry: Terraria', '2025-06-17 01:53:28'),
(32, 6, 'purchase', -39.99, 'Zakup gry: Stardew Valley', '2025-06-17 02:18:47'),
(33, 4, 'purchase', -59.99, 'Zakup gry: Dragon Age: Origins', '2025-06-18 23:15:42'),
(34, 4, 'deposit', 100.00, 'Doładowanie portfela', '2025-06-18 23:16:22'),
(35, 4, 'purchase', -49.99, 'Zakup gry: Assassin\'s Creed II', '2025-06-18 23:16:26'),
(36, 4, 'deposit', 50.00, 'Doładowanie portfela', '2025-06-19 02:25:05'),
(37, 4, 'deposit', 15.00, 'Doładowanie portfela', '2025-06-19 02:26:52'),
(38, 4, 'deposit', 15.00, 'Doładowanie portfela', '2025-06-19 02:27:41'),
(39, 7, 'deposit', 30.00, 'Doładowanie portfela', '2025-06-19 02:58:54'),
(40, 7, 'deposit', 30.00, 'Doładowanie portfela', '2025-06-19 02:59:15'),
(41, 7, 'deposit', 30.00, 'Doładowanie portfela', '2025-06-19 02:59:26'),
(42, 7, 'deposit', 30.00, 'Doładowanie portfela', '2025-06-19 02:59:48'),
(43, 7, 'deposit', 30.00, 'Doładowanie portfela', '2025-06-19 03:00:03'),
(44, 7, 'deposit', 30.00, 'Doładowanie portfela', '2025-06-19 03:01:32'),
(45, 7, 'deposit', 30.00, 'Doładowanie portfela', '2025-06-19 03:02:00'),
(46, 7, 'deposit', 30.00, 'Doładowanie portfela', '2025-06-19 03:02:17'),
(47, 7, 'deposit', 50.00, 'Doładowanie portfela', '2025-06-19 03:02:23'),
(48, 7, 'deposit', 50.00, 'Doładowanie portfela', '2025-06-19 03:02:48'),
(49, 7, 'deposit', 50.00, 'Doładowanie portfela', '2025-06-19 03:02:52'),
(50, 7, 'deposit', 50.00, 'Doładowanie portfela', '2025-06-19 03:02:56'),
(51, 4, 'deposit', 1.00, 'Doładowanie portfela', '2025-06-19 03:03:28'),
(52, 4, 'deposit', 1.00, 'Doładowanie portfela', '2025-06-19 03:03:41'),
(53, 4, 'deposit', 1.00, 'Doładowanie portfela', '2025-06-19 03:03:49'),
(54, 4, 'deposit', 1.00, 'Doładowanie portfela', '2025-06-19 03:03:54'),
(55, 4, 'deposit', 1.00, 'Doładowanie portfela', '2025-06-19 17:40:36'),
(56, 4, 'deposit', 1.00, 'Doładowanie portfela', '2025-06-19 17:42:10'),
(57, 4, 'deposit', 1.00, 'Doładowanie portfela', '2025-06-19 17:42:13'),
(58, 4, 'purchase', -49.99, 'Zakup gry: Slay the Spire', '2025-06-19 18:27:57'),
(59, 4, 'deposit', 5.00, 'Doładowanie portfela', '2025-06-19 18:48:48');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `added_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `game_id`, `added_at`) VALUES
(40, 4, 44, '2025-06-19 02:09:03');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_category` (`category_id`);

--
-- Indeksy dla tabeli `game_categories`
--
ALTER TABLE `game_categories`
  ADD PRIMARY KEY (`game_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indeksy dla tabeli `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indeksy dla tabeli `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeksy dla tabeli `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `game_categories`
--
ALTER TABLE `game_categories`
  ADD CONSTRAINT `game_categories_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `game_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchases_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD CONSTRAINT `wallet_transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
