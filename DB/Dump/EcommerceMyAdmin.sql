-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Ott 22, 2020 alle 08:07
-- Versione del server: 5.7.31-0ubuntu0.18.04.1
-- Versione PHP: 7.3.18-1+ubuntu18.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `easystart`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `accesses`
--

CREATE TABLE `accesses` (
  `id` int(12) NOT NULL,
  `ip` char(20) NOT NULL,
  `data` char(10) NOT NULL,
  `ora` char(8) NOT NULL,
  `username` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `accesses`
--

INSERT INTO `accesses` (`id`, `ip`, `data`, `ora`, `username`) VALUES
(1, '127.0.0.1', '12-09-2020', '12:20', 'antonio'),
(2, '127.0.0.1', '14-09-2020', '12:57', 'antonio'),
(3, '127.0.0.1', '19-09-2020', '09:55', 'antonio'),
(4, '127.0.0.1', '21-09-2020', '09:36', 'antonio'),
(5, '127.0.0.1', '22-09-2020', '16:18', 'antonio'),
(6, '127.0.0.1', '22-09-2020', '17:54', 'antonio'),
(7, '127.0.0.1', '26-09-2020', '11:17', 'antonio'),
(8, '127.0.0.1', '28-09-2020', '10:59', 'antonio'),
(9, '127.0.0.1', '03-10-2020', '21:35', 'antonio'),
(10, '127.0.0.1', '22-10-2020', '08:04', 'antonio');

-- --------------------------------------------------------

--
-- Struttura della tabella `admingroups`
--

CREATE TABLE `admingroups` (
  `id_group` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `admingroups`
--

INSERT INTO `admingroups` (`id_group`, `name`) VALUES
(3, 'admin');

-- --------------------------------------------------------

--
-- Struttura della tabella `adminsessions`
--

CREATE TABLE `adminsessions` (
  `uid` char(32) NOT NULL,
  `token` char(32) NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `creation_date` int(10) UNSIGNED NOT NULL,
  `user_agent` char(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `adminsessions`
--

INSERT INTO `adminsessions` (`uid`, `token`, `id_user`, `creation_date`, `user_agent`) VALUES
('185980176e0cc05ebb7bba33970c9f5d', '7f069d5b33aea44bad2b01d472f4608c', 2, 1603346676, '07cd63964cd4102f3186766c33314e34');

-- --------------------------------------------------------

--
-- Struttura della tabella `adminusers`
--

CREATE TABLE `adminusers` (
  `id_user` int(10) UNSIGNED NOT NULL,
  `username` varchar(80) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `password` char(40) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `last_failure` int(10) UNSIGNED NOT NULL,
  `has_confirmed` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `adminusers`
--

INSERT INTO `adminusers` (`id_user`, `username`, `password`, `last_failure`, `has_confirmed`) VALUES
(2, 'antonio', 'e9d71f5ee7c92d6dc9e92ffdad17b8bd49418f98', 1593423104, 0),
(3, 'yyy', '7823372203bd98aeb10e6f33a6ce7dab12d13423', 0, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `adminusers_groups`
--

CREATE TABLE `adminusers_groups` (
  `id_user` int(11) UNSIGNED NOT NULL,
  `id_group` int(11) UNSIGNED NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL,
  `id_ug` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `attributi`
--

CREATE TABLE `attributi` (
  `id_a` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `titolo` varchar(200) NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL,
  `tipo` char(20) NOT NULL DEFAULT 'TENDINA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `attributi_valori`
--

CREATE TABLE `attributi_valori` (
  `id_av` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `titolo` varchar(200) NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL,
  `id_a` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `immagine` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `caratteristiche`
--

CREATE TABLE `caratteristiche` (
  `id_car` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `titolo` varchar(200) NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `caratteristiche`
--

INSERT INTO `caratteristiche` (`id_car`, `data_creazione`, `titolo`, `id_order`) VALUES
(1, '2020-07-30 11:35:46', 'TEST', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `caratteristiche_valori`
--

CREATE TABLE `caratteristiche_valori` (
  `id_cv` int(10) UNSIGNED NOT NULL,
  `id_car` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `titolo` text NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `caratteristiche_valori`
--

INSERT INTO `caratteristiche_valori` (`id_cv`, `id_car`, `data_creazione`, `titolo`, `id_order`) VALUES
(1, 1, '2020-07-30 11:35:54', 'CCCC', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `cart`
--

CREATE TABLE `cart` (
  `id_cart` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_page` int(10) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL,
  `cart_uid` char(32) NOT NULL,
  `creation_time` int(10) UNSIGNED NOT NULL,
  `price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `codice` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `immagine` varchar(100) NOT NULL,
  `in_promozione` char(1) NOT NULL DEFAULT 'N',
  `prezzo_intero` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `id_c` int(10) UNSIGNED NOT NULL,
  `attributi` text NOT NULL,
  `peso` decimal(10,2) NOT NULL DEFAULT '1.00',
  `json_sconti` text,
  `id_iva` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `iva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `id_p` int(10) UNSIGNED NOT NULL,
  `json_attributi` varchar(100) NOT NULL DEFAULT '[]',
  `json_personalizzazioni` varchar(100) NOT NULL DEFAULT '[]'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `categories`
--

CREATE TABLE `categories` (
  `id_c` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `attivo` char(1) NOT NULL,
  `title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `alias` varchar(100) CHARACTER SET utf8 NOT NULL,
  `id_p` int(10) UNSIGNED NOT NULL,
  `lft` int(10) UNSIGNED NOT NULL,
  `rgt` int(10) UNSIGNED NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL,
  `section` char(20) CHARACTER SET utf8 NOT NULL,
  `keywords` varchar(400) CHARACTER SET utf8 NOT NULL,
  `meta_description` text CHARACTER SET utf8 NOT NULL,
  `add_in_sitemap` enum('Y','N') NOT NULL DEFAULT 'Y',
  `template` varchar(100) CHARACTER SET utf8 NOT NULL,
  `immagine` varchar(200) CHARACTER SET utf8 NOT NULL,
  `mostra_in_home` enum('Y','N') NOT NULL DEFAULT 'Y'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `categories`
--

INSERT INTO `categories` (`id_c`, `data_creazione`, `attivo`, `title`, `description`, `alias`, `id_p`, `lft`, `rgt`, `id_order`, `section`, `keywords`, `meta_description`, `add_in_sitemap`, `template`, `immagine`, `mostra_in_home`) VALUES
(1, '2013-06-07 09:00:48', 'Y', '-- root --', '', '', 0, 1, 42, 0, '', '', '', 'Y', '', '', 'Y'),
(84, '2014-09-01 14:39:51', '', 'Prodotti', '', 'prodotti', 1, 2, 23, 6, 'prodotti', 'aaaaaa', 'aaaa', 'Y', '', 'tige-categoria-sospensione_1.jpg', 'Y'),
(85, '2018-02-26 08:56:53', '', 'Slide', '', 'slide', 1, 24, 25, 7, 'slide', '', '', 'Y', '', '', 'Y'),
(86, '2018-02-26 08:57:12', '', 'Home', '', 'home', 1, 26, 27, 8, 'home', '', '', 'Y', '', '', 'Y'),
(87, '2018-02-26 08:57:28', '', 'Blog', '', 'blog', 1, 28, 33, 9, 'blog', '', '', 'Y', '', '', 'Y'),
(97, '2019-10-14 08:39:18', '', 'Sospensione', '', 'sospensione', 84, 5, 8, 14, '', '', '', 'Y', '', 'tige-categoria-sospensione.jpg', 'Y'),
(98, '2019-10-14 08:39:27', '', 'Parete', '', 'parete', 84, 3, 4, 13, '', '', '', 'Y', '', 'tige-categoria-parete.jpg', 'Y'),
(99, '2019-10-14 08:39:35', '', 'Soffitto', '', 'soffitto', 84, 9, 10, 15, '', '', '', 'Y', '', 'tecnico-categoria-soffitto.jpg', 'Y'),
(100, '2019-10-14 08:39:47', '', 'Tavolo', '', 'tavolo', 84, 11, 12, 16, '', '', '', 'Y', '', '', 'Y'),
(101, '2019-10-14 08:39:55', '', 'Terra', '', 'terra', 84, 13, 14, 17, '', '', '', 'Y', '', 'tige-categoria-terra.jpg', 'Y'),
(102, '2019-10-14 08:40:01', '', 'Accessori', '', 'accessori', 84, 15, 16, 18, '', '', '', 'Y', '', '', 'N'),
(103, '2019-10-26 11:17:16', '', 'Slide sotto', '', 'slide-sotto', 1, 34, 35, 19, 'slidesotto', '', '', 'Y', '', '', 'Y'),
(104, '2019-10-28 13:43:53', '', '111', '', '111', 97, 6, 7, 20, '', '', '', 'Y', '', '', 'Y'),
(105, '2020-05-25 07:53:02', '', 'Contattaci', '', 'contattaci-7329', 84, 17, 18, 21, '', '', '', 'Y', '', '', 'Y'),
(106, '2020-06-08 19:10:27', '', 'aaa', '', 'aaa', 87, 29, 30, 22, '', '', '', 'Y', '', '', 'Y'),
(107, '2020-06-08 19:10:43', '', 'bbb', '', 'ccc', 87, 31, 32, 23, '', '', '', 'Y', '', '', 'Y'),
(108, '2020-06-15 13:21:47', '', '111', '', '111-6638', 84, 19, 20, 24, '', '', '', 'Y', '', '', 'Y'),
(109, '2020-07-11 10:05:29', '', 'Referenze', '', 'referenze', 1, 36, 37, 25, 'referenze', '', '', 'Y', '', '', 'Y'),
(110, '2020-07-27 16:04:17', '', 'Team', '', 'team', 1, 38, 39, 26, 'team', '', '', 'Y', '', '', 'Y'),
(111, '2020-07-28 07:42:36', '', '444', '', '444', 84, 21, 22, 27, '', '', '', 'Y', '', '', 'Y'),
(112, '2020-08-12 13:18:44', '', 'Download', '', 'download', 1, 40, 41, 28, 'download', '', '', 'Y', '', '', 'Y');

-- --------------------------------------------------------

--
-- Struttura della tabella `classi_sconto`
--

CREATE TABLE `classi_sconto` (
  `id_classe` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `titolo` varchar(200) CHARACTER SET utf8 NOT NULL,
  `sconto` decimal(10,2) NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `classi_sconto`
--

INSERT INTO `classi_sconto` (`id_classe`, `data_creazione`, `titolo`, `sconto`, `id_order`) VALUES
(4, '2018-02-24 11:09:57', 'Professionista', '15.00', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `classi_sconto_categories`
--

CREATE TABLE `classi_sconto_categories` (
  `id_csc` int(10) UNSIGNED NOT NULL,
  `id_c` int(11) UNSIGNED NOT NULL,
  `id_classe` int(11) UNSIGNED NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `combinazioni`
--

CREATE TABLE `combinazioni` (
  `id_c` int(10) UNSIGNED NOT NULL,
  `col_1` int(11) UNSIGNED NOT NULL,
  `col_2` int(11) UNSIGNED NOT NULL,
  `col_3` int(11) UNSIGNED NOT NULL,
  `col_4` int(11) UNSIGNED NOT NULL,
  `col_5` int(11) UNSIGNED NOT NULL,
  `col_6` int(11) UNSIGNED NOT NULL,
  `col_7` int(11) UNSIGNED NOT NULL,
  `col_8` int(11) UNSIGNED NOT NULL,
  `id_page` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `immagine` varchar(100) NOT NULL DEFAULT '0',
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `price` decimal(12,4) NOT NULL,
  `codice` varchar(100) NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL,
  `peso` decimal(10,2) NOT NULL,
  `giacenza` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `contenuti`
--

CREATE TABLE `contenuti` (
  `id_cont` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lingua` char(5) CHARACTER SET utf8 DEFAULT NULL,
  `titolo` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `descrizione` text CHARACTER SET utf8,
  `immagine_1` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `immagine_2` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `id_tipo` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `id_c` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `id_page` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `id_order` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `link_contenuto` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `link_libero` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `target` enum('STESSO_TAB','NUOVO_TAB') NOT NULL DEFAULT 'STESSO_TAB',
  `attivo` enum('Y','N') NOT NULL DEFAULT 'Y',
  `tipo` char(20) NOT NULL DEFAULT 'FASCIA',
  `coordinate` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `contenuti`
--

INSERT INTO `contenuti` (`id_cont`, `data_creazione`, `lingua`, `titolo`, `descrizione`, `immagine_1`, `immagine_2`, `id_tipo`, `id_c`, `id_page`, `id_order`, `link_contenuto`, `link_libero`, `target`, `attivo`, `tipo`, `coordinate`) VALUES
(10, '2020-06-23 09:38:51', 'tutte', 'Fascia 1', NULL, NULL, NULL, 1, 0, 204, 5, 0, NULL, 'STESSO_TAB', 'Y', 'FASCIA', ''),
(11, '2020-06-23 09:55:50', 'tutte', 'AAAA', NULL, NULL, NULL, 1, 0, 207, 6, 0, NULL, 'STESSO_TAB', 'Y', 'FASCIA', ''),
(12, '2020-06-23 09:59:09', 'it', 'BBB', NULL, NULL, NULL, 1, 0, 207, 8, 0, NULL, 'STESSO_TAB', 'Y', 'FASCIA', ''),
(13, '2020-06-27 08:08:25', 'fr', '1234', NULL, NULL, NULL, 1, 0, 207, 7, 0, NULL, 'STESSO_TAB', 'Y', 'FASCIA', ''),
(14, '2020-06-27 09:06:03', 'tutte', 'CCC', NULL, NULL, NULL, 1, 0, 207, 9, 0, NULL, 'STESSO_TAB', 'Y', 'FASCIA', ''),
(16, '2020-06-27 10:06:05', 'tutte', 'AAAA', NULL, NULL, NULL, 1, 84, 0, 13, 0, NULL, 'STESSO_TAB', 'Y', 'FASCIA', ''),
(17, '2020-06-27 10:06:11', 'tutte', 'BBBB', NULL, NULL, NULL, 1, 84, 0, 10, 0, NULL, 'STESSO_TAB', 'Y', 'FASCIA', ''),
(18, '2020-06-29 09:38:36', 'tutte', 'PRODOTTI', NULL, NULL, NULL, 2, 98, 0, 12, 0, NULL, 'STESSO_TAB', 'Y', 'FASCIA', ''),
(19, '2020-06-29 09:56:06', 'tutte', 'PRODOTTI', NULL, NULL, NULL, 2, 84, 0, 11, 0, NULL, 'STESSO_TAB', 'Y', 'FASCIA', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `contenuti_tradotti`
--

CREATE TABLE `contenuti_tradotti` (
  `id_ct` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lingua` varchar(2) CHARACTER SET utf8 DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alias` varchar(300) CHARACTER SET utf8 DEFAULT NULL,
  `description` text CHARACTER SET utf8,
  `keywords` varchar(400) CHARACTER SET utf8 DEFAULT NULL,
  `meta_description` text CHARACTER SET utf8,
  `id_c` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `id_page` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `salvato` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL DEFAULT '',
  `sottotitolo` varchar(255) NOT NULL DEFAULT '',
  `id_car` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `id_cv` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `titolo` text CHARACTER SET utf8,
  `sezione` varchar(100) NOT NULL DEFAULT '',
  `id_marchio` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `id_ruolo` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `id_tipo_azienda` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `id_a` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `id_av` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `id_pers` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `id_tag` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `contenuti_tradotti`
--

INSERT INTO `contenuti_tradotti` (`id_ct`, `data_creazione`, `lingua`, `title`, `alias`, `description`, `keywords`, `meta_description`, `id_c`, `id_page`, `salvato`, `url`, `sottotitolo`, `id_car`, `id_cv`, `titolo`, `sezione`, `id_marchio`, `id_ruolo`, `id_tipo_azienda`, `id_a`, `id_av`, `id_pers`, `id_tag`) VALUES
(37, '2020-05-26 08:15:54', 'en', '(Copia di) (Copia di) Post 1', 'post-1-8761-2231', 'test', '', '', 0, 375, 1, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(39, '2020-05-26 08:15:54', 'en', '(Copia di) Post 1', 'post-1-8761', 'test', '', '', 0, 374, 0, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(41, '2020-05-26 08:26:36', 'en', 'Shop', 'shop', '', '', '', 84, 0, 1, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(43, '2020-05-26 08:26:36', 'en', 'Wall', 'wall', '', '', '', 98, 0, 1, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(45, '2020-05-26 08:26:36', 'en', 'Sospensione', 'sospensione', '', '', '', 97, 0, 0, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(47, '2020-05-26 08:26:36', 'en', '111', '111', '', '', '', 104, 0, 0, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(49, '2020-05-26 08:26:37', 'en', 'Soffitto', 'soffitto', '', '', '', 99, 0, 0, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(51, '2020-05-26 08:26:37', 'en', 'Tavolo', 'tavolo', '', '', '', 100, 0, 0, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(53, '2020-05-26 08:26:37', 'en', 'Terra', 'terra', '', '', '', 101, 0, 0, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(55, '2020-05-26 08:26:37', 'en', 'Accessori', 'accessori', '', '', '', 102, 0, 0, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(57, '2020-05-26 08:26:37', 'en', 'Contattaci', 'contattaci-7329', '', '', '', 105, 0, 0, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(59, '2020-05-26 08:29:17', 'en', 'Blog', 'blog', '', '', '', 87, 0, 0, '', '', 0, 0, '', 'blog', 0, 0, 0, 0, 0, 0, 0),
(61, '2020-05-26 08:30:03', 'en', 'Contattaci', 'contattaci', 'sdfsdsf', '', '', 0, 205, 0, '', '', 0, 0, '', '_detail', 0, 0, 0, 0, 0, 0, 0),
(63, '2020-05-26 08:30:04', 'en', 'About us', 'about-us', '&lt;p&gt;&lt;strong&gt;Father&amp;amp;Son&lt;/strong&gt; fonda le radici in oltre vent&rsquo;anni di storia a contatto con il mondo dell&rsquo;illuminazione e del design, prima come terzisti di marchi primari, poi come produttori di apparecchi propri. Questa esperienza costituisce un substrato essenziale nei nostri processi, dall&rsquo;ideazione&nbsp;alla produzione. Conoscenza dei materiali, attenzione ai dettagli, scelta del design, sono frutto dell&rsquo;interazione con i nostri fornitori, designer e rivenditori. Il valore aggiunto di Father&amp;amp;Son &egrave; la capacit&agrave; di realizzare prodotti di serie dall&rsquo;elevato valore qualitativo e la possibilit&agrave; di personalizzare i prodotti rendendoli soluzioni su misura.&lt;/p&gt;', 'sementi, fertilizzanti, prodotti giardinaggio', 'E-commerce, vendita online di sementi, fertilizzanti, prodotti per il giardinaggio professionali e hobbistica, bordure in corten.', 0, 204, 1, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(65, '2020-05-26 08:30:04', 'en', 'Cookies', 'cookies', '&lt;p&gt;Utilizzo di cookies&lt;/p&gt;\r\n&lt;p&gt;Il presente sito internet impiega i cosiddetti &ldquo;cookies&rdquo;, ovvero piccoli file testuali che il server di un sito memorizza temporaneamente o in via definitiva sul browser, sul vostro computer o su un altro dispositivo, finalizzati alla semplificazione, integrazione o personalizzazione dell&rsquo;utilizzo delle pagine web. Accendendo al presente sito internet, vi dichiarate d&rsquo;accordo al trattamento dei vostri dati personali da parte di Google. Avete la possibilit&agrave; di disattivare il salvataggio dei cookies attraverso il vostro browser o di cancellare i cookies gi&agrave; memorizzati, tenendo presente che ci&ograve; potrebbe comportare una limitazione delle funzionalit&agrave;, un rallentamento o l&rsquo;inutilizzabilit&agrave; di alcune parti del sito internet. Per ulteriori informazioni sui cookies e sulle modalit&agrave; di cancellazione, in base al tipo di browser impiegato, www.giardineggiando.it vi rimanda al seguente link:&lt;/p&gt;\r\n&lt;p&gt;support.google.com/accounts/answer/32050&lt;/p&gt;\r\n&lt;p&gt;www.giardineggiando.it impiega i seguenti cookies.&lt;/p&gt;\r\n&lt;p&gt;1. Cookies assolutamente necessari, grazie a cui il visitatore ha la possibilit&agrave; di visualizzare la pagina web, utilizzare le funzioni del sito o ottenere l&rsquo;accesso ad aree di sicurezza o registrate. In caso di disattivazione di tali cookies, determinate parti del sito non potranno pi&ugrave; essere impiegate correttamente.&lt;br /&gt;2. Cookies funzionali, con cui www.giardineggiando.it pu&ograve; memorizzare le scelte e le preferenze degli utenti, per incrementare la facilit&agrave; d&rsquo;uso del sito.&lt;br /&gt;3. Cookies per l&rsquo;analisi dell&rsquo;impiego, che raccolgono informazioni sull&rsquo;accesso al presente sito internet (compreso l&rsquo;indirizzo IP). Tali informazioni non hanno carattere personale e sono trasmesse a un server di Google negli USA, dove vengono memorizzate. Google utilizzer&agrave; tali dati per la valutazione dell&rsquo;utilizzo del sito internet, la stesura di report sulle attivit&agrave; del sito a beneficio dei gestori e per altri servizi collegati agli accessi alla pagina e all&rsquo;impiego d&rsquo;internet in generale. Google trasmetter&agrave; tali informazioni a terzi, qualora ci&ograve; sia previsto dalla legge ovvero se tali soggetti si occuperanno del trattamento dei dati per conto di Google. In nessun caso, l&rsquo;indirizzo IP sar&agrave; messo in collegamento con altri dati di Google.&lt;/p&gt;', 'cookies, file testuali', 'Il presente sito internet impiega i cosiddetti &ldquo;cookies&rdquo;, ovvero piccoli file testuali che il server di un sito memorizza temporaneamente....', 0, 206, 0, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(67, '2020-05-26 08:30:04', 'en', 'Condizioni Generali Di Vendita', 'condizioni-generali-di-vendita', 'Condizioni di vendita', 'condizioni generali di vendita', 'Condizioni generali di vendita: I beni oggetto delle presenti condizioni generali sono posti in vendita da Lian snc con sede in Via Arrigoni 53/d, Peraga...', 0, 207, 0, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(69, '2020-05-26 08:30:04', 'en', 'Newsletter', 'newsletter', '', 'newsletter', '', 0, 210, 0, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(71, '2020-05-26 08:30:04', 'en', 'Grazie', 'grazie', 'Grazie per averci contattato, vi risponderemo il prima possibile.&lt;br /&gt;&lt;br /&gt;&lt;br /&gt;', 'grazie', 'Grazie per averci contattato, vi risponderemo il prima possibile', 0, 227, 0, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(73, '2020-05-26 08:32:34', 'en', 'Lighting 2018', 'lighting-2018', '', '', '', 0, 370, 0, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(79, '2020-05-30 19:55:48', 'en', 'AAAA', 'aaaa-5388', '', '', '', 0, 186, 0, 'aaa', '111 2222', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(81, '2020-05-30 19:55:48', 'en', 'Slide 2', 'slide-2', '', '', '', 0, 365, 0, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(83, '2020-05-30 19:55:48', 'en', 'Slide 3', 'slide-3', '', '', '', 0, 367, 0, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(89, '2020-06-08 19:10:29', 'en', 'aaa', 'aaa', '', '', '', 106, 0, 0, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(91, '2020-06-08 19:10:45', 'en', 'bbb', 'ccc', '', '', '', 107, 0, 0, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(93, '2020-06-15 13:21:49', 'en', '111', '111-6638', '', '', '', 108, 0, 0, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(135, '2020-06-22 08:21:52', 'en', 'GIARDINEGGIANDO, AL FIANCO DEL PROFESSIONISTA', 'giardineggiando-al-fianco-del-professionista', '        &lt;div class=&quot;main clearfix &quot;&gt;\r\n            &lt;div class=&quot;content fullwidth&quot;&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;aq-block aq-block-aq_title_border_block aq_span12 aq-first cf&quot;&gt;\r\n                &lt;div class=&quot;border-block&quot;&gt;\r\n                    &lt;div class=&quot;title-block-wrap&quot;&gt;\r\n                        &lt;div class=&quot;titletext margintitle&quot;&gt; &lt;/div&gt;\r\n                    &lt;/div&gt;\r\n                    &lt;div id=&quot;aq-block-9551-12&quot; class=&quot;aq-block aq-block-pmc_prebuild_start_title_small aq_span12 aq-first cf&quot;&gt;\r\n                        &lt;div id=&quot;aq-block-9551-13&quot; class=&quot;aq-block aq-block-aq_quote_title_block aq_span12 aq-first cf&quot;&gt;\r\n                            &lt;div class=&quot;infotextwrap&quot;&gt;\r\n                    			&lt;div class=&quot;infotext&quot;&gt;\r\n                    				&lt;div class=&quot;infotext-before&quot;&gt;&lt;/div&gt;\r\n                    				&lt;div class=&quot;infotext-title&quot;&gt;\r\n                    					&lt;h2 style=&quot;color:#fff&quot;&gt;[testo home_2_top]&lt;/h2&gt;\r\n                    					&lt;div class=&quot;infotext-title-small&quot; style=&quot;color:#fff&quot;&gt;\r\n                    						&lt;p&gt;[testo home_2_middle]&lt;/p&gt;\r\n                    					&lt;/div&gt;\r\n                    				&lt;/div&gt;\r\n                    				&lt;div class=&quot;infotext-after&quot;&gt;&lt;/div&gt;\r\n                    			&lt;/div&gt;\r\n                    		&lt;/div&gt;\r\n                    	&lt;/div&gt;\r\n                    	&lt;div id=&quot;aq-block-9551-14&quot; class=&quot;aq-block aq-block-aq_clear_block aq_span12 aq-first cf&quot;&gt;\r\n                    		&lt;div class=&quot;cf&quot; style=&quot;height:30px; background:&quot;&gt;&lt;/div&gt;\r\n                    	&lt;/div&gt;\r\n                    	&lt;div id=&quot;aq-block-9551-15&quot; class=&quot;aq-block aq-block-aq_logo_block aq_span12 aq-first cf&quot;&gt;\r\n                    		&lt;div class=&quot;logo-center&quot;&gt;\r\n                    			&lt;a href=&quot;[baseUrl]&quot;&gt;[testo home_2_img]&lt;/a&gt;\r\n                    		&lt;/div&gt;\r\n                    	&lt;/div&gt;\r\n                    	&lt;div id=&quot;aq-block-9551-16&quot; class=&quot;aq-block aq-block-aq_richtext_block aq_span12 aq-first cf&quot;&gt;&lt;br&gt;\r\n                    		&lt;div class=&quot;contact-opus-button&quot; style=&quot;text-align: center;&quot;&gt;&lt;a title=&quot;Contattaci per il tuo prodotto su misura&quot; href=&quot;[baseUrl]/crea-account/&quot;&gt;REGISTRATI ORA&lt;/a&gt;&lt;/div&gt;\r\n                    	&lt;/div&gt;\r\n                    &lt;/div&gt;\r\n                    &lt;div class=&quot;aq-block aq-block-aq_end_content_block aq_span12 aq-first cf&quot;&gt;\r\n                    &lt;/div&gt;\r\n                &lt;/div&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;aq-block aq-block-aq_end_content_block aq_span12 aq-first cf&quot;&gt;\r\n            &lt;/div&gt;\r\n        &lt;/div&gt;', '', '', 0, 209, 0, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(138, '2020-06-22 08:21:52', 'en', 'LA NOSTRA ESPERIENZA,  LA TUA PASSIONE', 'la-nostra-esperienza-la-tua-passione', '&lt;div class=&quot;main clearfix &quot;&gt;\r\n    &lt;div class=&quot;content fullwidth&quot;&gt;\r\n    &lt;/div&gt;\r\n    &lt;div id=&quot;aq-block-9551-9&quot; class=&quot;aq-block aq-block-aq_quote_title_block aq_span12 aq-first cf&quot;&gt;\r\n        &lt;div class=&quot;infotextwrap&quot;&gt;\r\n\r\n            &lt;div class=&quot;infotext&quot;&gt;\r\n                &lt;div class=&quot;infotext-before&quot;&gt;&lt;/div&gt;\r\n                &lt;div class=&quot;infotext-title&quot;&gt;\r\n                    &lt;h2 style=&quot;color:#fff&quot;&gt;LA NOSTRA ESPERIENZA, &lt;br&gt;LA TUA PASSIONE&lt;/h2&gt;\r\n                    &lt;div class=&quot;infotext-title-small&quot; style=&quot;color:#fff&quot;&gt;\r\n                        &lt;p&gt;&lt;br&gt;&lt;br&gt;SEMENTI E FERTILIZZANTI DEI MAGGIORI PRODUTTORI INTERNAZIONALI.&lt;br&gt;ARREDOGIARDINO IN ACCIAIO COR-TEN PRODOTTO ARTIGIANALMENTE IN ITALIA, ANCHE SU MISURA!&lt;br&gt;ATTREZZATURE PER IL GIARDINAGGIO PROFESSIONALE DEI MAGGIORI PRODUTTORI\r\n                            AL MONDO.&lt;/p&gt;\r\n                    &lt;/div&gt;\r\n                &lt;/div&gt;\r\n                &lt;div class=&quot;infotext-after&quot;&gt;&lt;/div&gt;\r\n            &lt;/div&gt;\r\n        &lt;/div&gt;\r\n    &lt;/div&gt;\r\n    &lt;div id=&quot;aq-block-9551-10&quot; class=&quot;aq-block aq-block-aq_richtext_block aq_span12 aq-first cf&quot;&gt;&lt;br&gt;&lt;img style=&quot;margin-right: auto; margin-left: auto; display: block;&quot; src=&quot;http://www.giardineggiando.it/wp-content/uploads/2013/12/eco1.png&quot; alt=&quot;&quot; width=&quot;129&quot; height=&quot;150&quot;&gt;\r\n        &lt;div style=&quot;text-align: center;&quot;&gt;&lt;span style=&quot;color: #ffffff;&quot;&gt;&lt;br&gt;ISCRIVITI ALLA NOSTRA NEWSLETTER,&lt;br&gt; TI AGGIORNEREMO SULLE ULTIME NOVITA&#039; E SULLE NOSTRE PROMOZIONI&lt;/span&gt;&lt;/div&gt;\r\n        &lt;h4 style=&quot;text-align: center;&quot;&gt;&lt;span style=&quot;color: #000000; background-color: #ffffff;&quot;&gt;&lt;strong&gt;&lt;a href=&quot;http://www.giardineggiando.it/it/newsletter&quot;&gt;ISCRIVITI ALLA NEWSLETTER&lt;/a&gt;&lt;br&gt;&lt;/strong&gt;&lt;/span&gt;&lt;/h4&gt;\r\n    &lt;/div&gt;\r\n    &lt;div id=&quot;aq-block-9551-11&quot; class=&quot;aq-block aq-block-aq_end_content_block aq_span12 aq-first cf&quot;&gt; &lt;/div&gt;\r\n&lt;/div&gt;', '', '', 0, 208, 0, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(141, '2020-06-27 09:45:01', 'en', 'Slide', 'slide', '', '', '', 85, 0, 0, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(144, '2020-06-27 09:45:02', 'en', 'Home', 'home', '', '', '', 86, 0, 0, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(150, '2020-06-27 09:45:02', 'en', 'Slide sotto', 'slide-sotto', '', '', '', 103, 0, 0, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(153, '2020-07-11 10:05:31', 'en', 'Referenze', 'referenze', '', '', '', 109, 0, 0, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(159, '2020-07-11 10:07:19', 'en', 'yyyy', 'yyyy', '', '', '', 0, 383, 0, '', '', 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0),
(165, '2020-07-11 10:10:15', 'en', 'yyyy7777', 'yyyy7777', '', '', '', 0, 385, 1, '', '', 0, 0, '', 'referenze_detail', 0, 0, 0, 0, 0, 0, 0),
(168, '2020-07-27 16:05:00', 'en', '', 'technical-aa', '', '', '', 0, 0, 0, '', '', 0, 0, 'Technical aa', '-marchio-', 6, 0, 0, 0, 0, 0, 0),
(171, '2020-07-27 16:05:00', 'en', '', 'tige', '', '', '', 0, 0, 0, '', '', 0, 0, 'Tige', '-marchio-', 5, 0, 0, 0, 0, 0, 0),
(219, '2020-07-28 07:42:38', 'en', 'uuu', 'uuu', '', '', '', 111, 0, 1, '', '', 0, 0, '', 'prodotti', 0, 0, 0, 0, 0, 0, 0),
(222, '2020-07-30 11:34:39', 'en', 'TEST', 'test', '', '', '', 0, 387, 0, '', '', 0, 0, '', 'team_detail', 0, 0, 0, 0, 0, 0, 0),
(225, '2020-07-30 11:35:46', 'en', '', '', '', '', '', 0, 0, 0, '', '', 1, 0, 'TEST', '-car-', 0, 0, 0, 0, 0, 0, 0),
(228, '2020-07-30 11:35:54', 'en', '', '', '', '', '', 0, 0, 0, '', '', 0, 1, 'CCCC', '-cv-', 0, 0, 0, 0, 0, 0, 0),
(231, '2020-08-01 08:45:32', 'en', 'Team', 'team', '', '', '', 110, 0, 0, '', '', 0, 0, '', 'team', 0, 0, 0, 0, 0, 0, 0),
(249, '2020-08-01 09:44:02', 'en', 'Privacy', 'privacy', '', '', '', 0, 390, 0, '', '', 0, 0, '', '_detail', 0, 0, 0, 0, 0, 0, 0),
(257, '2020-08-29 09:45:04', 'en', '', '', '', '', '', 0, 0, 1, '', '', 0, 0, 'bbb', 'personalizzazioni', 0, 0, 0, 0, 0, 1, 0),
(258, '2020-08-29 09:45:05', 'en', '', '', '', '', '', 0, 0, 0, '', '', 0, 0, 'BBB', 'personalizzazioni', 0, 0, 0, 0, 0, 2, 0),
(259, '2020-08-29 10:12:29', 'en', '', '', '', '', '', 0, 0, 1, '', '', 0, 0, 'Engraving text', 'personalizzazioni', 0, 0, 0, 0, 0, 3, 0),
(260, '2020-08-29 10:35:09', 'en', '', '', '', '', '', 0, 0, 0, '', '', 0, 0, 'Pers 2', 'personalizzazioni', 0, 0, 0, 0, 0, 4, 0),
(272, '2020-09-08 12:39:20', 'en', '', 'christmas-2020', '', '', '', 0, 0, 1, '', '', 0, 0, 'Christmas 2020', 'tag', 0, 0, 0, 0, 0, 0, 3),
(276, '2020-09-26 09:19:18', 'en', 'Download', 'download', '', '', '', 112, 0, 0, '', '', 0, 0, '', 'download', 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `corrieri`
--

CREATE TABLE `corrieri` (
  `id_corriere` int(10) UNSIGNED NOT NULL,
  `titolo` varchar(200) CHARACTER SET utf8 NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL,
  `prezzo` decimal(10,2) DEFAULT NULL,
  `attivo` enum('Y','N') NOT NULL DEFAULT 'Y'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `corrieri`
--

INSERT INTO `corrieri` (`id_corriere`, `titolo`, `id_order`, `prezzo`, `attivo`) VALUES
(10, 'Corriere espresso', 1, NULL, 'Y'),
(11, 'Corriere standard', 2, NULL, 'Y');

-- --------------------------------------------------------

--
-- Struttura della tabella `corrieri_spese`
--

CREATE TABLE `corrieri_spese` (
  `id_spesa` int(10) UNSIGNED NOT NULL,
  `peso` decimal(10,2) DEFAULT NULL,
  `prezzo` decimal(10,2) DEFAULT NULL,
  `id_corriere` int(10) UNSIGNED NOT NULL,
  `nazione` char(2) NOT NULL DEFAULT 'W'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `corrieri_spese`
--

INSERT INTO `corrieri_spese` (`id_spesa`, `peso`, `prezzo`, `id_corriere`, `nazione`) VALUES
(80, '1.00', '6.50', 10, 'IT'),
(81, '4.00', '9.00', 10, 'IT'),
(82, '1.00', '1.00', 11, 'IT'),
(83, '4.00', '3.00', 11, 'IT'),
(84, '1.00', '10.00', 10, 'W'),
(85, '4.00', '20.00', 10, 'W');

-- --------------------------------------------------------

--
-- Struttura della tabella `documenti`
--

CREATE TABLE `documenti` (
  `id_doc` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `titolo` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `filename` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `clean_filename` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `lingua` char(10) NOT NULL DEFAULT 'tutte',
  `estensione` char(10) NOT NULL,
  `content_type` varchar(100) NOT NULL,
  `id_page` int(10) UNSIGNED NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL,
  `data_documento` date NOT NULL,
  `immagine` varchar(255) NOT NULL DEFAULT '',
  `clean_immagine` varchar(255) NOT NULL DEFAULT '',
  `id_tipo_doc` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `fatture`
--

CREATE TABLE `fatture` (
  `id_f` int(10) UNSIGNED NOT NULL,
  `id_o` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `filename` varchar(200) NOT NULL,
  `numero` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `id_order` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `immagini`
--

CREATE TABLE `immagini` (
  `id_immagine` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `immagine` varchar(100) NOT NULL,
  `id_page` int(10) UNSIGNED NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `immagini`
--

INSERT INTO `immagini` (`id_immagine`, `data_creazione`, `immagine`, `id_page`, `id_order`) VALUES
(135, '2014-09-01 08:42:38', 'aaaaaaa_b.jpg', 90, 3),
(136, '2014-09-01 08:42:38', 'aaaaaaa_g.jpg', 90, 4),
(137, '2014-09-01 08:42:56', 'aaaaaaa_b.jpg', 91, 5),
(138, '2014-09-01 08:42:56', 'aaaaaaa_g.jpg', 91, 6),
(460, '2018-08-15 11:55:08', 'spedizioni-1-.png', 205, 243),
(461, '2018-08-15 11:55:08', 'spedizioni.png', 205, 245),
(462, '2018-08-15 11:55:08', 'ordine-2632-1-.png', 205, 246),
(463, '2018-08-15 11:55:08', 'ordine-2632.png', 205, 244),
(533, '2020-07-11 10:08:43', 'img10_2.jpg', 375, 308),
(534, '2020-07-11 10:08:43', 'img1_8.jpg', 375, 309),
(535, '2020-07-11 10:08:44', 'img9_3.jpg', 375, 315),
(536, '2020-07-11 10:08:44', 'img10-1-_1.jpg', 375, 310),
(537, '2020-07-11 10:08:44', 'alert_pagamento_1.png', 375, 311),
(538, '2020-07-11 10:08:44', 'categorie_1.png', 375, 312),
(539, '2020-07-11 10:08:44', 'dashboard_4.png', 375, 313),
(540, '2020-07-11 10:08:44', 'famiglie_2.png', 375, 314),
(542, '2020-07-11 10:09:42', 'img9_4.jpg', 383, 316),
(543, '2020-07-11 10:09:45', 'img10-1-_2.jpg', 383, 317),
(544, '2020-07-11 10:09:48', 'img10_3.jpg', 383, 318),
(545, '2020-07-11 10:09:52', 'img1_9.jpg', 383, 319),
(550, '2020-07-11 10:10:15', 'img9_4.jpg', 385, 320),
(551, '2020-07-11 10:10:15', 'img10-1-_2.jpg', 385, 321),
(552, '2020-07-11 10:10:15', 'img10_3.jpg', 385, 322),
(553, '2020-07-11 10:10:15', 'img1_9.jpg', 385, 323);

-- --------------------------------------------------------

--
-- Struttura della tabella `impostazioni`
--

CREATE TABLE `impostazioni` (
  `id_imp` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `iva` char(10) NOT NULL DEFAULT '22',
  `usa_smtp` enum('Y','N') NOT NULL DEFAULT 'N',
  `smtp_host` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `smtp_port` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `smtp_user` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `smtp_psw` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `smtp_from` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `smtp_nome` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `bcc` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `mail_invio_ordine` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `mail_invio_conferma_pagamento` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `nome_sito` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `iva_inclusa` enum('Y','N') NOT NULL DEFAULT 'N',
  `usa_sandbox` enum('Y','N') NOT NULL DEFAULT 'N',
  `paypal_seller` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `paypal_sandbox_seller` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `title_home_page` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `esponi_prezzi_ivati` enum('Y','N') NOT NULL DEFAULT 'N',
  `redirect_immediato_a_paypal` enum('Y','N') NOT NULL DEFAULT 'N',
  `mailchimp_api_key` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `mailchimp_list_id` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `mostra_scritta_iva_inclusa` enum('Y','N') NOT NULL DEFAULT 'N',
  `analytics` text NOT NULL,
  `manda_mail_fattura_in_automatico` enum('Y','N') NOT NULL DEFAULT 'N',
  `meta_description` text NOT NULL,
  `keywords` text NOT NULL,
  `spedizioni_gratuite_sopra_euro` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `impostazioni`
--

INSERT INTO `impostazioni` (`id_imp`, `data_creazione`, `iva`, `usa_smtp`, `smtp_host`, `smtp_port`, `smtp_user`, `smtp_psw`, `smtp_from`, `smtp_nome`, `bcc`, `mail_invio_ordine`, `mail_invio_conferma_pagamento`, `nome_sito`, `iva_inclusa`, `usa_sandbox`, `paypal_seller`, `paypal_sandbox_seller`, `title_home_page`, `esponi_prezzi_ivati`, `redirect_immediato_a_paypal`, `mailchimp_api_key`, `mailchimp_list_id`, `mostra_scritta_iva_inclusa`, `analytics`, `manda_mail_fattura_in_automatico`, `meta_description`, `keywords`, `spedizioni_gratuite_sopra_euro`) VALUES
(1, '2018-02-25 18:05:00', '22', 'N', '', '25', '', '', 'EcommerceMyAdmin@test.it', 'EcommerceMyAdmin', '', '', '', 'EcommerceMyAdmin', 'N', 'Y', '', 'seller_1295877693_biz@yahoo.com', 'Sito di test', 'Y', 'Y', '', '', 'Y', '', 'N', '111', '222', 1000);

-- --------------------------------------------------------

--
-- Struttura della tabella `iva`
--

CREATE TABLE `iva` (
  `id_iva` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `titolo` char(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `valore` decimal(10,2) NOT NULL DEFAULT '0.00',
  `id_order` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `tipo` char(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `iva`
--

INSERT INTO `iva` (`id_iva`, `data_creazione`, `titolo`, `valore`, `id_order`, `tipo`) VALUES
(1, '2018-03-13 13:21:39', 'Iva al 22%', '22.00', 1, ''),
(3, '2018-03-13 15:24:14', 'Iva al 4%', '4.00', 3, ''),
(4, '2020-09-22 14:23:30', 'Ex art. 41 del D.L. n. 331/1993', '0.00', 4, 'B2BUE'),
(5, '2020-09-22 14:23:55', 'Ex art. 7 c.4 del D.P.R. 633/72', '0.00', 5, 'B2BEX'),
(6, '2020-09-22 14:24:48', 'Ex Art.8', '0.00', 6, 'B2CEX');

-- --------------------------------------------------------

--
-- Struttura della tabella `lingue`
--

CREATE TABLE `lingue` (
  `id_lingua` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `codice` char(5) CHARACTER SET utf8 DEFAULT NULL,
  `descrizione` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `id_order` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `principale` tinyint(4) NOT NULL DEFAULT '0',
  `attiva` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `lingue`
--

INSERT INTO `lingue` (`id_lingua`, `data_creazione`, `codice`, `descrizione`, `id_order`, `principale`, `attiva`) VALUES
(1, '2018-08-16 21:45:59', 'it', 'Italiano', 1, 1, 1),
(2, '2018-08-16 21:45:59', 'en', 'Inglese', 2, 0, 1),
(3, '2018-08-16 21:45:59', 'fr', 'Francese', 3, 0, 0),
(4, '2018-08-16 21:45:59', 'es', 'Spagnolo', 4, 0, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `mail_ordini`
--

CREATE TABLE `mail_ordini` (
  `id_mail` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_o` int(10) UNSIGNED NOT NULL,
  `tipo` enum('F','P','C','A','R') NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `mail_ordini`
--

INSERT INTO `mail_ordini` (`id_mail`, `data_creazione`, `id_o`, `tipo`) VALUES
(1, '2019-12-02 15:43:14', 2, 'R'),
(2, '2020-05-18 20:06:58', 5, 'R'),
(3, '2020-07-06 13:28:10', 6, 'R'),
(4, '2020-08-12 18:28:32', 3, 'R'),
(5, '2020-08-12 18:31:39', 3, 'R'),
(6, '2020-08-12 18:32:21', 3, 'R'),
(7, '2020-08-12 18:36:49', 3, 'R'),
(8, '2020-08-12 18:41:19', 3, 'A'),
(9, '2020-08-12 18:45:23', 3, 'R'),
(10, '2020-08-12 18:45:49', 3, 'R'),
(11, '2020-08-12 18:45:52', 3, 'P'),
(12, '2020-08-12 18:45:54', 3, 'C'),
(13, '2020-08-12 18:45:56', 3, 'A'),
(14, '2020-08-12 18:46:45', 3, 'R'),
(15, '2020-08-12 18:46:48', 3, 'P'),
(16, '2020-08-12 18:46:50', 3, 'C'),
(17, '2020-08-12 18:48:32', 3, 'A'),
(18, '2020-08-12 18:48:34', 3, 'R'),
(19, '2020-08-12 18:48:37', 3, 'P'),
(20, '2020-08-12 18:48:40', 3, 'C'),
(21, '2020-08-12 18:53:01', 3, 'F'),
(22, '2020-08-23 16:58:26', 9, 'R'),
(23, '2020-08-23 17:00:07', 9, 'R'),
(24, '2020-08-23 17:02:06', 9, 'R'),
(25, '2020-08-23 17:02:33', 9, 'R'),
(26, '2020-08-23 17:03:34', 9, 'R'),
(27, '2020-08-23 17:04:46', 9, 'R'),
(28, '2020-08-23 17:07:25', 9, 'R'),
(29, '2020-08-23 17:10:16', 9, 'R'),
(30, '2020-08-23 17:11:03', 9, 'R'),
(31, '2020-08-23 17:12:03', 9, 'R'),
(32, '2020-08-23 17:27:52', 9, 'F'),
(33, '2020-09-07 09:25:41', 13, 'R'),
(34, '2020-09-07 09:43:38', 13, 'R'),
(35, '2020-09-07 10:05:10', 13, 'R'),
(36, '2020-09-07 10:05:30', 13, 'F'),
(37, '2020-09-07 10:09:51', 14, 'R'),
(38, '2020-09-07 11:09:52', 13, 'R');

-- --------------------------------------------------------

--
-- Struttura della tabella `marchi`
--

CREATE TABLE `marchi` (
  `id_marchio` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `titolo` char(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `descrizione` text CHARACTER SET utf8 NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `immagine` varchar(200) CHARACTER SET utf8 NOT NULL,
  `alias` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `immagine_2x` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `marchi`
--

INSERT INTO `marchi` (`id_marchio`, `data_creazione`, `titolo`, `descrizione`, `id_order`, `immagine`, `alias`, `immagine_2x`) VALUES
(5, '2019-10-14 08:38:52', 'Tige', 'TIGE\r\n', 1, 'famiglia-tige.jpg', 'tige', ''),
(6, '2019-10-14 08:38:58', 'Technical aa', 'TECHNICAL\r\n', 2, 'famiglia-tecnico.jpg', 'technical-aa', 'youtube-2x.png');

-- --------------------------------------------------------

--
-- Struttura della tabella `menu`
--

CREATE TABLE `menu` (
  `id_m` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `attivo` char(1) NOT NULL,
  `title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `alias` varchar(100) CHARACTER SET utf8 NOT NULL,
  `link_to` char(10) CHARACTER SET utf8 NOT NULL,
  `link_id` varchar(300) CHARACTER SET utf8 NOT NULL,
  `id_p` int(10) UNSIGNED NOT NULL,
  `lft` int(10) UNSIGNED NOT NULL,
  `rgt` int(10) UNSIGNED NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL,
  `id_c` int(10) UNSIGNED NOT NULL,
  `id_page` int(10) UNSIGNED NOT NULL,
  `link_alias` varchar(300) CHARACTER SET utf8 NOT NULL,
  `active_link` char(1) NOT NULL DEFAULT 'Y',
  `lingua` char(2) NOT NULL DEFAULT 'it',
  `file_custom_html` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `menu`
--

INSERT INTO `menu` (`id_m`, `data_creazione`, `attivo`, `title`, `alias`, `link_to`, `link_id`, `id_p`, `lft`, `rgt`, `id_order`, `id_c`, `id_page`, `link_alias`, `active_link`, `lingua`, `file_custom_html`) VALUES
(1, '2013-07-18 14:58:34', 'Y', '-- root --', '---root---', '', '', 0, 1, 34, 0, 0, 0, '', 'Y', '', ''),
(11, '2014-09-01 10:02:11', '', 'Shop', 'shop', 'cat', '', 1, 4, 15, 2, 84, 176, 'http://easystart/it/prodotti.html', 'Y', 'it', ''),
(16, '2018-02-26 11:16:27', '', 'Home', 'home', 'home', '', 1, 2, 3, 1, 84, 176, 'http://easystart/it', 'Y', 'it', ''),
(17, '2018-02-26 11:17:30', '', 'Chi siamo', 'chi-siamo', 'cont', '', 1, 16, 17, 3, 84, 204, 'http://easystart/it/chi-siamo.html', 'Y', 'it', ''),
(18, '2018-02-26 11:17:41', '', 'Blog', 'blog', 'cat', '', 1, 18, 19, 4, 87, 176, 'http://easystart/it/blog.html', 'Y', 'it', ''),
(19, '2018-02-26 11:17:55', '', 'Newsletter', 'newsletter', 'cont', '', 1, 20, 21, 5, 84, 210, 'http://easystart/it/newsletter.html', 'Y', 'it', ''),
(20, '2018-02-26 11:18:14', '', 'Contattaci', 'contattaci', 'cont', '', 1, 22, 23, 6, 84, 205, 'http://easystart/it/contattaci.html', 'Y', 'it', ''),
(22, '2019-10-14 08:42:47', '', 'Sospensione', 'sospensione', 'cat', '', 11, 5, 6, 7, 97, 205, 'http://easystart/it/prodotti/sospensione.html', 'Y', 'it', ''),
(23, '2019-10-14 08:43:09', '', 'Parete', 'parete', 'cat', '', 11, 7, 8, 8, 98, 205, 'http://easystart/it/prodotti/parete.html', 'Y', 'it', ''),
(24, '2019-10-14 08:43:25', '', 'Tavolo', 'tavolo', 'cat', '', 11, 9, 10, 9, 100, 205, 'http://easystart/it/prodotti/tavolo.html', 'Y', 'it', ''),
(25, '2019-10-14 08:43:36', '', 'Terra', 'terra', 'cat', '', 11, 11, 12, 10, 101, 205, 'http://easystart/it/prodotti/terra.html', 'Y', 'it', ''),
(26, '2019-10-14 08:43:47', '', 'Accessori', 'accessori', 'cat', '', 11, 13, 14, 11, 102, 205, 'http://easystart/it/prodotti/accessori.html', 'Y', 'it', ''),
(27, '2020-05-25 08:39:39', '', 'Home EN', 'home-en', 'home', '', 1, 24, 25, 12, 84, 205, 'http://easystart/en', 'Y', 'en', ''),
(28, '2020-05-25 08:40:00', '', 'Shop EN', 'shop-en', 'cat', '', 1, 26, 29, 13, 84, 205, 'http://easystart/en/shop.html', 'Y', 'en', ''),
(29, '2020-05-25 08:43:10', '', 'Wall', 'wall', 'cat', '', 28, 27, 28, 14, 98, 205, 'http://easystart/en/shop/wall.html', 'Y', 'en', ''),
(31, '2020-05-25 15:00:24', '', 'Home FR', 'home-fr', 'home', '', 1, 30, 31, 16, 84, 205, 'http://easystart/fr', 'Y', 'fr', ''),
(32, '2020-06-15 13:44:28', '', 'Home', 'home-7652', 'home', '', 1, 32, 33, 17, 84, 204, 'http://easystart/es', 'Y', 'es', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `menu_sec`
--

CREATE TABLE `menu_sec` (
  `id_m` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `attivo` char(1) NOT NULL DEFAULT 'Y',
  `title` varchar(100) NOT NULL,
  `alias` varchar(100) NOT NULL,
  `link_alias` varchar(300) NOT NULL,
  `link_to` char(10) NOT NULL,
  `id_p` int(10) UNSIGNED NOT NULL,
  `id_c` int(10) UNSIGNED NOT NULL,
  `id_page` int(10) UNSIGNED NOT NULL,
  `lft` int(10) UNSIGNED NOT NULL,
  `rgt` int(10) UNSIGNED NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL,
  `active_link` char(1) NOT NULL DEFAULT 'Y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `menu_sec`
--

INSERT INTO `menu_sec` (`id_m`, `data_creazione`, `attivo`, `title`, `alias`, `link_alias`, `link_to`, `id_p`, `id_c`, `id_page`, `lft`, `rgt`, `id_order`, `active_link`) VALUES
(1, '2013-10-01 12:16:12', 'Y', '-- root --', '---root---', '', '', 0, 0, 0, 1, 6, 0, 'Y'),
(2, '2013-10-01 12:32:26', 'Y', 'Laterale 1', 'laterale-1', 'http://easystart/', 'cat', 1, 48, 6, 2, 3, 1, 'Y'),
(10, '2013-10-02 07:06:09', 'Y', 'No link', 'no-link', 'http://easystart/', 'cat', 1, 41, 6, 4, 5, 4, 'N');

-- --------------------------------------------------------

--
-- Struttura della tabella `nazioni`
--

CREATE TABLE `nazioni` (
  `id_nazione` int(11) NOT NULL,
  `titolo` varchar(255) NOT NULL DEFAULT '',
  `iso_country_code` varchar(6) NOT NULL,
  `tipo` char(2) DEFAULT '2',
  `attiva` tinyint(4) NOT NULL DEFAULT '1',
  `attiva_spedizione` tinyint(4) NOT NULL DEFAULT '1',
  `latitudine` char(15) NOT NULL DEFAULT '',
  `longitudine` char(15) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `nazioni`
--

INSERT INTO `nazioni` (`id_nazione`, `titolo`, `iso_country_code`, `tipo`, `attiva`, `attiva_spedizione`, `latitudine`, `longitudine`) VALUES
(2, 'Italia', 'IT', 'UE', 1, 1, '41.87194', '12.56738'),
(3, 'Afghanistan', 'AF', 'EX', 1, 1, '33.93911', '67.709953'),
(4, 'Albania', 'AL', 'EX', 1, 1, '41.153332', '20.168331'),
(5, 'Algeria', 'DZ', 'EX', 1, 1, '28.033886', '1.659626'),
(6, 'American Samoa', 'AS', 'EX', 1, 1, '-14.270972', '-170.132217'),
(7, 'Andorra', 'AD', 'EX', 1, 1, '42.546245', '1.601554'),
(8, 'Angola', 'AO', 'EX', 1, 1, '-11.202692', '17.873887'),
(9, 'Anguilla', 'AI', 'EX', 1, 1, '18.220554', '-63.068615'),
(10, 'Antarctica', 'AQ', 'EX', 1, 1, '-75.250973', '-0.071389'),
(11, 'Antigua and Barbuda', 'AG', 'EX', 1, 1, '17.060816', '-61.796428'),
(12, 'Argentina', 'AR', 'EX', 1, 1, '-38.416097', '-63.616672'),
(13, 'Armenia', 'AM', 'EX', 1, 1, '40.069099', '45.038189'),
(14, 'Aruba', 'AW', 'EX', 1, 1, '12.52111', '-69.968338'),
(15, 'Australia', 'AU', 'EX', 1, 1, '-25.274398', '133.775136'),
(16, 'Austria', 'AT', 'UE', 1, 1, '47.516231', '14.550072'),
(17, 'Azerbaijan', 'AZ', 'EX', 1, 1, '40.143105', '47.576927'),
(18, 'Bahamas', 'BS', 'EX', 1, 1, '25.03428', '-77.39628'),
(19, 'Bahrain', 'BH', 'EX', 1, 1, '25.930414', '50.637772'),
(20, 'Bangladesh', 'BD', 'EX', 1, 1, '23.684994', '90.356331'),
(21, 'Barbados', 'BB', 'EX', 1, 1, '13.193887', '-59.543198'),
(22, 'Belarus', 'BY', 'EX', 1, 1, '53.709807', '27.953389'),
(23, 'Belgium', 'BE', 'UE', 1, 1, '50.503887', '4.469936'),
(24, 'Belize', 'BZ', 'EX', 1, 1, '17.189877', '-88.49765'),
(25, 'Benin', 'BJ', 'EX', 1, 1, '9.30769', '2.315834'),
(26, 'Bermuda', 'BM', 'EX', 1, 1, '32.321384', '-64.75737'),
(27, 'Bhutan', 'BT', 'EX', 1, 1, '27.514162', '90.433601'),
(28, 'Bolivia', 'BO', 'EX', 1, 1, '-16.290154', '-63.588653'),
(29, 'Bonaire, Sint Eustatius and Saba', 'BQ', 'EX', 1, 1, '', ''),
(30, 'Bosnia and Herzegovina', 'BA', 'EX', 1, 1, '43.915886', '17.679076'),
(31, 'Botswana', 'BW', 'EX', 1, 1, '-22.328474', '24.684866'),
(32, 'Brazil', 'BR', 'EX', 1, 1, '-14.235004', '-51.92528'),
(33, 'British Indian Ocean Territory', 'IO', 'EX', 1, 1, '-6.343194', '71.876519'),
(34, 'Brunei Darussalam', 'BN', 'EX', 1, 1, '4.535277', '114.727669'),
(35, 'Bulgaria', 'BG', 'UE', 1, 1, '42.733883', '25.48583'),
(36, 'Burkina Faso', 'BF', 'EX', 1, 1, '12.238333', '-1.561593'),
(37, 'Burundi', 'BI', 'EX', 1, 1, '-3.373056', '29.918886'),
(38, 'Cambodia', 'KH', 'EX', 1, 1, '12.565679', '104.990963'),
(39, 'Cameroon', 'CM', 'EX', 1, 1, '7.369722', '12.354722'),
(40, 'Canada', 'CA', 'EX', 1, 1, '56.130366', '-106.346771'),
(41, 'Cape Verde', 'CV', 'EX', 1, 1, '16.002082', '-24.013197'),
(42, 'Cayman Islands', 'KY', 'EX', 1, 1, '19.513469', '-80.566956'),
(43, 'Central African Republic', 'CF', 'EX', 1, 1, '6.611111', '20.939444'),
(44, 'Chad', 'TD', 'EX', 1, 1, '15.454166', '18.732207'),
(45, 'Chile', 'CL', 'EX', 1, 1, '-35.675147', '-71.542969'),
(46, 'China', 'CN', 'EX', 1, 1, '35.86166', '104.195397'),
(47, 'Christmas Island', 'CX', 'EX', 1, 1, '-10.447525', '105.690449'),
(48, 'Cocos (Keeling) Islands', 'CC', 'EX', 1, 1, '-12.164165', '96.870956'),
(49, 'Colombia', 'CO', 'EX', 1, 1, '4.570868', '-74.297333'),
(50, 'Comoros', 'KM', 'EX', 1, 1, '-11.875001', '43.872219'),
(51, 'Congo', 'CG', 'EX', 1, 1, '-0.228021', '15.827659'),
(52, 'Congo, The Democratic Republic of the', 'CD', 'EX', 1, 1, '-4.038333', '21.758664'),
(53, 'Cook Islands', 'CK', 'EX', 1, 1, '-21.236736', '-159.777671'),
(54, 'Costa Rica', 'CR', 'EX', 1, 1, '9.748917', '-83.753428'),
(55, 'CÃ´te d\'Ivoire', 'CI', 'EX', 1, 1, '7.539989', '-5.54708'),
(56, 'Croatia', 'HR', 'UE', 1, 1, '45.1', '15.2'),
(57, 'Cuba', 'CU', 'EX', 1, 1, '21.521757', '-77.781167'),
(58, 'CuraÃ§ao', 'CW', 'EX', 1, 1, '', ''),
(59, 'Cyprus', 'CY', 'UE', 1, 1, '35.126413', '33.429859'),
(60, 'Czech Republic', 'CZ', 'UE', 1, 1, '49.817492', '15.472962'),
(61, 'Denmark', 'DK', 'UE', 1, 1, '56.26392', '9.501785'),
(62, 'Djibouti', 'DJ', 'EX', 1, 1, '11.825138', '42.590275'),
(63, 'Dominica', 'DM', 'EX', 1, 1, '15.414999', '-61.370976'),
(64, 'Dominican Republic', 'DO', 'EX', 1, 1, '18.735693', '-70.162651'),
(65, 'Ecuador', 'EC', 'EX', 1, 1, '-1.831239', '-78.183406'),
(66, 'Egypt', 'EG', 'EX', 1, 1, '26.820553', '30.802498'),
(67, 'El Salvador', 'SV', 'EX', 1, 1, '13.794185', '-88.89653'),
(68, 'Equatorial Guinea', 'GQ', 'EX', 1, 1, '1.650801', '10.267895'),
(69, 'Eritrea', 'ER', 'EX', 1, 1, '15.179384', '39.782334'),
(70, 'Estonia', 'EE', 'UE', 1, 1, '58.595272', '25.013607'),
(71, 'Ethiopia', 'ET', 'EX', 1, 1, '9.145', '40.489673'),
(72, 'Falkland Islands (Malvinas)', 'FK', 'EX', 1, 1, '-51.796253', '-59.523613'),
(73, 'Faroe Islands', 'FO', 'EX', 1, 1, '61.892635', '-6.911806'),
(74, 'Fiji', 'FJ', 'EX', 1, 1, '-16.578193', '179.414413'),
(75, 'Finland', 'FI', 'UE', 1, 1, '61.92411', '25.748151'),
(76, 'France', 'FR', 'UE', 1, 1, '46.227638', '2.213749'),
(77, 'French Guiana', 'GF', 'EX', 1, 1, '3.933889', '-53.125782'),
(78, 'French Polynesia', 'PF', 'EX', 1, 1, '-17.679742', '-149.406843'),
(79, 'French Southern Territories', 'TF', 'EX', 1, 1, '-49.280366', '69.348557'),
(80, 'Gabon', 'GA', 'EX', 1, 1, '-0.803689', '11.609444'),
(81, 'Gambia', 'GM', 'EX', 1, 1, '13.443182', '-15.310139'),
(82, 'Georgia', 'GE', 'EX', 1, 1, '42.315407', '43.356892'),
(83, 'Germania', 'DE', 'UE', 1, 1, '51.165691', '10.451526'),
(84, 'Ghana', 'GH', 'EX', 1, 1, '7.946527', '-1.023194'),
(85, 'Gibraltar', 'GI', 'EX', 1, 1, '36.137741', '-5.345374'),
(86, 'Greece', 'GR', 'UE', 1, 1, '39.074208', '21.824312'),
(87, 'Greenland', 'GL', 'EX', 1, 1, '71.706936', '-42.604303'),
(88, 'Grenada', 'GD', 'EX', 1, 1, '12.262776', '-61.604171'),
(89, 'Guadeloupe', 'GP', 'EX', 1, 1, '16.995971', '-62.067641'),
(90, 'Guam', 'GU', 'EX', 1, 1, '13.444304', '144.793731'),
(91, 'Guatemala', 'GT', 'EX', 1, 1, '15.783471', '-90.230759'),
(92, 'Guernsey', 'GG', 'EX', 1, 1, '49.465691', '-2.585278'),
(93, 'Guinea', 'GN', 'EX', 1, 1, '9.945587', '-9.696645'),
(94, 'Guinea-Bissau', 'GW', 'EX', 1, 1, '11.803749', '-15.180413'),
(95, 'Guyana', 'GY', 'EX', 1, 1, '4.860416', '-58.93018'),
(96, 'Haiti', 'HT', 'EX', 1, 1, '18.971187', '-72.285215'),
(97, 'Heard Island and McDonald Islands', 'HM', 'EX', 1, 1, '-53.08181', '73.504158'),
(98, 'CittÃ  del vaticano', 'VA', 'EX', 1, 1, '41.902916', '12.453389'),
(99, 'Honduras', 'HN', 'EX', 1, 1, '15.199999', '-86.241905'),
(100, 'Hong Kong', 'HK', 'EX', 1, 1, '22.396428', '114.109497'),
(101, 'Hungary', 'HU', 'UE', 1, 1, '47.162494', '19.503304'),
(102, 'Iceland', 'IS', 'EX', 1, 1, '64.963051', '-19.020835'),
(103, 'India', 'IN', 'EX', 1, 1, '20.593684', '78.96288'),
(104, 'Indonesia', 'ID', 'EX', 1, 1, '-0.789275', '113.921327'),
(105, 'Installations in International Waters', 'XZ', 'EX', 1, 1, '', ''),
(106, 'Iran, Islamic Republic of', 'IR', 'EX', 1, 1, '32.427908', '53.688046'),
(107, 'Iraq', 'IQ', 'EX', 1, 1, '33.223191', '43.679291'),
(108, 'Ireland', 'IE', 'UE', 1, 1, '53.41291', '-8.24389'),
(109, 'Isle of Man', 'IM', 'EX', 1, 1, '54.236107', '-4.548056'),
(110, 'Israel', 'IL', 'EX', 1, 1, '31.046051', '34.851612'),
(111, 'Jamaica', 'JM', 'EX', 1, 1, '18.109581', '-77.297508'),
(112, 'Japan', 'JP', 'EX', 1, 1, '36.204824', '138.252924'),
(113, 'Jersey', 'JE', 'EX', 1, 1, '49.214439', '-2.13125'),
(114, 'Jordan', 'JO', 'EX', 1, 1, '30.585164', '36.238414'),
(115, 'Kazakhstan', 'KZ', 'EX', 1, 1, '48.019573', '66.923684'),
(116, 'Kenya', 'KE', 'EX', 1, 1, '-0.023559', '37.906193'),
(117, 'Kiribati', 'KI', 'EX', 1, 1, '-3.370417', '-168.734039'),
(118, 'Korea, Democratic People\'s Republic of', 'KP', 'EX', 1, 1, '40.339852', '127.510093'),
(119, 'Korea, Republic of', 'KR', 'EX', 1, 1, '35.907757', '127.766922'),
(120, 'Kuwait', 'KW', 'EX', 1, 1, '29.31166', '47.481766'),
(121, 'Kyrgyzstan', 'KG', 'EX', 1, 1, '41.20438', '74.766098'),
(122, 'Lao People\'s Democratic Republic', 'LA', 'EX', 1, 1, '19.85627', '102.495496'),
(123, 'Latvia', 'LV', 'UE', 1, 1, '56.879635', '24.603189'),
(124, 'Lebanon', 'LB', 'EX', 1, 1, '33.854721', '35.862285'),
(125, 'Lesotho', 'LS', 'EX', 1, 1, '-29.609988', '28.233608'),
(126, 'Liberia', 'LR', 'EX', 1, 1, '6.428055', '-9.429499'),
(127, 'Libya', 'LY', 'EX', 1, 1, '26.3351', '17.228331'),
(128, 'Liechtenstein', 'LI', 'EX', 1, 1, '47.166', '9.555373'),
(129, 'Lithuania', 'LT', 'UE', 1, 1, '55.169438', '23.881275'),
(130, 'Luxembourg', 'LU', 'UE', 1, 1, '49.815273', '6.129583'),
(131, 'Macao', 'MO', 'EX', 1, 1, '22.198745', '113.543873'),
(132, 'Macedonia, The former Yugoslav Republic of', 'MK', 'EX', 1, 1, '41.608635', '21.745275'),
(133, 'Madagascar', 'MG', 'EX', 1, 1, '-18.766947', '46.869107'),
(134, 'Malawi', 'MW', 'EX', 1, 1, '-13.254308', '34.301525'),
(135, 'Malaysia', 'MY', 'EX', 1, 1, '4.210484', '101.975766'),
(136, 'Maldives', 'MV', 'EX', 1, 1, '3.202778', '73.22068'),
(137, 'Mali', 'ML', 'EX', 1, 1, '17.570692', '-3.996166'),
(138, 'Malta', 'MT', 'UE', 1, 1, '35.937496', '14.375416'),
(139, 'Marshall Islands', 'MH', 'EX', 1, 1, '7.131474', '171.184478'),
(140, 'Martinique', 'MQ', 'EX', 1, 1, '14.641528', '-61.024174'),
(141, 'Mauritania', 'MR', 'EX', 1, 1, '21.00789', '-10.940835'),
(142, 'Mauritius', 'MU', 'EX', 1, 1, '-20.348404', '57.552152'),
(143, 'Mayotte', 'YT', 'EX', 1, 1, '-12.8275', '45.166244'),
(144, 'Mexico', 'MX', 'EX', 1, 1, '23.634501', '-102.552784'),
(145, 'Micronesia, Federated States of', 'FM', 'EX', 1, 1, '7.425554', '150.550812'),
(146, 'Moldavia', 'MD', 'EX', 1, 1, '47.411631', '28.369885'),
(147, 'Monaco', 'MC', 'EX', 1, 1, '43.750298', '7.412841'),
(148, 'Mongolia', 'MN', 'EX', 1, 1, '46.862496', '103.846656'),
(149, 'Montenegro', 'ME', 'EX', 1, 1, '42.708678', '19.37439'),
(150, 'Montserrat', 'MS', 'EX', 1, 1, '16.742498', '-62.187366'),
(151, 'Morocco', 'MA', 'EX', 1, 1, '31.791702', '-7.09262'),
(152, 'Mozambique', 'MZ', 'EX', 1, 1, '-18.665695', '35.529562'),
(153, 'Myanmar', 'MM', 'EX', 1, 1, '21.913965', '95.956223'),
(154, 'Namibia', 'NA', 'EX', 1, 1, '-22.95764', '18.49041'),
(155, 'Nauru', 'NR', 'EX', 1, 1, '-0.522778', '166.931503'),
(156, 'Nepal', 'NP', 'EX', 1, 1, '28.394857', '84.124008'),
(157, 'Netherlands', 'NL', 'UE', 1, 1, '52.132633', '5.291266'),
(158, 'New Caledonia', 'NC', 'EX', 1, 1, '-20.904305', '165.618042'),
(159, 'New Zealand', 'NZ', 'EX', 1, 1, '-40.900557', '174.885971'),
(160, 'Nicaragua', 'NI', 'EX', 1, 1, '12.865416', '-85.207229'),
(161, 'Niger', 'NE', 'EX', 1, 1, '17.607789', '8.081666'),
(162, 'Nigeria', 'NG', 'EX', 1, 1, '9.081999', '8.675277'),
(163, 'Niue', 'NU', 'EX', 1, 1, '-19.054445', '-169.867233'),
(164, 'Norfolk Island', 'NF', 'EX', 1, 1, '-29.040835', '167.954712'),
(165, 'Northern Mariana Islands', 'MP', 'EX', 1, 1, '17.33083', '145.38469'),
(166, 'Norway', 'NO', 'EX', 1, 1, '60.472024', '8.468946'),
(167, 'Oman', 'OM', 'EX', 1, 1, '21.512583', '55.923255'),
(168, 'Pakistan', 'PK', 'EX', 1, 1, '30.375321', '69.345116'),
(169, 'Palau', 'PW', 'EX', 1, 1, '7.51498', '134.58252'),
(170, 'Palestine, State of', 'PS', 'EX', 1, 1, '31.952162', '35.233154'),
(171, 'Panama', 'PA', 'EX', 1, 1, '8.537981', '-80.782127'),
(172, 'Papua New Guinea', 'PG', 'EX', 1, 1, '-6.314993', '143.95555'),
(173, 'Paraguay', 'PY', 'EX', 1, 1, '-23.442503', '-58.443832'),
(174, 'Peru', 'PE', 'EX', 1, 1, '-9.189967', '-75.015152'),
(175, 'Philippines', 'PH', 'EX', 1, 1, '12.879721', '121.774017'),
(176, 'Pitcairn', 'PN', 'EX', 1, 1, '-24.703615', '-127.439308'),
(177, 'Poland', 'PL', 'UE', 1, 1, '51.919438', '19.145136'),
(178, 'Portugal', 'PT', 'UE', 1, 1, '39.399872', '-8.224454'),
(179, 'Puerto Rico', 'PR', 'EX', 1, 1, '18.220833', '-66.590149'),
(180, 'Qatar', 'QA', 'EX', 1, 1, '25.354826', '51.183884'),
(181, 'Reunion', 'RE', 'EX', 1, 1, '-21.115141', '55.536384'),
(182, 'Romania', 'RO', 'UE', 1, 1, '45.943161', '24.96676'),
(183, 'Russian Federation', 'RU', 'EX', 1, 1, '61.52401', '105.318756'),
(184, 'Rwanda', 'RW', 'EX', 1, 1, '-1.940278', '29.873888'),
(185, 'Saint BarthÃ©lemy', 'BL', 'EX', 1, 1, '', ''),
(186, 'Saint Helena, Ascension and Tristan Da Cunha', 'SH', 'EX', 1, 1, '-24.143474', '-10.030696'),
(187, 'Saint Kitts and Nevis', 'KN', 'EX', 1, 1, '17.357822', '-62.782998'),
(188, 'Saint Lucia', 'LC', 'EX', 1, 1, '13.909444', '-60.978893'),
(189, 'Saint Martin (French Part)', 'MF', 'EX', 1, 1, '', ''),
(190, 'Saint Pierre and Miquelon', 'PM', 'EX', 1, 1, '46.941936', '-56.27111'),
(191, 'Saint Vincent and the Grenadines', 'VC', 'EX', 1, 1, '12.984305', '-61.287228'),
(192, 'Samoa', 'WS', 'EX', 1, 1, '-13.759029', '-172.104629'),
(193, 'San Marino', 'SM', 'EX', 1, 1, '43.94236', '12.457777'),
(194, 'Sao Tome and Principe', 'ST', 'EX', 1, 1, '0.18636', '6.613081'),
(195, 'Saudi Arabia', 'SA', 'EX', 1, 1, '23.885942', '45.079162'),
(196, 'Senegal', 'SN', 'EX', 1, 1, '14.497401', '-14.452362'),
(197, 'Serbia', 'RS', 'EX', 1, 1, '44.016521', '21.005859'),
(198, 'Seychelles', 'SC', 'EX', 1, 1, '-4.679574', '55.491977'),
(199, 'Sierra Leone', 'SL', 'EX', 1, 1, '8.460555', '-11.779889'),
(200, 'Singapore', 'SG', 'EX', 1, 1, '1.352083', '103.819836'),
(201, 'Sint Maarten (Dutch Part)', 'SX', 'EX', 1, 1, '', ''),
(202, 'Slovakia', 'SK', 'UE', 1, 1, '48.669026', '19.699024'),
(203, 'Slovenia', 'SI', 'UE', 1, 1, '46.151241', '14.995463'),
(204, 'Solomon Islands', 'SB', 'EX', 1, 1, '-9.64571', '160.156194'),
(205, 'Somalia', 'SO', 'EX', 1, 1, '5.152149', '46.199616'),
(206, 'South Africa', 'ZA', 'EX', 1, 1, '-30.559482', '22.937506'),
(207, 'South Georgia and the South Sandwich Islands', 'GS', 'EX', 1, 1, '-54.429579', '-36.587909'),
(208, 'South Sudan', 'SS', 'EX', 1, 1, '', ''),
(209, 'Spain', 'ES', 'UE', 1, 1, '40.463667', '-3.74922'),
(210, 'Sri Lanka', 'LK', 'EX', 1, 1, '7.873054', '80.771797'),
(211, 'Sudan', 'SD', 'EX', 1, 1, '12.862807', '30.217636'),
(212, 'Suriname', 'SR', 'EX', 1, 1, '3.919305', '-56.027783'),
(213, 'Svalbard and Jan Mayen', 'SJ', 'EX', 1, 1, '77.553604', '23.670272'),
(214, 'Swaziland', 'SZ', 'EX', 1, 1, '-26.522503', '31.465866'),
(215, 'Sweden', 'SE', 'UE', 1, 1, '60.128161', '18.643501'),
(216, 'Switzerland', 'CH', 'EX', 1, 1, '46.818188', '8.227512'),
(217, 'Syrian Arab Republic', 'SY', 'EX', 1, 1, '34.802075', '38.996815'),
(218, 'Taiwan, Province of China', 'TW', 'EX', 1, 1, '23.69781', '120.960515'),
(219, 'Tajikistan', 'TJ', 'EX', 1, 1, '38.861034', '71.276093'),
(220, 'Tanzania, United Republic of', 'TZ', 'EX', 1, 1, '-6.369028', '34.888822'),
(221, 'Thailand', 'TH', 'EX', 1, 1, '15.870032', '100.992541'),
(222, 'Timor-Leste', 'TL', 'EX', 1, 1, '-8.874217', '125.727539'),
(223, 'Togo', 'TG', 'EX', 1, 1, '8.619543', '0.824782'),
(224, 'Tokelau', 'TK', 'EX', 1, 1, '-8.967363', '-171.855881'),
(225, 'Tonga', 'TO', 'EX', 1, 1, '-21.178986', '-175.198242'),
(226, 'Trinidad and Tobago', 'TT', 'EX', 1, 1, '10.691803', '-61.222503'),
(227, 'Tunisia', 'TN', 'EX', 1, 1, '33.886917', '9.537499'),
(228, 'Turkey', 'TR', 'EX', 1, 1, '38.963745', '35.243322'),
(229, 'Turkmenistan', 'TM', 'EX', 1, 1, '38.969719', '59.556278'),
(230, 'Turks and Caicos Islands', 'TC', 'EX', 1, 1, '21.694025', '-71.797928'),
(231, 'Tuvalu', 'TV', 'EX', 1, 1, '-7.109535', '177.64933'),
(232, 'Uganda', 'UG', 'EX', 1, 1, '1.373333', '32.290275'),
(233, 'Ukraine', 'UA', 'EX', 1, 1, '48.379433', '31.16558'),
(234, 'United Arab Emirates', 'AE', 'EX', 1, 1, '23.424076', '53.847818'),
(235, 'United Kingdom', 'UK', 'EX', 1, 1, '', ''),
(236, 'United States', 'US', 'EX', 1, 1, '37.09024', '-95.712891'),
(237, 'United States Minor Outlying Islands', 'UM', 'EX', 1, 1, '', ''),
(238, 'Uruguay', 'UY', 'EX', 1, 1, '-32.522779', '-55.765835'),
(239, 'Uzbekistan', 'UZ', 'EX', 1, 1, '41.377491', '64.585262'),
(240, 'Vanuatu', 'VU', 'EX', 1, 1, '-15.376706', '166.959158'),
(241, 'Venezuela', 'VE', 'EX', 1, 1, '6.42375', '-66.58973'),
(242, 'Viet Nam', 'VN', 'EX', 1, 1, '14.058324', '108.277199'),
(243, 'Virgin Islands, British', 'VG', 'EX', 1, 1, '18.420695', '-64.639968'),
(244, 'Virgin Islands, U.S.', 'VI', 'EX', 1, 1, '18.335765', '-64.896335'),
(245, 'Wallis and Futuna', 'WF', 'EX', 1, 1, '-13.768752', '-177.156097'),
(246, 'Western Sahara', 'EH', 'EX', 1, 1, '24.215527', '-12.885834'),
(247, 'Yemen', 'YE', 'EX', 1, 1, '15.552727', '48.516388'),
(248, 'Zambia', 'ZM', 'EX', 1, 1, '-13.133897', '27.849332'),
(249, 'Zimbabwe', 'ZW', 'EX', 1, 1, '-19.015438', '29.154857');

-- --------------------------------------------------------

--
-- Struttura della tabella `news`
--

CREATE TABLE `news` (
  `id_n` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `titolo` varchar(200) NOT NULL,
  `sotto_titolo` varchar(200) NOT NULL,
  `alias` varchar(200) NOT NULL,
  `immagine` varchar(200) NOT NULL,
  `testo_introduttivo` text NOT NULL,
  `descrizione` text NOT NULL,
  `attivo` char(1) NOT NULL DEFAULT 'Y',
  `data_news` date NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL,
  `keywords` varchar(400) NOT NULL,
  `meta_description` text NOT NULL,
  `documento` varchar(200) NOT NULL,
  `clean_immagine` varchar(200) NOT NULL,
  `clean_documento` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `news`
--

INSERT INTO `news` (`id_n`, `data_creazione`, `titolo`, `sotto_titolo`, `alias`, `immagine`, `testo_introduttivo`, `descrizione`, `attivo`, `data_news`, `id_order`, `keywords`, `meta_description`, `documento`, `clean_immagine`, `clean_documento`) VALUES
(87, '2016-12-25 00:11:05', 'Test', '', 'test', '75c6a95ab3cd5db2a4645840697d9aa2.jpg', '', '', 'Y', '2016-12-25', 2, '', '', '', 'Keep-Calm-Bal-Folk-rosso.jpg', ''),
(88, '2017-01-15 19:41:56', 'gggg', '', 'gggg', '', '', '', 'N', '2017-01-15', 1, '', '', '', '', ''),
(89, '2017-01-19 23:35:42', 'tttt', '', 'tttt', '', '', '', 'Y', '2017-01-19', 3, '', '', '', '', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `orders`
--

CREATE TABLE `orders` (
  `id_o` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cart_uid` char(32) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `cognome` varchar(200) NOT NULL,
  `ragione_sociale` varchar(200) NOT NULL,
  `p_iva` varchar(200) NOT NULL,
  `codice_fiscale` varchar(200) NOT NULL,
  `indirizzo` varchar(200) NOT NULL,
  `cap` varchar(200) NOT NULL,
  `provincia` varchar(200) NOT NULL,
  `citta` varchar(200) NOT NULL,
  `telefono` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `pagamento` varchar(200) NOT NULL,
  `accetto` varchar(200) NOT NULL,
  `descrizione_acquisto` text NOT NULL,
  `creation_time` int(10) UNSIGNED NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL,
  `tipo_cliente` varchar(200) NOT NULL,
  `stato` varchar(30) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `spedizione` decimal(10,2) NOT NULL,
  `iva` decimal(10,2) NOT NULL,
  `admin_token` char(32) NOT NULL,
  `txn_id` char(32) NOT NULL,
  `registrato` char(1) NOT NULL DEFAULT 'N',
  `id_user` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `prezzo_scontato` decimal(10,2) NOT NULL,
  `codice_promozione` char(32) NOT NULL,
  `nome_promozione` varchar(200) NOT NULL,
  `usata_promozione` char(1) NOT NULL DEFAULT 'N',
  `banca_token` char(32) NOT NULL,
  `peso` decimal(10,2) NOT NULL,
  `indirizzo_spedizione` varchar(200) NOT NULL,
  `cap_spedizione` char(10) NOT NULL,
  `provincia_spedizione` varchar(200) NOT NULL,
  `nazione_spedizione` varchar(200) NOT NULL,
  `citta_spedizione` varchar(200) NOT NULL,
  `telefono_spedizione` varchar(200) NOT NULL,
  `aggiungi_nuovo_indirizzo` enum('Y','N') NOT NULL DEFAULT 'N',
  `id_spedizione` int(10) UNSIGNED NOT NULL,
  `id_corriere` int(10) UNSIGNED NOT NULL,
  `nazione` char(10) NOT NULL DEFAULT 'IT',
  `pec` varchar(200) NOT NULL,
  `codice_destinatario` varchar(200) NOT NULL,
  `data_pagamento` varchar(255) DEFAULT NULL,
  `promo` decimal(10,2) DEFAULT NULL,
  `dprovincia` varchar(255) NOT NULL,
  `dprovincia_spedizione` varchar(255) NOT NULL,
  `lingua` char(2) NOT NULL DEFAULT 'it',
  `id_iva` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `pages`
--

CREATE TABLE `pages` (
  `id_page` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `attivo` char(1) NOT NULL,
  `title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `alias` varchar(100) CHARACTER SET utf8 NOT NULL,
  `id_p` int(10) UNSIGNED NOT NULL,
  `id_c` int(10) UNSIGNED NOT NULL,
  `lft` int(10) UNSIGNED NOT NULL,
  `rgt` int(10) UNSIGNED NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL,
  `price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `codice` varchar(100) CHARACTER SET utf8 NOT NULL,
  `in_evidenza` char(1) NOT NULL DEFAULT 'N',
  `in_promozione` char(1) NOT NULL DEFAULT 'N',
  `prezzo_promozione` decimal(10,2) NOT NULL,
  `dal` date NOT NULL,
  `al` date NOT NULL,
  `peso` decimal(10,2) NOT NULL DEFAULT '1.00',
  `codice_alfa` varchar(32) CHARACTER SET utf8 NOT NULL,
  `principale` enum('Y','N') NOT NULL DEFAULT 'Y',
  `keywords` varchar(400) CHARACTER SET utf8 NOT NULL,
  `meta_description` text CHARACTER SET utf8 NOT NULL,
  `add_in_sitemap` enum('Y','N') NOT NULL DEFAULT 'Y',
  `gruppi` varchar(300) CHARACTER SET utf8 NOT NULL,
  `immagine` varchar(300) CHARACTER SET utf8 NOT NULL,
  `template` varchar(100) CHARACTER SET utf8 NOT NULL,
  `use_editor` enum('Y','N') NOT NULL DEFAULT 'Y',
  `data_news` date DEFAULT NULL,
  `data_masterspeed` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `data_transition` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `id_iva` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `id_marchio` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `sottotitolo` varchar(255) CHARACTER SET utf8 NOT NULL,
  `descrizione_breve` text CHARACTER SET utf8 NOT NULL,
  `css` text CHARACTER SET utf8 NOT NULL,
  `immagine_2` varchar(255) CHARACTER SET utf8 NOT NULL,
  `url` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `link_id_page` int(10) UNSIGNED NOT NULL,
  `link_id_c` int(10) UNSIGNED NOT NULL,
  `video` text CHARACTER SET utf8 NOT NULL,
  `codice_nazione` char(2) NOT NULL DEFAULT '',
  `coordinate` varchar(100) CHARACTER SET utf8 NOT NULL,
  `video_thumb` varchar(255) NOT NULL DEFAULT '',
  `id_tag` int(11) NOT NULL DEFAULT '0',
  `acquistabile` enum('Y','N') NOT NULL DEFAULT 'Y',
  `aggiungi_sempre_come_accessorio` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `pages`
--

INSERT INTO `pages` (`id_page`, `data_creazione`, `attivo`, `title`, `description`, `alias`, `id_p`, `id_c`, `lft`, `rgt`, `id_order`, `price`, `codice`, `in_evidenza`, `in_promozione`, `prezzo_promozione`, `dal`, `al`, `peso`, `codice_alfa`, `principale`, `keywords`, `meta_description`, `add_in_sitemap`, `gruppi`, `immagine`, `template`, `use_editor`, `data_news`, `data_masterspeed`, `data_transition`, `id_iva`, `id_marchio`, `sottotitolo`, `descrizione_breve`, `css`, `immagine_2`, `url`, `link_id_page`, `link_id_c`, `video`, `codice_nazione`, `coordinate`, `video_thumb`, `id_tag`, `acquistabile`, `aggiungi_sempre_come_accessorio`) VALUES
(186, '2018-02-26 09:10:11', 'Y', 'AAAA', '', 'aaaa-5388', 0, 85, 0, 0, 176, '0.0000', '', 'N', 'N', '0.00', '0000-00-00', '0000-00-00', '1.00', 'e8cf43b894aa044363e87b33aee4f4a4', 'Y', '', '', 'Y', '--free--', 'dashboard_3.png', '', 'Y', '0000-00-00', '1000', 'fade', 1, 0, '111 2222', '', '', '', 'aaa', 204, 0, '', '', '', '', 0, 'Y', 'N'),
(204, '2018-02-26 10:35:11', 'Y', 'Chi siamo', '&lt;p&gt;&lt;strong&gt;Father&amp;amp;Son&lt;/strong&gt; fonda le radici in oltre vent&rsquo;anni di storia a contatto con il mondo dell&rsquo;illuminazione e del design, prima come terzisti di marchi primari, poi come produttori di apparecchi propri. Questa esperienza costituisce un substrato essenziale nei nostri processi, dall&rsquo;ideazione&nbsp;alla produzione. Conoscenza dei materiali, attenzione ai dettagli, scelta del design, sono frutto dell&rsquo;interazione con i nostri fornitori, designer e rivenditori. Il valore aggiunto di Father&amp;amp;Son &egrave; la capacit&agrave; di realizzare prodotti di serie dall&rsquo;elevato valore qualitativo e la possibilit&agrave; di personalizzare i prodotti rendendoli soluzioni su misura.&lt;/p&gt;', 'chi-siamo', 0, 1, 0, 0, 46, '0.0000', '', 'N', 'N', '0.00', '0000-00-00', '0000-00-00', '1.00', '16973cabc8a692117a1cc384151ec927', 'Y', 'sementi, fertilizzanti, prodotti giardinaggio', 'E-commerce, vendita online di sementi, fertilizzanti, prodotti per il giardinaggio professionali e hobbistica, bordure in corten.', 'Y', '--free--', '', 'chi-siamo', 'Y', '0000-00-00', '', '', 1, 0, '', '', '.elementor-13 .elementor-element.elementor-element-67b8099&gt;.elementor-container {\r\n    max-width: 1160px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-67b8099 {\r\n	margin-top: 0px;\r\n	margin-bottom: 80px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-b19034e {\r\n	text-align: left;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-b19034e.elementor-widget-heading .elementor-heading-title {\r\n	color: #222222;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-744f424 {\r\n	color: #222222;\r\n	font-size: 15px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-dba616d&gt;.elementor-container {\r\n	max-width: 1160px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-b225e54&gt;.elementor-container {\r\n	max-width: 1160px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-b225e54 {\r\n	margin-top: 100px;\r\n	margin-bottom: 100px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-0312e00 {\r\n	text-align: center;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-0312e00.elementor-widget-heading .elementor-heading-title {\r\n	color: #222222;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-0312e00 .elementor-heading-title {\r\n	font-size: 48px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-0312e00&gt;.elementor-widget-container {\r\n	padding: 0px 0px 10px 0px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-982ceac.elementor-position-right .elementor-image-box-img {\r\n	margin-left: 15px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-982ceac.elementor-position-left .elementor-image-box-img {\r\n	margin-right: 15px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-982ceac.elementor-position-top .elementor-image-box-img {\r\n	margin-bottom: 15px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-982ceac .elementor-image-box-wrapper .elementor-image-box-img {\r\n	width: 80px;\r\n	height: 80px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-982ceac .elementor-image-box-wrapper .elementor-image-box-img img {\r\n	opacity: 1;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-982ceac .elementor-image-box-wrapper .elementor-image-box-img svg {\r\n	opacity: 1;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-982ceac:hover .elementor-image-box-wrapper .elementor-image-box-img img {\r\n	opacity: 1;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-982ceac:hover .elementor-image-box-wrapper .elementor-image-box-img svg {\r\n	opacity: 1;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-982ceac .elementor-image-box-img svg {\r\n	width: 60px;\r\n	height: 60px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-982ceac .elementor-image-box-content .elementor-image-box-title {\r\n	color: #222222;\r\n	font-size: 18px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-982ceac .elementor-image-box-content .elementor-image-box-description {\r\n	font-size: 14px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-a587c71.elementor-position-right .elementor-image-box-img {\r\n	margin-left: 15px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-a587c71.elementor-position-left .elementor-image-box-img {\r\n	margin-right: 15px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-a587c71.elementor-position-top .elementor-image-box-img {\r\n	margin-bottom: 15px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-a587c71 .elementor-image-box-wrapper .elementor-image-box-img {\r\n	width: 80px;\r\n	height: 80px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-a587c71 .elementor-image-box-wrapper .elementor-image-box-img img {\r\n	opacity: 1;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-a587c71 .elementor-image-box-wrapper .elementor-image-box-img svg {\r\n	opacity: 1;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-a587c71:hover .elementor-image-box-wrapper .elementor-image-box-img img {\r\n	opacity: 1;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-a587c71:hover .elementor-image-box-wrapper .elementor-image-box-img svg {\r\n	opacity: 1;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-a587c71 .elementor-image-box-img svg {\r\n	width: 60px;\r\n	height: 60px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-a587c71 .elementor-image-box-content .elementor-image-box-title {\r\n	color: #222222;\r\n	font-size: 18px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-a587c71 .elementor-image-box-content .elementor-image-box-description {\r\n	font-size: 14px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-3896a37.elementor-position-right .elementor-image-box-img {\r\n	margin-left: 15px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-3896a37.elementor-position-left .elementor-image-box-img {\r\n	margin-right: 15px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-3896a37.elementor-position-top .elementor-image-box-img {\r\n	margin-bottom: 15px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-3896a37 .elementor-image-box-wrapper .elementor-image-box-img {\r\n	width: 80px;\r\n	height: 80px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-3896a37 .elementor-image-box-wrapper .elementor-image-box-img img {\r\n	opacity: 1;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-3896a37 .elementor-image-box-wrapper .elementor-image-box-img svg {\r\n	opacity: 1;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-3896a37:hover .elementor-image-box-wrapper .elementor-image-box-img img {\r\n	opacity: 1;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-3896a37:hover .elementor-image-box-wrapper .elementor-image-box-img svg {\r\n	opacity: 1;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-3896a37 .elementor-image-box-img svg {\r\n	width: 60px;\r\n	height: 60px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-3896a37 .elementor-image-box-content .elementor-image-box-title {\r\n	color: #222222;\r\n	font-size: 18px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-3896a37 .elementor-image-box-content .elementor-image-box-description {\r\n	font-size: 14px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-f54374d:not(.elementor-motion-effects-element-type-background),\r\n.elementor-13 .elementor-element.elementor-element-f54374d&gt;.elementor-motion-effects-container&gt;.elementor-motion-effects-layer {\r\n	background-image: url(&quot;http://wordpress/wp-content/uploads/2018/10/bannerau-3.jpg&quot;);\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-f54374d {\r\n	transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-f54374d&gt;.elementor-background-overlay {\r\n	transition: background 0.3s, border-radius 0.3s, opacity 0.3s;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-8cdd211 .elementor-video-wrapper {\r\n	text-align: center;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-8cdd211 .opal-video-popup .elementor-video-icon {\r\n	font-size: 32px;\r\n	width: 60px;\r\n	height: 60px;\r\n	color: #c9c9c9;\r\n	background-color: #ffffff;\r\n	border-radius: 50% 50% 50% 50%;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-8cdd211&gt;.elementor-widget-container {\r\n	padding: 300px 0px 300px 0px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-a7b734f&gt;.elementor-container {\r\n	max-width: 1170px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-a7b734f {\r\n	margin-top: 40px;\r\n	margin-bottom: 100px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-3d64777&gt;.elementor-column-wrap&gt;.elementor-widget-wrap&gt;.elementor-widget:not(.elementor-widget__width-auto):not(.elementor-widget__width-initial):not(:last-child):not(.elementor-absolute) {\r\n	margin-bottom: 0px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-a833e9e {\r\n	text-align: center;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-a833e9e&gt;.elementor-widget-container {\r\n	margin: 0px 20px 0px 20px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-f553c0e&gt;.elementor-column-wrap&gt;.elementor-widget-wrap&gt;.elementor-widget:not(.elementor-widget__width-auto):not(.elementor-widget__width-initial):not(:last-child):not(.elementor-absolute) {\r\n	margin-bottom: 0px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-1f0bb9a {\r\n	text-align: center;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-1f0bb9a&gt;.elementor-widget-container {\r\n	margin: 0px 20px 0px 20px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-a875130&gt;.elementor-column-wrap&gt;.elementor-widget-wrap&gt;.elementor-widget:not(.elementor-widget__width-auto):not(.elementor-widget__width-initial):not(:last-child):not(.elementor-absolute) {\r\n	margin-bottom: 0px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-d3c750a {\r\n	text-align: center;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-d3c750a&gt;.elementor-widget-container {\r\n	margin: 0px 20px 0px 20px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-b24fd14&gt;.elementor-column-wrap&gt;.elementor-widget-wrap&gt;.elementor-widget:not(.elementor-widget__width-auto):not(.elementor-widget__width-initial):not(:last-child):not(.elementor-absolute) {\r\n	margin-bottom: 0px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-b531052 {\r\n	text-align: center;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-b531052&gt;.elementor-widget-container {\r\n	margin: 0px 20px 0px 20px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-096273c&gt;.elementor-column-wrap&gt;.elementor-widget-wrap&gt;.elementor-widget:not(.elementor-widget__width-auto):not(.elementor-widget__width-initial):not(:last-child):not(.elementor-absolute) {\r\n	margin-bottom: 0px;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-4bfa63e {\r\n	text-align: center;\r\n}\r\n\r\n.elementor-13 .elementor-element.elementor-element-4bfa63e&gt;.elementor-widget-container {\r\n	margin: 0px 20px 0px 20px;\r\n}\r\n\r\n@media(max-width:767px) {\r\n	.elementor-13 .elementor-element.elementor-element-b19034e .elementor-heading-title {\r\n		font-size: 26px;\r\n	}\r\n	.elementor-13 .elementor-element.elementor-element-0312e00 .elementor-heading-title {\r\n		font-size: 26px;\r\n	}\r\n	.elementor-13 .elementor-element.elementor-element-982ceac .elementor-image-box-img {\r\n		margin-bottom: 15px;\r\n	}\r\n	.elementor-13 .elementor-element.elementor-element-a587c71 .elementor-image-box-img {\r\n		margin-bottom: 15px;\r\n	}\r\n	.elementor-13 .elementor-element.elementor-element-3896a37 .elementor-image-box-img {\r\n		margin-bottom: 15px;\r\n	}\r\n}', '', '', 0, 0, '', '', '', '', 0, 'Y', 'N'),
(205, '2018-02-26 10:35:20', 'Y', 'Contattaci', 'sdfsdsf', 'contattaci', 0, 1, 0, 0, 45, '0.0000', '', 'N', 'N', '0.00', '0000-00-00', '0000-00-00', '1.00', '335a527e0b8c290e0e634d8858c7ceb6', 'Y', '', '', 'Y', '--free--', 'screenshot_20180717_104635.png', 'contattaci', 'Y', '0000-00-00', '', '', 1, 0, '', '', '', '', '', 0, 0, '', '', '', '', 0, 'Y', 'N'),
(206, '2018-02-26 10:35:26', 'Y', 'Cookies', '&lt;p&gt;Utilizzo di cookies&lt;/p&gt;\r\n&lt;p&gt;Il presente sito internet impiega i cosiddetti &ldquo;cookies&rdquo;, ovvero piccoli file testuali che il server di un sito memorizza temporaneamente o in via definitiva sul browser, sul vostro computer o su un altro dispositivo, finalizzati alla semplificazione, integrazione o personalizzazione dell&rsquo;utilizzo delle pagine web. Accendendo al presente sito internet, vi dichiarate d&rsquo;accordo al trattamento dei vostri dati personali da parte di Google. Avete la possibilit&agrave; di disattivare il salvataggio dei cookies attraverso il vostro browser o di cancellare i cookies gi&agrave; memorizzati, tenendo presente che ci&ograve; potrebbe comportare una limitazione delle funzionalit&agrave;, un rallentamento o l&rsquo;inutilizzabilit&agrave; di alcune parti del sito internet. Per ulteriori informazioni sui cookies e sulle modalit&agrave; di cancellazione, in base al tipo di browser impiegato, www.giardineggiando.it vi rimanda al seguente link:&lt;/p&gt;\r\n&lt;p&gt;support.google.com/accounts/answer/32050&lt;/p&gt;\r\n&lt;p&gt;www.giardineggiando.it impiega i seguenti cookies.&lt;/p&gt;\r\n&lt;p&gt;1. Cookies assolutamente necessari, grazie a cui il visitatore ha la possibilit&agrave; di visualizzare la pagina web, utilizzare le funzioni del sito o ottenere l&rsquo;accesso ad aree di sicurezza o registrate. In caso di disattivazione di tali cookies, determinate parti del sito non potranno pi&ugrave; essere impiegate correttamente.&lt;br /&gt;2. Cookies funzionali, con cui www.giardineggiando.it pu&ograve; memorizzare le scelte e le preferenze degli utenti, per incrementare la facilit&agrave; d&rsquo;uso del sito.&lt;br /&gt;3. Cookies per l&rsquo;analisi dell&rsquo;impiego, che raccolgono informazioni sull&rsquo;accesso al presente sito internet (compreso l&rsquo;indirizzo IP). Tali informazioni non hanno carattere personale e sono trasmesse a un server di Google negli USA, dove vengono memorizzate. Google utilizzer&agrave; tali dati per la valutazione dell&rsquo;utilizzo del sito internet, la stesura di report sulle attivit&agrave; del sito a beneficio dei gestori e per altri servizi collegati agli accessi alla pagina e all&rsquo;impiego d&rsquo;internet in generale. Google trasmetter&agrave; tali informazioni a terzi, qualora ci&ograve; sia previsto dalla legge ovvero se tali soggetti si occuperanno del trattamento dei dati per conto di Google. In nessun caso, l&rsquo;indirizzo IP sar&agrave; messo in collegamento con altri dati di Google.&lt;/p&gt;', 'cookies', 0, 1, 0, 0, 47, '0.0000', '', 'N', 'N', '0.00', '0000-00-00', '0000-00-00', '1.00', 'ff2c08dc16735136359692e7ca0d4346', 'Y', 'cookies, file testuali', 'Il presente sito internet impiega i cosiddetti &ldquo;cookies&rdquo;, ovvero piccoli file testuali che il server di un sito memorizza temporaneamente....', 'Y', '--free--', '', '', 'Y', '0000-00-00', '', '', 1, 0, '', '', '', '', '', 0, 0, '', '', '', '', 0, 'Y', 'N'),
(207, '2018-02-26 10:35:43', 'Y', 'Condizioni Generali Di Vendita', 'Condizioni di vendita', 'condizioni-generali-di-vendita', 0, 1, 0, 0, 48, '0.0000', '', 'N', 'N', '0.00', '0000-00-00', '0000-00-00', '1.00', '70e822f7bab4905c73c6aa3c8d8a700c', 'Y', 'condizioni generali di vendita', 'Condizioni generali di vendita: I beni oggetto delle presenti condizioni generali sono posti in vendita da Lian snc con sede in Via Arrigoni 53/d, Peraga...', 'Y', '--free--', '', '', 'Y', '0000-00-00', '', '', 1, 0, '', '', '', '', '', 0, 0, '', '', '', '', 0, 'Y', 'N'),
(208, '2018-02-26 10:37:32', 'Y', 'LA NOSTRA ESPERIENZA,  LA TUA PASSIONE', '&lt;div class=&quot;main clearfix &quot;&gt;\r\n    &lt;div class=&quot;content fullwidth&quot;&gt;\r\n    &lt;/div&gt;\r\n    &lt;div id=&quot;aq-block-9551-9&quot; class=&quot;aq-block aq-block-aq_quote_title_block aq_span12 aq-first cf&quot;&gt;\r\n        &lt;div class=&quot;infotextwrap&quot;&gt;\r\n\r\n            &lt;div class=&quot;infotext&quot;&gt;\r\n                &lt;div class=&quot;infotext-before&quot;&gt;&lt;/div&gt;\r\n                &lt;div class=&quot;infotext-title&quot;&gt;\r\n                    &lt;h2 style=&quot;color:#fff&quot;&gt;LA NOSTRA ESPERIENZA, &lt;br&gt;LA TUA PASSIONE&lt;/h2&gt;\r\n                    &lt;div class=&quot;infotext-title-small&quot; style=&quot;color:#fff&quot;&gt;\r\n                        &lt;p&gt;&lt;br&gt;&lt;br&gt;SEMENTI E FERTILIZZANTI DEI MAGGIORI PRODUTTORI INTERNAZIONALI.&lt;br&gt;ARREDOGIARDINO IN ACCIAIO COR-TEN PRODOTTO ARTIGIANALMENTE IN ITALIA, ANCHE SU MISURA!&lt;br&gt;ATTREZZATURE PER IL GIARDINAGGIO PROFESSIONALE DEI MAGGIORI PRODUTTORI\r\n                            AL MONDO.&lt;/p&gt;\r\n                    &lt;/div&gt;\r\n                &lt;/div&gt;\r\n                &lt;div class=&quot;infotext-after&quot;&gt;&lt;/div&gt;\r\n            &lt;/div&gt;\r\n        &lt;/div&gt;\r\n    &lt;/div&gt;\r\n    &lt;div id=&quot;aq-block-9551-10&quot; class=&quot;aq-block aq-block-aq_richtext_block aq_span12 aq-first cf&quot;&gt;&lt;br&gt;&lt;img style=&quot;margin-right: auto; margin-left: auto; display: block;&quot; src=&quot;http://www.giardineggiando.it/wp-content/uploads/2013/12/eco1.png&quot; alt=&quot;&quot; width=&quot;129&quot; height=&quot;150&quot;&gt;\r\n        &lt;div style=&quot;text-align: center;&quot;&gt;&lt;span style=&quot;color: #ffffff;&quot;&gt;&lt;br&gt;ISCRIVITI ALLA NOSTRA NEWSLETTER,&lt;br&gt; TI AGGIORNEREMO SULLE ULTIME NOVITA&#039; E SULLE NOSTRE PROMOZIONI&lt;/span&gt;&lt;/div&gt;\r\n        &lt;h4 style=&quot;text-align: center;&quot;&gt;&lt;span style=&quot;color: #000000; background-color: #ffffff;&quot;&gt;&lt;strong&gt;&lt;a href=&quot;http://www.giardineggiando.it/it/newsletter&quot;&gt;ISCRIVITI ALLA NEWSLETTER&lt;/a&gt;&lt;br&gt;&lt;/strong&gt;&lt;/span&gt;&lt;/h4&gt;\r\n    &lt;/div&gt;\r\n    &lt;div id=&quot;aq-block-9551-11&quot; class=&quot;aq-block aq-block-aq_end_content_block aq_span12 aq-first cf&quot;&gt; &lt;/div&gt;\r\n&lt;/div&gt;', 'la-nostra-esperienza-la-tua-passione', 0, 86, 0, 0, 50, '0.0000', '', 'N', 'N', '0.00', '0000-00-00', '0000-00-00', '1.00', 'f551c607886f15cedb08bef1102514a0', 'Y', '', '', 'Y', '--free--', 'featured-items-background-1.jpg', '', 'N', '0000-00-00', '', '', 1, 0, '', '', '', '', '', 0, 0, '', '', '', '', 0, 'Y', 'N'),
(209, '2018-02-26 10:53:02', 'Y', 'GIARDINEGGIANDO, AL FIANCO DEL PROFESSIONISTA', '        &lt;div class=&quot;main clearfix &quot;&gt;\r\n            &lt;div class=&quot;content fullwidth&quot;&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;aq-block aq-block-aq_title_border_block aq_span12 aq-first cf&quot;&gt;\r\n                &lt;div class=&quot;border-block&quot;&gt;\r\n                    &lt;div class=&quot;title-block-wrap&quot;&gt;\r\n                        &lt;div class=&quot;titletext margintitle&quot;&gt; &lt;/div&gt;\r\n                    &lt;/div&gt;\r\n                    &lt;div id=&quot;aq-block-9551-12&quot; class=&quot;aq-block aq-block-pmc_prebuild_start_title_small aq_span12 aq-first cf&quot;&gt;\r\n                        &lt;div id=&quot;aq-block-9551-13&quot; class=&quot;aq-block aq-block-aq_quote_title_block aq_span12 aq-first cf&quot;&gt;\r\n                            &lt;div class=&quot;infotextwrap&quot;&gt;\r\n                    			&lt;div class=&quot;infotext&quot;&gt;\r\n                    				&lt;div class=&quot;infotext-before&quot;&gt;&lt;/div&gt;\r\n                    				&lt;div class=&quot;infotext-title&quot;&gt;\r\n                    					&lt;h2 style=&quot;color:#fff&quot;&gt;[testo home_2_top]&lt;/h2&gt;\r\n                    					&lt;div class=&quot;infotext-title-small&quot; style=&quot;color:#fff&quot;&gt;\r\n                    						&lt;p&gt;[testo home_2_middle]&lt;/p&gt;\r\n                    					&lt;/div&gt;\r\n                    				&lt;/div&gt;\r\n                    				&lt;div class=&quot;infotext-after&quot;&gt;&lt;/div&gt;\r\n                    			&lt;/div&gt;\r\n                    		&lt;/div&gt;\r\n                    	&lt;/div&gt;\r\n                    	&lt;div id=&quot;aq-block-9551-14&quot; class=&quot;aq-block aq-block-aq_clear_block aq_span12 aq-first cf&quot;&gt;\r\n                    		&lt;div class=&quot;cf&quot; style=&quot;height:30px; background:&quot;&gt;&lt;/div&gt;\r\n                    	&lt;/div&gt;\r\n                    	&lt;div id=&quot;aq-block-9551-15&quot; class=&quot;aq-block aq-block-aq_logo_block aq_span12 aq-first cf&quot;&gt;\r\n                    		&lt;div class=&quot;logo-center&quot;&gt;\r\n                    			&lt;a href=&quot;[baseUrl]&quot;&gt;[testo home_2_img]&lt;/a&gt;\r\n                    		&lt;/div&gt;\r\n                    	&lt;/div&gt;\r\n                    	&lt;div id=&quot;aq-block-9551-16&quot; class=&quot;aq-block aq-block-aq_richtext_block aq_span12 aq-first cf&quot;&gt;&lt;br&gt;\r\n                    		&lt;div class=&quot;contact-opus-button&quot; style=&quot;text-align: center;&quot;&gt;&lt;a title=&quot;Contattaci per il tuo prodotto su misura&quot; href=&quot;[baseUrl]/crea-account/&quot;&gt;REGISTRATI ORA&lt;/a&gt;&lt;/div&gt;\r\n                    	&lt;/div&gt;\r\n                    &lt;/div&gt;\r\n                    &lt;div class=&quot;aq-block aq-block-aq_end_content_block aq_span12 aq-first cf&quot;&gt;\r\n                    &lt;/div&gt;\r\n                &lt;/div&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;aq-block aq-block-aq_end_content_block aq_span12 aq-first cf&quot;&gt;\r\n            &lt;/div&gt;\r\n        &lt;/div&gt;', 'giardineggiando-al-fianco-del-professionista', 0, 86, 0, 0, 49, '0.0000', '', 'N', 'N', '0.00', '0000-00-00', '0000-00-00', '1.00', '0354c7299cedecc624f3dd1959cd4f57', 'Y', '', '', 'Y', '--free--', 'foto-nera.jpg', '', 'N', '0000-00-00', '', '', 1, 0, '', '', '', '', '', 0, 0, '', '', '', '', 0, 'Y', 'N'),
(210, '2018-02-26 11:16:56', 'Y', 'Newsletter', '', 'newsletter', 0, 1, 0, 0, 51, '0.0000', '', 'N', 'N', '0.00', '0000-00-00', '0000-00-00', '1.00', '20c9b79aec49aed08ad55491e609b76f', 'Y', 'newsletter', '', 'Y', '--free--', '', 'newsletter', 'Y', '0000-00-00', '', '', 1, 0, '', '', 'input[type=&quot;color&quot;],\r\ninput[type=&quot;date&quot;],\r\ninput[type=&quot;datetime-local&quot;],\r\ninput[type=&quot;datetime&quot;],\r\ninput[type=&quot;email&quot;],\r\ninput[type=&quot;month&quot;],\r\ninput[type=&quot;number&quot;],\r\ninput[type=&quot;password&quot;],\r\ninput[type=&quot;range&quot;],\r\ninput[type=&quot;search&quot;],\r\ninput[type=&quot;tel&quot;],\r\ninput[type=&quot;text&quot;],\r\ninput[type=&quot;time&quot;],\r\ninput[type=&quot;url&quot;],\r\ninput[type=&quot;week&quot;],\r\ntextarea {\r\n  font-size: 1rem;\r\n  border-bottom: 1px solid #222 !important;\r\n  display: block;\r\n  width: 100%;\r\n  padding: 0.8rem 1.25rem;\r\n  background-color: #f6f6f6;\r\n  border-radius: 0;\r\n  padding-left:0px;\r\n}\r\n\r\n.wpcf7-form label {\r\n    font-size: 16px;\r\n    font-weight: 600;\r\n}\r\n\r\n.wpcf7-form select\r\n{\r\n    width:100%;\r\n}', '', '', 0, 0, '', '', '', '', 0, 'Y', 'N'),
(227, '2018-02-27 11:40:47', 'Y', 'Grazie', 'Grazie per averci contattato, vi risponderemo il prima possibile.&lt;br /&gt;&lt;br /&gt;&lt;br /&gt;', 'grazie', 0, 1, 0, 0, 62, '0.0000', '', 'N', 'N', '0.00', '0000-00-00', '0000-00-00', '1.00', 'f190698f8102e8d2a3f62603dcccaf7d', 'Y', 'grazie', 'Grazie per averci contattato, vi risponderemo il prima possibile', 'Y', '--free--', '', '', 'Y', '0000-00-00', '', '', 1, 0, '', '', '', '', '', 0, 0, '', '', '', '', 0, 'Y', 'N'),
(365, '2019-10-15 08:41:42', 'Y', 'Slide 2', '', 'slide-2', 0, 85, 0, 0, 175, '0.0000', '', 'N', 'N', '0.00', '0000-00-00', '0000-00-00', '1.00', '8cb0bd7bdb737880861e8888b4be82fa', 'Y', '', '', 'Y', '--free--', '', '', 'Y', '0000-00-00', '1000', 'fade', 1, 0, '', '', '', '', '', 0, 0, '', '', '', '', 0, 'Y', 'N'),
(367, '2019-10-15 08:51:53', 'Y', 'Slide 3', '', 'slide-3', 0, 85, 0, 0, 66, '0.0000', '', 'N', 'N', '0.00', '0000-00-00', '0000-00-00', '1.00', 'df827b39038f4747fd6723f8fa745d5e', 'Y', '', '', 'Y', '--free--', 'foto-slide-stilo_1.jpg', '', 'Y', '0000-00-00', '1000', 'fade', 1, 0, '', '', '', '', '', 0, 0, '', '', '', '', 0, 'Y', 'N'),
(370, '2019-10-26 11:37:13', 'Y', 'Lighting 2018', '', 'lighting-2018', 0, 103, 0, 0, 178, '0.0000', '', 'N', 'N', '0.00', '0000-00-00', '0000-00-00', '1.00', 'c77b22a18e86b96ec110c5945a43effa', 'Y', '', '', 'Y', '--free--', 'home1_banner1.jpg', '', 'Y', '0000-00-00', '', '', 0, 0, '', '', '', 'round-02_1.jpg', 'http://tige/it/blog.html', 0, 0, '', '', '', '', 0, 'Y', 'N'),
(374, '2019-10-28 10:21:41', 'Y', '(Copia di) Post 1', 'test', 'post-1-8761', 0, 87, 0, 0, 180, '0.0000', '', 'N', 'N', '0.00', '0000-00-00', '0000-00-00', '1.00', '40913e72a74ff0ecd3a112ed003a9bf8', 'Y', '', '', 'Y', '--free--', 'img1_1_1.jpg', '', 'Y', '2019-10-26', '', '', 0, 0, '', '', '', '', '', 0, 0, '', '', '', '', 0, 'Y', 'N'),
(375, '2019-10-28 10:21:42', 'Y', '888', '888', '888', 0, 87, 0, 0, 181, '0.0000', '', 'N', 'N', '0.00', '0000-00-00', '0000-00-00', '1.00', 'df65b35b5b30751ec537c9ecd1350a16', 'Y', '123', '', 'Y', '--free--', 'img1_1_1.jpg', '', 'Y', '2019-10-27', '', '', 0, 0, '', '', '', '', '', 0, 0, 'aaaa', '', '', 'img1.jpg', 0, 'Y', 'N'),
(383, '2020-07-11 10:07:19', 'Y', 'yyyy', '', 'yyyy', 0, 109, 0, 0, 184, '0.0000', '', 'N', 'N', '0.00', '0000-00-00', '0000-00-00', '1.00', 'd6e9e0d66df34cc72ac522c1283b2bdc', 'Y', '', '', 'Y', '--free--', 'img1_7.jpg', '', 'Y', '2020-07-11', '', '', 0, 0, '', '', '', '', '', 0, 0, '', 'AF', '1234,5678', '', 0, 'Y', 'N'),
(385, '2020-07-11 10:10:15', 'Y', '(Copia di) yyyy AAA', '', 'yyyy-7819', 0, 109, 0, 0, 185, '0.0000', '', 'N', 'N', '0.00', '0000-00-00', '0000-00-00', '1.00', 'a97ec14b5de493f8722a904918f46c69', 'Y', '', '', 'Y', '--free--', 'img1_7.jpg', '', 'Y', '2020-07-11', '', '', 0, 0, '', '', '', '', '', 0, 0, '', '0', '', '', 0, 'Y', 'N'),
(387, '2020-07-30 11:34:39', 'Y', 'TEST', '', 'test', 0, 110, 0, 0, 187, '0.0000', '', 'N', 'N', '0.00', '0000-00-00', '0000-00-00', '1.00', '6e405390630c738361bcf02115c8af48', 'Y', '', '', 'Y', '--free--', 'img9.jpg', '', 'Y', '0000-00-00', '', '', 0, 0, '', '', '', '', '', 0, 0, '', '', '', '', 0, 'Y', 'N'),
(390, '2020-08-01 09:44:02', 'Y', 'Privacy', '', 'privacy', 0, 1, 0, 0, 190, '0.0000', '', 'N', 'N', '0.00', '0000-00-00', '0000-00-00', '1.00', 'e0f1da2995191abe2b2cbb59acc6cf84', 'Y', '', '', 'Y', '--free--', '', '', 'Y', NULL, '', '', 0, 0, '', '', '', '', '', 0, 0, '', '', '', '', 0, 'Y', 'N');

-- --------------------------------------------------------

--
-- Struttura della tabella `pages_attributi`
--

CREATE TABLE `pages_attributi` (
  `id_pa` int(10) UNSIGNED NOT NULL,
  `id_page` int(11) UNSIGNED NOT NULL,
  `id_a` int(11) UNSIGNED NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL,
  `colonna` char(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `pages_caratteristiche_valori`
--

CREATE TABLE `pages_caratteristiche_valori` (
  `id_pcv` int(10) UNSIGNED NOT NULL,
  `id_page` int(11) UNSIGNED NOT NULL,
  `id_cv` int(11) UNSIGNED NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `pages_link`
--

CREATE TABLE `pages_link` (
  `id_page_link` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_page` int(11) UNSIGNED NOT NULL,
  `titolo` varchar(255) NOT NULL DEFAULT '',
  `url_link` varchar(255) NOT NULL DEFAULT '',
  `id_order` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `pages_personalizzazioni`
--

CREATE TABLE `pages_personalizzazioni` (
  `id_pp` int(10) UNSIGNED NOT NULL,
  `id_page` int(11) UNSIGNED NOT NULL,
  `id_pers` int(11) UNSIGNED NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `pages_tag`
--

CREATE TABLE `pages_tag` (
  `id_pt` int(10) UNSIGNED NOT NULL,
  `id_page` int(11) UNSIGNED NOT NULL,
  `id_tag` int(11) UNSIGNED NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `personalizzazioni`
--

CREATE TABLE `personalizzazioni` (
  `id_pers` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `titolo` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `tipo` char(30) NOT NULL DEFAULT 'TESTO',
  `numero_caratteri` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `personalizzazioni`
--

INSERT INTO `personalizzazioni` (`id_pers`, `data_creazione`, `titolo`, `tipo`, `numero_caratteri`) VALUES
(3, '2020-08-29 10:12:29', 'Testo incisione', 'TESTO', 12),
(4, '2020-08-29 10:35:08', 'Pers 2', 'TESTO', 5);

-- --------------------------------------------------------

--
-- Struttura della tabella `prodotti_correlati`
--

CREATE TABLE `prodotti_correlati` (
  `id_pc` int(10) UNSIGNED NOT NULL,
  `id_page` int(11) UNSIGNED NOT NULL,
  `id_corr` int(11) UNSIGNED NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL,
  `accessorio` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `prodotti_correlati`
--

INSERT INTO `prodotti_correlati` (`id_pc`, `id_page`, `id_corr`, `id_order`, `accessorio`) VALUES
(8, 374, 375, 8, 0),
(12, 375, 374, 11, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `promozioni`
--

CREATE TABLE `promozioni` (
  `id_p` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `attivo` char(1) NOT NULL DEFAULT 'Y',
  `dal` date NOT NULL,
  `al` date NOT NULL,
  `sconto` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `titolo` varchar(200) NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL,
  `codice` char(32) NOT NULL,
  `numero_utilizzi` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `promozioni`
--

INSERT INTO `promozioni` (`id_p`, `data_creazione`, `attivo`, `dal`, `al`, `sconto`, `titolo`, `id_order`, `codice`, `numero_utilizzi`) VALUES
(7, '2020-03-21 15:02:32', 'Y', '2020-03-21', '2020-04-07', 10, 'Promozione XX', 2, 'ANTONIO', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `promozioni_categorie`
--

CREATE TABLE `promozioni_categorie` (
  `id_pc` int(10) UNSIGNED NOT NULL,
  `id_p` int(11) UNSIGNED NOT NULL,
  `id_c` int(11) UNSIGNED NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `promozioni_categorie`
--

INSERT INTO `promozioni_categorie` (`id_pc`, `id_p`, `id_c`, `id_order`) VALUES
(2, 7, 99, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `promozioni_pages`
--

CREATE TABLE `promozioni_pages` (
  `id_pp` int(10) UNSIGNED NOT NULL,
  `id_p` int(11) UNSIGNED NOT NULL,
  `id_page` int(11) UNSIGNED NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `promozioni_pages`
--

INSERT INTO `promozioni_pages` (`id_pp`, `id_p`, `id_page`, `id_order`) VALUES
(7, 7, 349, 1),
(8, 7, 355, 2),
(9, 7, 358, 3),
(10, 7, 363, 4);

-- --------------------------------------------------------

--
-- Struttura della tabella `province`
--

CREATE TABLE `province` (
  `id_prov` int(10) UNSIGNED NOT NULL,
  `regione_clean` varchar(200) NOT NULL,
  `regione` varchar(200) NOT NULL,
  `provincia` varchar(200) NOT NULL,
  `codice_provincia` char(2) NOT NULL,
  `visibile_spedizione` char(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `province`
--

INSERT INTO `province` (`id_prov`, `regione_clean`, `regione`, `provincia`, `codice_provincia`, `visibile_spedizione`) VALUES
(441, 'piemonte', 'Piemonte', 'Torino', 'TO', '1'),
(442, 'piemonte', 'Piemonte', 'Vercelli', 'VC', '1'),
(443, 'piemonte', 'Piemonte', 'Novara', 'NO', '1'),
(444, 'piemonte', 'Piemonte', 'Cuneo', 'CN', '1'),
(445, 'piemonte', 'Piemonte', 'Asti', 'AT', '1'),
(446, 'piemonte', 'Piemonte', 'Alessandria', 'AL', '1'),
(447, 'piemonte', 'Piemonte', 'Biella', 'BI', '1'),
(448, 'piemonte', 'Piemonte', 'Verbano-Cusio-Ossola', 'VB', '1'),
(449, 'valle-d-aosta-vall-e-d-aoste', 'Valle d&#039;Aosta/Vall&eacute;e d&#039;Aoste', 'Valle d&#039;Aosta/Vall&eacute;e d&#039;Aoste', 'AO', '1'),
(450, 'lombardia', 'Lombardia', 'Varese', 'VA', '1'),
(451, 'lombardia', 'Lombardia', 'Como', 'CO', '1'),
(452, 'lombardia', 'Lombardia', 'Sondrio', 'SO', '1'),
(453, 'lombardia', 'Lombardia', 'Milano', 'MI', '1'),
(454, 'lombardia', 'Lombardia', 'Bergamo', 'BG', '1'),
(455, 'lombardia', 'Lombardia', 'Brescia', 'BS', '1'),
(456, 'lombardia', 'Lombardia', 'Pavia', 'PV', '1'),
(457, 'lombardia', 'Lombardia', 'Cremona', 'CR', '1'),
(458, 'lombardia', 'Lombardia', 'Mantova', 'MN', '1'),
(459, 'lombardia', 'Lombardia', 'Lecco', 'LC', '1'),
(460, 'lombardia', 'Lombardia', 'Lodi', 'LO', '1'),
(461, 'lombardia', 'Lombardia', 'Monza e della Brianza', 'MB', '1'),
(462, 'trentino-alto-adige-s-dtirol', 'Trentino-Alto Adige/S&uuml;dtirol', 'Bolzano/Bozen', 'BZ', '1'),
(463, 'trentino-alto-adige-s-dtirol', 'Trentino-Alto Adige/S&uuml;dtirol', 'Trento', 'TN', '1'),
(464, 'veneto', 'Veneto', 'Verona', 'VR', '1'),
(465, 'veneto', 'Veneto', 'Vicenza', 'VI', '1'),
(466, 'veneto', 'Veneto', 'Belluno', 'BL', '1'),
(467, 'veneto', 'Veneto', 'Treviso', 'TV', '1'),
(468, 'veneto', 'Veneto', 'Venezia', 'VE', '1'),
(469, 'veneto', 'Veneto', 'Padova', 'PD', '1'),
(470, 'veneto', 'Veneto', 'Rovigo', 'RO', '1'),
(471, 'friuli-venezia-giulia', 'Friuli-Venezia Giulia', 'Udine', 'UD', '1'),
(472, 'friuli-venezia-giulia', 'Friuli-Venezia Giulia', 'Gorizia', 'GO', '1'),
(473, 'friuli-venezia-giulia', 'Friuli-Venezia Giulia', 'Trieste', 'TS', '1'),
(474, 'friuli-venezia-giulia', 'Friuli-Venezia Giulia', 'Pordenone', 'PN', '1'),
(475, 'liguria', 'Liguria', 'Imperia', 'IM', '1'),
(476, 'liguria', 'Liguria', 'Savona', 'SV', '1'),
(477, 'liguria', 'Liguria', 'Genova', 'GE', '1'),
(478, 'liguria', 'Liguria', 'La Spezia', 'SP', '1'),
(479, 'emilia-romagna', 'Emilia-Romagna', 'Piacenza', 'PC', '1'),
(480, 'emilia-romagna', 'Emilia-Romagna', 'Parma', 'PR', '1'),
(481, 'emilia-romagna', 'Emilia-Romagna', 'Reggio nell&#039;Emilia', 'RE', '1'),
(482, 'emilia-romagna', 'Emilia-Romagna', 'Modena', 'MO', '1'),
(483, 'emilia-romagna', 'Emilia-Romagna', 'Bologna', 'BO', '1'),
(484, 'emilia-romagna', 'Emilia-Romagna', 'Ferrara', 'FE', '1'),
(485, 'emilia-romagna', 'Emilia-Romagna', 'Ravenna', 'RA', '1'),
(486, 'emilia-romagna', 'Emilia-Romagna', 'Forl&igrave;-Cesena', 'FC', '1'),
(487, 'emilia-romagna', 'Emilia-Romagna', 'Rimini', 'RN', '1'),
(488, 'toscana', 'Toscana', 'Massa-Carrara', 'MS', '1'),
(489, 'toscana', 'Toscana', 'Lucca', 'LU', '1'),
(490, 'toscana', 'Toscana', 'Pistoia', 'PT', '1'),
(491, 'toscana', 'Toscana', 'Firenze', 'FI', '1'),
(492, 'toscana', 'Toscana', 'Livorno', 'LI', '1'),
(493, 'toscana', 'Toscana', 'Pisa', 'PI', '1'),
(494, 'toscana', 'Toscana', 'Arezzo', 'AR', '1'),
(495, 'toscana', 'Toscana', 'Siena', 'SI', '1'),
(496, 'toscana', 'Toscana', 'Grosseto', 'GR', '1'),
(497, 'toscana', 'Toscana', 'Prato', 'PO', '1'),
(498, 'umbria', 'Umbria', 'Perugia', 'PG', '1'),
(499, 'umbria', 'Umbria', 'Terni', 'TR', '1'),
(500, 'marche', 'Marche', 'Pesaro e Urbino', 'PU', '1'),
(501, 'marche', 'Marche', 'Ancona', 'AN', '1'),
(502, 'marche', 'Marche', 'Macerata', 'MC', '1'),
(503, 'marche', 'Marche', 'Ascoli Piceno', 'AP', '1'),
(504, 'marche', 'Marche', 'Fermo', 'FM', '1'),
(505, 'lazio', 'Lazio', 'Viterbo', 'VT', '1'),
(506, 'lazio', 'Lazio', 'Rieti', 'RI', '1'),
(507, 'lazio', 'Lazio', 'Roma', 'RM', '1'),
(508, 'lazio', 'Lazio', 'Latina', 'LT', '1'),
(509, 'lazio', 'Lazio', 'Frosinone', 'FR', '1'),
(510, 'abruzzo', 'Abruzzo', 'L&#039;Aquila', 'AQ', '1'),
(511, 'abruzzo', 'Abruzzo', 'Teramo', 'TE', '1'),
(512, 'abruzzo', 'Abruzzo', 'Pescara', 'PE', '1'),
(513, 'abruzzo', 'Abruzzo', 'Chieti', 'CH', '1'),
(514, 'molise', 'Molise', 'Campobasso', 'CB', '1'),
(515, 'molise', 'Molise', 'Isernia', 'IS', '1'),
(516, 'campania', 'Campania', 'Caserta', 'CE', '1'),
(517, 'campania', 'Campania', 'Benevento', 'BN', '1'),
(518, 'campania', 'Campania', 'Napoli', 'NA', '1'),
(519, 'campania', 'Campania', 'Avellino', 'AV', '1'),
(520, 'campania', 'Campania', 'Salerno', 'SA', '1'),
(521, 'puglia', 'Puglia', 'Foggia', 'FG', '1'),
(522, 'puglia', 'Puglia', 'Bari', 'BA', '1'),
(523, 'puglia', 'Puglia', 'Taranto', 'TA', '1'),
(524, 'puglia', 'Puglia', 'Brindisi', 'BR', '1'),
(525, 'puglia', 'Puglia', 'Lecce', 'LE', '1'),
(526, 'puglia', 'Puglia', 'Barletta-Andria-Trani', 'BT', '1'),
(527, 'basilicata', 'Basilicata', 'Potenza', 'PZ', '1'),
(528, 'basilicata', 'Basilicata', 'Matera', 'MT', '1'),
(529, 'calabria', 'Calabria', 'Cosenza', 'CS', '1'),
(530, 'calabria', 'Calabria', 'Catanzaro', 'CZ', '1'),
(531, 'calabria', 'Calabria', 'Reggio di Calabria', 'RC', '1'),
(532, 'calabria', 'Calabria', 'Crotone', 'KR', '1'),
(533, 'calabria', 'Calabria', 'Vibo Valentia', 'VV', '1'),
(534, 'sicilia', 'Sicilia', 'Trapani', 'TP', '1'),
(535, 'sicilia', 'Sicilia', 'Palermo', 'PA', '1'),
(536, 'sicilia', 'Sicilia', 'Messina', 'ME', '1'),
(537, 'sicilia', 'Sicilia', 'Agrigento', 'AG', '1'),
(538, 'sicilia', 'Sicilia', 'Caltanissetta', 'CL', '1'),
(539, 'sicilia', 'Sicilia', 'Enna', 'EN', '1'),
(540, 'sicilia', 'Sicilia', 'Catania', 'CT', '1'),
(541, 'sicilia', 'Sicilia', 'Ragusa', 'RG', '1'),
(542, 'sicilia', 'Sicilia', 'Siracusa', 'SR', '1'),
(543, 'sardegna', 'Sardegna', 'Sassari', 'SS', '1'),
(544, 'sardegna', 'Sardegna', 'Nuoro', 'NU', '1'),
(545, 'sardegna', 'Sardegna', 'Cagliari', 'CA', '1'),
(546, 'sardegna', 'Sardegna', 'Oristano', 'OR', '1'),
(547, 'sardegna', 'Sardegna', 'Olbia-Tempio', 'OT', '1'),
(548, 'sardegna', 'Sardegna', 'Ogliastra', 'OG', '1'),
(549, 'sardegna', 'Sardegna', 'Medio Campidano', 'VS', '1'),
(550, 'sardegna', 'Sardegna', 'Carbonia-Iglesias', 'CI', '1');

-- --------------------------------------------------------

--
-- Struttura della tabella `regaccesses`
--

CREATE TABLE `regaccesses` (
  `id` int(12) NOT NULL,
  `ip` char(20) NOT NULL,
  `data` char(10) NOT NULL,
  `ora` char(8) NOT NULL,
  `username` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `regaccesses`
--

INSERT INTO `regaccesses` (`id`, `ip`, `data`, `ora`, `username`) VALUES
(1, '127.0.0.1', '09-01-2014', '14:17', 'tonicucoz@lanxsatura.org'),
(2, '127.0.0.1', '09-01-2014', '14:27', 'tonicucoz@lanxsatura.org'),
(3, '127.0.0.1', '09-01-2014', '14:37', 'antoniog.web@gmail.com'),
(4, '127.0.0.1', '10-01-2014', '15:59', 'antoniog.web@gmail.com'),
(5, '127.0.0.1', '10-01-2014', '16:00', 'antoniog.web@gmail.com'),
(6, '127.0.0.1', '10-01-2014', '16:15', 'tonicucoz@lanxsatura.org'),
(7, '127.0.0.1', '10-01-2014', '16:16', 'tonicucoz@lanxsatura.org'),
(8, '127.0.0.1', '10-01-2014', '16:34', 'tonicucoz@lanxsatura.org'),
(9, '127.0.0.1', '10-01-2014', '16:44', 'antoniog.web@gmail.com'),
(10, '127.0.0.1', '10-01-2014', '16:52', 'antoniog.web@gmail.com'),
(11, '127.0.0.1', '10-01-2014', '23:30', 'antoniog.web@gmail.com'),
(12, '127.0.0.1', '11-01-2014', '12:10', 'tonicucoz@lanxsatura.org'),
(13, '127.0.0.1', '11-01-2014', '12:31', 'antoniog.web@gmail.com'),
(14, '127.0.0.1', '11-01-2014', '12:32', 'antoniog.web@gmail.com'),
(15, '127.0.0.1', '11-01-2014', '12:38', 'antoniog.web@gmail.com'),
(16, '127.0.0.1', '11-01-2014', '12:40', 'antoniog.web@gmail.com'),
(17, '127.0.0.1', '11-01-2014', '12:41', 'antoniog.web@gmail.com'),
(18, '127.0.0.1', '11-01-2014', '12:41', 'antoniog.web@gmail.com'),
(19, '127.0.0.1', '11-01-2014', '12:42', 'antoniog.web@gmail.com'),
(20, '127.0.0.1', '11-01-2014', '12:42', 'antoniog.web@gmail.com'),
(21, '127.0.0.1', '11-01-2014', '12:48', 'antoniog.web@gmail.com'),
(22, '127.0.0.1', '11-01-2014', '12:49', 'antoniog.web@gmail.com'),
(23, '127.0.0.1', '11-01-2014', '18:04', 'antoniog.web@gmail.com'),
(24, '127.0.0.1', '11-01-2014', '18:06', 'antoniog.web@gmail.com'),
(25, '127.0.0.1', '11-01-2014', '18:25', 'antoniog.web@gmail.com'),
(26, '127.0.0.1', '11-01-2014', '18:33', 'antoniog.web@gmail.com'),
(27, '127.0.0.1', '11-01-2014', '18:51', 'tonicucoz@lanxsatura.org'),
(28, '127.0.0.1', '11-01-2014', '19:08', 'tonicucoz@lanxsatura.org'),
(29, '127.0.0.1', '12-01-2014', '14:41', 'antoniog.web@gmail.com'),
(30, '127.0.0.1', '12-01-2014', '15:07', 'antoniog.web@gmail.com'),
(31, '127.0.0.1', '12-01-2014', '15:12', 'antoniog.web@gmail.com'),
(32, '127.0.0.1', '12-01-2014', '15:20', 'antoniog.web@gmail.com'),
(33, '127.0.0.1', '12-01-2014', '16:34', 'info@laboratoriolibero.com'),
(34, '127.0.0.1', '12-01-2014', '16:35', 'info@laboratoriolibero.com'),
(35, '127.0.0.1', '12-01-2014', '16:37', 'tonicucoz@lanxsatura.org'),
(36, '127.0.0.1', '12-01-2014', '16:38', 'tonicucoz@lanxsatura.org'),
(37, '127.0.0.1', '12-01-2014', '16:45', 'tonicucoz@lanxsatura.org'),
(38, '127.0.0.1', '12-01-2014', '17:23', 'antonio888@outlook.it'),
(39, '127.0.0.1', '13-01-2014', '09:24', 'antoniog.web@gmail.com'),
(40, '127.0.0.1', '13-01-2014', '09:31', 'antoniog.web@gmail.com'),
(41, '127.0.0.1', '13-01-2014', '09:39', 'antoniog.web@gmail.com'),
(42, '127.0.0.1', '13-01-2014', '10:09', 'info@laboratoriolibero.com'),
(43, '127.0.0.1', '13-01-2014', '11:15', 'antoniog.web@gmail.com'),
(44, '127.0.0.1', '13-01-2014', '11:20', 'antoniog.web@gmail.com'),
(45, '127.0.0.1', '13-01-2014', '11:30', 'antoniog.web@gmail.com'),
(46, '127.0.0.1', '13-01-2014', '11:32', 'antoniog.web@gmail.com'),
(47, '127.0.0.1', '13-01-2014', '11:37', 'antoniog.web@gmail.com'),
(48, '127.0.0.1', '13-01-2014', '16:52', 'antoniog.web@gmail.com'),
(49, '127.0.0.1', '13-01-2014', '17:58', 'antoniog.web@gmail.com'),
(50, '127.0.0.1', '13-01-2014', '18:43', 'antoniog.web@gmail.com'),
(51, '127.0.0.1', '14-01-2014', '11:55', 'antoniog.web@gmail.com'),
(52, '127.0.0.1', '14-01-2014', '17:17', 'antoniog.web@gmail.com'),
(53, '127.0.0.1', '15-01-2014', '09:48', 'info@laboratoriolibero.com'),
(54, '127.0.0.1', '15-01-2014', '10:00', 'antoniog.web@gmail.com'),
(55, '127.0.0.1', '15-01-2014', '10:14', 'info@laboratoriolibero.com'),
(56, '127.0.0.1', '15-01-2014', '10:45', 'info@laboratoriolibero.com'),
(57, '127.0.0.1', '15-01-2014', '19:24', 'antoniog.web@gmail.com'),
(58, '127.0.0.1', '15-01-2014', '20:08', 'antonio888@outlook.it'),
(59, '127.0.0.1', '15-01-2014', '20:19', 'info@laboratoriolibero.com'),
(60, '127.0.0.1', '15-01-2014', '20:48', 'info@laboratoriolibero.com'),
(61, '127.0.0.1', '16-01-2014', '09:36', 'antonio888@outlook.it'),
(62, '127.0.0.1', '16-01-2014', '19:32', 'tonicucoz@lanxsatura.org'),
(63, '127.0.0.1', '16-01-2014', '19:33', 'tonicucoz@lanxsatura.org'),
(64, '127.0.0.1', '16-01-2014', '19:35', 'tonicucoz@lanxsatura.org'),
(65, '127.0.0.1', '16-01-2014', '21:04', 'tonicucoz@lanxsatura.org'),
(66, '127.0.0.1', '16-01-2014', '21:29', 'tonicucoz@lanxsatura.org'),
(67, '127.0.0.1', '16-01-2014', '21:30', 'tonicucoz@lanxsatura.org'),
(68, '127.0.0.1', '16-01-2014', '21:30', 'antonio888@outlook.it'),
(69, '127.0.0.1', '18-01-2014', '17:22', 'antonio888@outlook.it'),
(70, '127.0.0.1', '19-01-2014', '15:05', 'antonio888@outlook.it'),
(71, '127.0.0.1', '20-01-2014', '08:49', 'antonio888@outlook.it'),
(72, '127.0.0.1', '22-01-2014', '14:58', 'antonio888@outlook.it'),
(73, '127.0.0.1', '22-01-2014', '17:30', 'antoniog.web@gmail.com'),
(74, '127.0.0.1', '22-01-2014', '17:42', 'antoniog.web@gmail.com'),
(75, '127.0.0.1', '22-01-2014', '17:43', 'antoniog.web@gmail.com'),
(76, '127.0.0.1', '22-01-2014', '17:45', 'antoniog.web@gmail.com'),
(77, '127.0.0.1', '22-01-2014', '17:48', 'antoniog.web@gmail.com'),
(78, '127.0.0.1', '22-01-2014', '17:50', 'antoniog.web@gmail.com'),
(79, '127.0.0.1', '22-01-2014', '17:51', 'tonicucoz@lanxsatura.org'),
(80, '127.0.0.1', '22-01-2014', '18:07', 'antonio888@outlook.it'),
(81, '127.0.0.1', '23-01-2014', '11:24', 'antonio888@outlook.it'),
(82, '127.0.0.1', '27-01-2014', '08:14', 'antonio888@outlook.it'),
(83, '127.0.0.1', '27-01-2014', '08:14', 'antonio888@outlook.it'),
(84, '127.0.0.1', '27-01-2014', '08:17', 'antonio888@outlook.it'),
(85, '127.0.0.1', '27-01-2014', '08:17', 'antonio888@outlook.it'),
(86, '127.0.0.1', '03-02-2014', '09:14', 'antonio888@outlook.it'),
(87, '127.0.0.1', '05-02-2014', '08:52', 'antonio888@outlook.it'),
(88, '127.0.0.1', '06-02-2014', '09:48', 'antonio888@outlook.it'),
(89, '127.0.0.1', '14-02-2014', '15:13', 'antonio888@outlook.it'),
(90, '127.0.0.1', '15-02-2014', '16:00', 'antonio888@outlook.it'),
(91, '127.0.0.1', '17-02-2014', '00:32', 'antonio888@outlook.it'),
(92, '127.0.0.1', '19-02-2014', '16:47', 'antonio888@outlook.it'),
(93, '127.0.0.1', '19-02-2014', '17:42', 'antonio888@outlook.it'),
(94, '127.0.0.1', '20-02-2014', '07:35', 'antonio888@outlook.it'),
(95, '127.0.0.1', '26-02-2014', '21:47', 'antonio888@outlook.it'),
(96, '127.0.0.1', '26-02-2014', '22:00', 'antonio888@outlook.it'),
(97, '127.0.0.1', '26-02-2014', '22:00', 'antonio888@outlook.it'),
(98, '127.0.0.1', '26-02-2014', '22:01', 'antoniog.web@gmail.com'),
(99, '127.0.0.1', '26-02-2014', '22:28', 'antonio888@outlook.it'),
(100, '127.0.0.1', '27-02-2014', '11:33', 'antoniog.web@gmail.com'),
(101, '127.0.0.1', '27-03-2014', '22:22', 'info@laboratoriolibero.com'),
(102, '127.0.0.1', '27-03-2014', '22:25', 'antoniog.web@gmail.com'),
(103, '127.0.0.1', '28-03-2014', '10:12', 'antonio888@outlook.it'),
(104, '127.0.0.1', '02-04-2014', '09:27', 'antonio888@outlook.it'),
(105, '127.0.0.1', '07-04-2014', '12:46', 'antoniog.web@gmail.com'),
(106, '127.0.0.1', '07-04-2014', '13:00', 'antoniog.web@gmail.com'),
(107, '127.0.0.1', '07-04-2014', '13:04', 'antoniog.web@gmail.com'),
(108, '127.0.0.1', '07-04-2014', '13:08', 'antoniog.web@gmail.com'),
(109, '127.0.0.1', '07-04-2014', '14:08', 'antoniog.web@gmail.com'),
(110, '127.0.0.1', '07-04-2014', '14:11', 'antoniog.web@gmail.com'),
(111, '127.0.0.1', '07-04-2014', '14:14', 'antoniog.web@gmail.com'),
(112, '127.0.0.1', '01-05-2014', '22:20', 'antoniog.web@gmail.com'),
(113, '127.0.0.1', '18-06-2014', '12:22', 'antonio888@outlook.it'),
(114, '127.0.0.1', '03-09-2014', '21:06', 'antoniog.web@gmail.com'),
(115, '127.0.0.1', '04-09-2014', '12:19', 'antoniog.web@gmail.com'),
(116, '127.0.0.1', '04-09-2014', '12:41', 'antoniog.web@gmail.com'),
(117, '127.0.0.1', '04-09-2014', '13:30', 'antoniog.web@gmail.com'),
(118, '127.0.0.1', '04-09-2014', '14:45', 'antoniog.web@gmail.com'),
(119, '127.0.0.1', '04-09-2014', '14:59', 'antoniog.web@gmail.com'),
(120, '127.0.0.1', '04-09-2014', '15:05', 'antoniog.web@gmail.com'),
(121, '127.0.0.1', '11-12-2014', '08:03', 'antoniog.web@gmail.com'),
(122, '127.0.0.1', '21-12-2014', '13:04', 'antoniog.web@gmail.com'),
(123, '127.0.0.1', '21-12-2014', '13:05', 'info@laboratoriolibero.com'),
(124, '127.0.0.1', '20-06-2015', '09:08', 'antoniog.web@gmail.com'),
(125, '127.0.0.1', '12-07-2015', '12:13', 'antoniog.web@gmail.com'),
(126, '127.0.0.1', '10-11-2015', '12:04', 'antoniog.web@gmail.com'),
(127, '127.0.0.1', '15-01-2016', '11:46', 'antoniog.web@gmail.com'),
(128, '127.0.0.1', '31-03-2016', '21:19', 'antoniog.web@gmail.com'),
(129, '127.0.0.1', '31-03-2016', '21:19', 'antoniog.web@gmail.com'),
(130, '127.0.0.1', '31-03-2016', '21:22', 'antoniog.web@gmail.com'),
(131, '127.0.0.1', '31-03-2016', '21:25', 'antoniog.web@gmail.com'),
(132, '127.0.0.1', '06-07-2016', '17:21', 'antoniog.web@gmail.com'),
(133, '127.0.0.1', '24-02-2018', '12:38', 'antoniog.web@gmail.com'),
(134, '127.0.0.1', '24-02-2018', '12:56', 'antoniog.web@gmail.com'),
(135, '127.0.0.1', '24-02-2018', '13:29', 'antoniog.web@gmail.com'),
(136, '127.0.0.1', '24-02-2018', '14:24', 'info@laboratoriolibero.com'),
(137, '127.0.0.1', '24-02-2018', '14:32', 'antoniog.web@gmail.com'),
(138, '127.0.0.1', '24-02-2018', '21:46', 'info@laboratoriolibero.com'),
(139, '127.0.0.1', '25-02-2018', '10:37', 'antoniog.web@gmail.com'),
(140, '127.0.0.1', '25-02-2018', '10:46', 'info@laboratoriolibero.com'),
(141, '127.0.0.1', '25-02-2018', '12:30', 'antoniog.web@gmail.com'),
(142, '127.0.0.1', '25-02-2018', '12:35', 'info@laboratoriolibero.com'),
(143, '127.0.0.1', '25-02-2018', '12:50', 'info@laboratoriolibero.com'),
(144, '127.0.0.1', '27-02-2018', '14:00', 'info@laboratoriolibero.com'),
(145, '127.0.0.1', '27-02-2018', '17:38', 'info@laboratoriolibero.com'),
(146, '', '06-03-2018', '20:37', 'angela.reggi@gmail.com'),
(147, '', '07-03-2018', '11:51', 'angela.reggi@gmail.com'),
(148, '127.0.0.1', '12-03-2018', '15:37', 'antoniog.web@gmail.com'),
(149, '127.0.0.1', '12-03-2018', '16:12', 'antoniog.web@gmail.com'),
(150, '127.0.0.1', '12-03-2018', '16:49', 'antoniog.web@gmail.com'),
(151, '127.0.0.1', '12-03-2018', '17:13', 'antoniog.web@gmail.com'),
(152, '127.0.0.1', '12-03-2018', '17:15', 'antoniog.web@gmail.com'),
(153, '127.0.0.1', '12-03-2018', '17:15', 'antoniog.web@gmail.com'),
(154, '127.0.0.1', '12-03-2018', '17:16', 'antoniog.web@gmail.com'),
(155, '127.0.0.1', '12-03-2018', '17:32', 'antoniog.web@gmail.com'),
(156, '127.0.0.1', '12-03-2018', '17:35', 'antoniog.web@gmail.com'),
(157, '127.0.0.1', '12-03-2018', '17:38', 'antoniog.web@gmail.com'),
(158, '127.0.0.1', '12-03-2018', '17:40', 'antoniog.web@gmail.com'),
(159, '151.36.7.215', '12-03-2018', '18:23', 'antoniog.web@gmail.com'),
(160, '151.18.28.132', '12-03-2018', '19:34', 'antoniog.web@gmail.com'),
(161, '151.18.137.229', '13-03-2018', '08:36', 'antoniog.web@gmail.com'),
(162, '78.7.21.22', '13-03-2018', '10:16', 'stfmichele@gmail.com'),
(163, '78.7.21.22', '13-03-2018', '10:17', 'stfmichele@gmail.com'),
(164, '151.18.71.22', '13-03-2018', '10:38', 'stfmichele@gmail.com'),
(165, '151.68.91.86', '13-03-2018', '14:26', 'antoniog.web@gmail.com'),
(166, '151.68.91.86', '13-03-2018', '14:56', 'antoniog.web@gmail.com'),
(167, '151.51.59.198', '13-03-2018', '16:39', 'stfmichele@gmail.com'),
(168, '151.51.59.198', '13-03-2018', '16:49', 'stfmichele@gmail.com'),
(169, '151.82.115.112', '13-03-2018', '17:27', 'antoniog.web@gmail.com'),
(170, '151.82.55.110', '17-03-2018', '11:30', 'antoniog.web@gmail.com'),
(171, '151.82.55.110', '17-03-2018', '11:56', 'antoniog.web@gmail.com'),
(172, '151.82.55.110', '17-03-2018', '12:05', 'antoniog.web@gmail.com'),
(173, '151.34.43.57', '17-03-2018', '18:17', 'antoniog.web@gmail.com'),
(174, '151.82.95.142', '19-03-2018', '11:48', 'antoniog.web@gmail.com'),
(175, '151.82.123.192', '19-03-2018', '11:58', 'antoniog.web@gmail.com'),
(176, '151.36.64.1', '20-03-2018', '13:27', 'antoniog.web@gmail.com'),
(177, '151.35.69.77', '24-03-2018', '09:28', 'antoniog.web@gmail.com'),
(178, '151.35.69.77', '24-03-2018', '10:00', 'antoniog.web@gmail.com'),
(179, '151.82.153.84', '25-03-2018', '00:35', 'antoniog.web@gmail.com'),
(180, '151.51.50.211', '04-04-2018', '16:43', 'laragmr73@gmail.com'),
(181, '151.18.32.190', '04-04-2018', '19:59', 'antoniog.web@gmail.com'),
(182, '176.200.75.55', '09-04-2018', '09:09', 'ispettore07@gmail.com'),
(183, '151.38.48.166', '10-04-2018', '15:25', 'antoniog.web@gmail.com'),
(184, '151.82.27.240', '16-04-2018', '14:43', 'antoniog.web@gmail.com'),
(185, '185.58.5.29', '22-04-2018', '10:02', 'kristiano@mac.com'),
(186, '151.95.116.164', '22-04-2018', '11:17', 'laragmr73@gmail.com'),
(187, '37.182.196.249', '23-04-2018', '13:37', 'brunabianciotto83@gmail.com'),
(188, '2.231.30.3', '23-04-2018', '14:51', 'kristiano@mac.com'),
(189, '37.182.196.249', '24-04-2018', '13:06', 'brunabianciotto83@gmail.com'),
(190, '185.58.5.29', '25-04-2018', '20:10', 'kristiano@mac.com'),
(191, '2.234.73.179', '26-04-2018', '15:38', 'diegozambotto@libero.it'),
(192, '2.234.73.179', '26-04-2018', '15:41', 'diegozambotto@libero.it'),
(193, '2.234.73.179', '26-04-2018', '16:49', 'diegozambotto@libero.it'),
(194, '151.38.123.103', '28-04-2018', '00:20', 'antoniog.web@gmail.com'),
(195, '82.50.113.103', '30-04-2018', '20:54', 'rfk@tiscali.it'),
(196, '80.104.38.76', '01-05-2018', '19:18', 'ucciodb@gmail.com'),
(197, '95.232.179.135', '02-05-2018', '18:01', 'rfk@tiscali.it'),
(198, '87.5.229.170', '03-05-2018', '18:22', 'rfk@tiscali.it'),
(199, '80.104.38.76', '03-05-2018', '20:00', 'ucciodb@gmail.com'),
(200, '80.104.38.76', '03-05-2018', '20:01', 'ucciodb@gmail.com'),
(201, '46.44.210.193', '04-05-2018', '15:05', 'leo.guidi@me.com'),
(202, '188.14.198.216', '08-05-2018', '08:58', 'mario.antinori69@gmail.com'),
(203, '185.58.5.29', '08-05-2018', '18:19', 'kristiano@mac.com'),
(204, '151.34.123.247', '09-05-2018', '15:34', 'giorgionardisalso57@gmail.com'),
(205, '93.145.246.7', '11-05-2018', '11:06', 'mario.antinori69@gmail.com'),
(206, '146.241.0.33', '11-05-2018', '14:22', 'edolamp94@yahoo.it'),
(207, '84.18.128.188', '15-05-2018', '07:05', 'otto@onlinestore.it'),
(208, '46.44.210.193', '15-05-2018', '10:48', 'leo.guidi@me.com'),
(209, '146.241.226.151', '16-05-2018', '10:47', 'faini.atelier@gmail.com'),
(210, '151.34.0.234', '26-05-2018', '16:23', 'antoniog.web@gmail.com'),
(211, '95.245.253.35', '27-05-2018', '20:29', 'marco.sansoni.1995@gmail.com'),
(212, '151.0.142.228', '28-05-2018', '03:58', 'natalia.cabella@libero.it'),
(213, '2.234.226.255', '30-05-2018', '22:03', 'marco.sansoni.1995@gmail.com'),
(214, '151.68.67.72', '05-06-2018', '11:24', 'antoniog.web@gmail.com'),
(215, '87.8.180.194', '05-06-2018', '21:37', 'bonventopaolo@gmail.com'),
(216, '87.8.180.194', '05-06-2018', '21:45', 'bonventopaolo@gmail.com'),
(217, '2.36.87.240', '10-06-2018', '22:27', 'renzosalvi0@gmail.com'),
(218, '37.227.94.68', '15-06-2018', '16:21', 'kristiano@mac.com'),
(219, '151.36.75.172', '15-06-2018', '17:59', 'laragmr73@gmail.com'),
(220, '79.22.157.254', '19-06-2018', '00:10', 'fatboyhd1994@hotmail.com'),
(221, '91.252.27.226', '21-06-2018', '11:09', 'fatboyhd1994@hotmail.com'),
(222, '82.58.215.149', '22-06-2018', '20:43', 'claudiot35@gmail.com'),
(223, '127.0.0.1', '23-06-2018', '11:00', 'antoniog.web@gmail.com'),
(224, '127.0.0.1', '06-08-2018', '17:34', 'antoniog.web@gmail.com'),
(225, '127.0.0.1', '15-08-2018', '13:42', 'antoniog.web@gmail.com'),
(226, '127.0.0.1', '15-08-2018', '13:45', 'antoniog.web@gmail.com'),
(227, '127.0.0.1', '03-09-2018', '08:17', 'antoniog.web@gmail.com'),
(228, '127.0.0.1', '03-09-2018', '08:17', 'antoniog.web@gmail.com'),
(229, '127.0.0.1', '03-09-2018', '08:17', 'antoniog.web@gmail.com'),
(230, '127.0.0.1', '03-09-2018', '08:45', 'antoniog.web@gmail.com'),
(231, '127.0.0.1', '18-09-2018', '11:39', 'antoniog.web@gmail.com'),
(232, '127.0.0.1', '18-09-2018', '11:46', 'antoniog.web@gmail.com'),
(233, '127.0.0.1', '25-02-2019', '09:40', 'antoniog.web@gmail.com'),
(234, '127.0.0.1', '25-02-2019', '10:30', 'antoniog.web@gmail.com'),
(235, '127.0.0.1', '25-02-2019', '10:45', 'info@laboratoriolibero.com'),
(236, '127.0.0.1', '25-02-2019', '10:48', 'info@laboratoriolibero.com'),
(237, '127.0.0.1', '25-02-2019', '10:53', 'info@laboratoriolibero.com'),
(238, '127.0.0.1', '02-03-2019', '10:30', 'antoniog.web@gmail.com'),
(239, '127.0.0.1', '02-03-2019', '11:49', 'antoniog.web@gmail.com'),
(240, '127.0.0.1', '02-03-2019', '12:48', 'antoniog.web@gmail.com'),
(241, '127.0.0.1', '04-03-2019', '08:46', 'tonicucoz@gmail.com'),
(242, '127.0.0.1', '04-03-2019', '08:51', 'tonicucoz@gmail.com'),
(243, '127.0.0.1', '04-03-2019', '11:55', 'tonicucoz@gmail.com'),
(244, '127.0.0.1', '09-03-2019', '10:55', 'antoniog.web@gmail.com'),
(245, '127.0.0.1', '09-03-2019', '20:07', 'antoniog.web@gmail.com'),
(246, '127.0.0.1', '11-03-2019', '08:14', 'antoniog.web@gmail.com'),
(247, '127.0.0.1', '11-03-2019', '11:09', 'antoniog.web@gmail.com'),
(248, '127.0.0.1', '19-03-2019', '10:13', 'antoniog.web@gmail.com'),
(249, '127.0.0.1', '24-03-2019', '11:14', 'antoniog.web@gmail.com'),
(250, '127.0.0.1', '24-03-2019', '11:17', 'antoniog.web@gmail.com'),
(251, '127.0.0.1', '17-09-2019', '09:33', 'antoniog.web@gmail.com'),
(252, '127.0.0.1', '30-09-2019', '11:18', 'antoniog.web@gmail.com'),
(253, '127.0.0.1', '30-09-2019', '11:25', 'antoniog.web@gmail.com'),
(254, '127.0.0.1', '30-09-2019', '11:26', 'antoniog.web@gmail.com'),
(255, '127.0.0.1', '30-09-2019', '16:08', 'antoniog.web@gmail.com'),
(256, '127.0.0.1', '30-09-2019', '16:12', 'antoniog.web@gmail.com'),
(257, '127.0.0.1', '30-09-2019', '16:34', 'antoniog.web@gmail.com'),
(258, '127.0.0.1', '30-09-2019', '18:03', 'antoniog.web@gmail.com'),
(259, '127.0.0.1', '05-10-2019', '09:31', 'antoniog.web@gmail.com'),
(260, '127.0.0.1', '05-10-2019', '11:11', 'antoniog.web@gmail.com'),
(261, '127.0.0.1', '07-10-2019', '12:32', 'antoniog.web@gmail.com'),
(262, '127.0.0.1', '07-10-2019', '15:34', 'antoniog.web@gmail.com'),
(263, '127.0.0.1', '07-10-2019', '16:42', 'antoniog.web@gmail.com'),
(264, '127.0.0.1', '07-10-2019', '17:05', 'antoniog.web@gmail.com'),
(265, '127.0.0.1', '14-10-2019', '18:11', 'antoniog.web@gmail.com'),
(266, '127.0.0.1', '21-10-2019', '12:58', 'antoniog.web@gmail.com'),
(267, '127.0.0.1', '28-10-2019', '13:22', 'antoniog.web@gmail.com'),
(268, '127.0.0.1', '28-10-2019', '15:04', 'antoniog.web@gmail.com'),
(269, '127.0.0.1', '09-11-2019', '11:21', 'antoniog.web@gmail.com'),
(270, '127.0.0.1', '09-11-2019', '11:45', 'tonicucoz@gmail.com'),
(271, '127.0.0.1', '09-11-2019', '11:48', 'tonicucoz@gmail.com'),
(272, '127.0.0.1', '09-11-2019', '12:09', 'tonicucoz@gmail.com'),
(273, '127.0.0.1', '18-11-2019', '15:51', 'tonicucoz@gmail.com'),
(274, '127.0.0.1', '18-11-2019', '15:56', 'tonicucoz@gmail.com'),
(275, '127.0.0.1', '18-11-2019', '16:00', 'tonicucoz@gmail.com'),
(276, '127.0.0.1', '02-12-2019', '16:43', 'aaa@aaa.it'),
(277, '127.0.0.1', '07-12-2019', '13:05', 'info@laboratoriolibero.com'),
(278, '127.0.0.1', '28-01-2020', '10:01', 'hhh@hhh.it'),
(279, '127.0.0.1', '28-01-2020', '10:07', 'info@laboratoriolibero.com'),
(280, '127.0.0.1', '30-01-2020', '11:50', 'info@laboratoriolibero.com'),
(281, '127.0.0.1', '18-05-2020', '22:06', 'info@laboratoriolibero.com'),
(282, '127.0.0.1', '06-07-2020', '15:24', 'info@laboratoriolibero.com'),
(283, '127.0.0.1', '06-07-2020', '15:27', 'info@laboratoriolibero.com'),
(284, '127.0.0.1', '27-07-2020', '12:07', 'info@laboratoriolibero.com'),
(285, '127.0.0.1', '29-07-2020', '08:28', 'info@laboratoriolibero.com'),
(286, '127.0.0.1', '01-08-2020', '13:06', 'info@laboratoriolibero.com'),
(287, '127.0.0.1', '01-08-2020', '13:09', 'info@laboratoriolibero.com'),
(288, '127.0.0.1', '01-08-2020', '13:23', 'info@laboratoriolibero.com'),
(289, '127.0.0.1', '12-08-2020', '15:40', 'a@a.it'),
(290, '127.0.0.1', '12-08-2020', '19:35', 'info@laboratoriolibero.com'),
(291, '127.0.0.1', '12-08-2020', '20:19', 'antoniog.web@gmail.com'),
(292, '127.0.0.1', '23-08-2020', '18:57', 'info@laboratoriolibero.com'),
(293, '127.0.0.1', '24-08-2020', '16:52', 'info@laboratoriolibero.com'),
(294, '127.0.0.1', '01-09-2020', '15:30', 'antoniog.web@gmail.com'),
(295, '127.0.0.1', '07-09-2020', '11:25', 'info@laboratoriolibero.com'),
(296, '127.0.0.1', '12-09-2020', '12:58', 'antoniog.web@gmail.com'),
(297, '127.0.0.1', '12-09-2020', '12:59', 'antoniog.web@gmail.com'),
(298, '127.0.0.1', '19-09-2020', '11:53', 'antoniog.web@gmail.com'),
(299, '127.0.0.1', '19-09-2020', '12:17', 'info@laboratoriolibero.com'),
(300, '127.0.0.1', '19-09-2020', '13:19', 'info@laboratoriolibero.com'),
(301, '127.0.0.1', '21-09-2020', '10:50', 'antoniog.web@gmail.com'),
(302, '127.0.0.1', '22-09-2020', '17:34', 'info@laboratoriolibero.com'),
(303, '127.0.0.1', '22-09-2020', '17:35', 'antoniog.web@gmail.com'),
(304, '127.0.0.1', '03-10-2020', '22:16', 'info@laboratoriolibero.com'),
(305, '127.0.0.1', '05-10-2020', '17:43', 'info@laboratoriolibero.com');

-- --------------------------------------------------------

--
-- Struttura della tabella `reggroups`
--

CREATE TABLE `reggroups` (
  `id_group` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `reggroups`
--

INSERT INTO `reggroups` (`id_group`, `name`) VALUES
(7, 'gruppo_1'),
(8, 'gruppo_2'),
(9, 'gruppo_3'),
(10, 'gruppo_4');

-- --------------------------------------------------------

--
-- Struttura della tabella `reggroups_categories`
--

CREATE TABLE `reggroups_categories` (
  `id_gc` int(10) UNSIGNED NOT NULL,
  `id_c` int(11) UNSIGNED NOT NULL,
  `id_group` int(11) UNSIGNED NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `regsessions`
--

CREATE TABLE `regsessions` (
  `uid` char(32) NOT NULL,
  `token` char(32) NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `creation_date` int(10) UNSIGNED NOT NULL,
  `user_agent` char(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `regusers`
--

CREATE TABLE `regusers` (
  `id_user` int(10) UNSIGNED NOT NULL,
  `username` varchar(80) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password` char(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `last_failure` int(10) UNSIGNED NOT NULL,
  `has_confirmed` int(10) UNSIGNED NOT NULL,
  `ha_confermato` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `confirmation_token` char(32) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creation_time` int(10) UNSIGNED NOT NULL,
  `temp_field` char(32) NOT NULL,
  `deleted` char(4) NOT NULL DEFAULT 'no',
  `forgot_token` char(32) NOT NULL,
  `forgot_time` int(10) UNSIGNED NOT NULL,
  `nome` varchar(200) NOT NULL,
  `cognome` varchar(200) NOT NULL,
  `ragione_sociale` varchar(200) NOT NULL,
  `p_iva` varchar(200) NOT NULL,
  `codice_fiscale` varchar(200) NOT NULL,
  `indirizzo` varchar(200) NOT NULL,
  `cap` varchar(200) NOT NULL,
  `provincia` varchar(200) NOT NULL,
  `citta` varchar(200) NOT NULL,
  `telefono` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `tipo_cliente` varchar(200) NOT NULL,
  `accetto` varchar(200) NOT NULL,
  `indirizzo_spedizione` text NOT NULL,
  `id_classe` int(10) UNSIGNED NOT NULL,
  `nazione` char(10) NOT NULL DEFAULT 'IT',
  `pec` varchar(200) NOT NULL,
  `codice_destinatario` varchar(200) NOT NULL,
  `dprovincia` varchar(255) NOT NULL,
  `id_ruolo` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `note` text NOT NULL,
  `codice` varchar(255) NOT NULL DEFAULT '',
  `id_tipo_azienda` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `lingua` char(2) NOT NULL DEFAULT 'it'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `regusers_groups`
--

CREATE TABLE `regusers_groups` (
  `id_ug` int(10) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  `id_group` int(11) UNSIGNED NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `righe`
--

CREATE TABLE `righe` (
  `id_r` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cart_uid` char(32) NOT NULL,
  `id_page` int(10) UNSIGNED NOT NULL,
  `id_c` int(10) UNSIGNED NOT NULL,
  `attributi` text CHARACTER SET utf8 NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `creation_time` int(10) UNSIGNED NOT NULL,
  `price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `in_promozione` char(1) NOT NULL DEFAULT 'N',
  `prezzo_intero` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `codice` varchar(100) CHARACTER SET utf8 NOT NULL,
  `title` varchar(300) CHARACTER SET utf8 NOT NULL,
  `immagine` varchar(100) CHARACTER SET utf8 NOT NULL,
  `peso` decimal(10,2) NOT NULL,
  `id_o` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `id_order` int(10) UNSIGNED NOT NULL,
  `json_sconti` text,
  `id_iva` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `iva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `id_p` int(10) UNSIGNED NOT NULL,
  `json_attributi` varchar(100) NOT NULL DEFAULT '[]',
  `json_personalizzazioni` varchar(100) NOT NULL DEFAULT '[]'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ruoli`
--

CREATE TABLE `ruoli` (
  `id_ruolo` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `titolo` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `id_order` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `scaglioni`
--

CREATE TABLE `scaglioni` (
  `id_scaglione` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_page` int(10) UNSIGNED NOT NULL,
  `quantita` int(10) UNSIGNED NOT NULL,
  `sconto` decimal(10,2) NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `slide_layer`
--

CREATE TABLE `slide_layer` (
  `id_layer` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `titolo` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `id_page` int(10) UNSIGNED NOT NULL,
  `testo` text CHARACTER SET utf8 NOT NULL,
  `immagine` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `larghezza_1` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `larghezza_2` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `larghezza_3` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `larghezza_4` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `x_1` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `x_2` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `x_3` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `x_4` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `y_1` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `y_2` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `y_3` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `y_4` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `animazione` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `id_order` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `url` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `slide_layer`
--

INSERT INTO `slide_layer` (`id_layer`, `data_creazione`, `titolo`, `id_page`, `testo`, `immagine`, `larghezza_1`, `larghezza_2`, `larghezza_3`, `larghezza_4`, `x_1`, `x_2`, `x_3`, `x_4`, `y_1`, `y_2`, `y_3`, `y_4`, `animazione`, `id_order`, `url`) VALUES
(1, '2019-10-15 07:45:43', 'foto-slide-petrarca', 186, '', 'foto-slide-petrarca.png', '400px', '230px', '250px', '180px', '551', '100', '400', '250', '247', '530', '680', '520', 'basso', 1, ''),
(2, '2019-10-15 07:52:11', 'foto-slide-tavolino', 186, '', 'foto-slide-tavolino.png', '300px', '300px', '300px', '250px', '831', '405', '94', '43', '473', '301', '238', '119', 'sinistra', 3, ''),
(3, '2019-10-15 07:53:02', 'foto-slide-casanova', 186, '', 'foto-slide-casanova.png', '500px', '500px', '325px', '225px', '1005', '560', '550', '350', '27', '251', '600', '470', 'alto', 4, ''),
(4, '2019-10-15 07:54:13', 'foto-slide-poltrona', 186, '', 'foto-slide-poltrona.png', '300px', '250px', '300px', '180px', '1199', '732', '370', '258', '473', '397', '291', '163', 'destra', 5, ''),
(5, '2019-10-15 07:58:30', 'Cerchio', 186, '', 'home1_layer6.png', '640px', '527px', '407px', '275px', '769', '477', '0', '0', '111', '102', '124', '84', 'centro', 2, ''),
(6, '2019-10-15 08:08:09', 'Tige', 186, 'Tige', '', '', '', '', '', '', '', '', '', '', '', '', '', 'testo', 0, 'http://tige/it/technical/prodotti.html'),
(7, '2019-10-15 07:45:43', 'foto-slide-petrarca', 365, '', 'foto-slide-tige-cappello-del-parroco.png', '800px', '800px', '800px', '800px', '851', '405', '94', '43', '-300', '-300', '-300', '-400', 'alto', 1, ''),
(9, '2019-10-15 07:53:02', 'foto-slide-casanova', 365, '', 'foto-slide-tige-mezza-11.png', '800px', '800px', '800px', '800px', '1200', '702', '392', '258', '-200', '-200', '-200', '-300', 'alto', 4, ''),
(11, '2019-10-15 07:58:30', 'Cerchio', 365, '', 'home1_layer6.png', '640px', '527px', '467px', '275px', '769', '477', '0', '0', '111', '102', '124', '54', 'centro', 2, ''),
(12, '2019-10-15 08:08:09', 'Tige', 365, 'Tige', '', '', '', '', '', '', '', '', '', '', '', '', '', 'testo', 0, 'http://tige/it/technical/prodotti.html'),
(16, '2019-10-15 08:08:09', 'Stilo', 367, 'Stilo', '', '', '', '', '', '', '', '', '', '', '', '', '', 'testo', 0, 'http://tige/it/technical/prodotti.html');

-- --------------------------------------------------------

--
-- Struttura della tabella `spedizioni`
--

CREATE TABLE `spedizioni` (
  `id_spedizione` int(10) UNSIGNED NOT NULL,
  `indirizzo_spedizione` varchar(200) CHARACTER SET utf8 NOT NULL,
  `cap_spedizione` char(10) CHARACTER SET utf8 NOT NULL,
  `provincia_spedizione` varchar(200) CHARACTER SET utf8 NOT NULL,
  `nazione_spedizione` varchar(200) CHARACTER SET utf8 NOT NULL,
  `citta_spedizione` varchar(200) CHARACTER SET utf8 NOT NULL,
  `telefono_spedizione` varchar(200) CHARACTER SET utf8 NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `ultimo_usato` enum('Y','N') NOT NULL DEFAULT 'N',
  `dprovincia_spedizione` varchar(255) CHARACTER SET utf8 NOT NULL,
  `da_usare` char(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `tag`
--

CREATE TABLE `tag` (
  `id_tag` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `titolo` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alias` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `id_order` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `attivo` enum('Y','N') NOT NULL DEFAULT 'Y'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `tag`
--

INSERT INTO `tag` (`id_tag`, `data_creazione`, `titolo`, `alias`, `id_order`, `attivo`) VALUES
(3, '2020-09-08 12:37:20', 'Natale 2020', 'natale-2020', 1, 'Y');

-- --------------------------------------------------------

--
-- Struttura della tabella `testi`
--

CREATE TABLE `testi` (
  `id_t` int(10) UNSIGNED NOT NULL,
  `chiave` varchar(200) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `valore` text NOT NULL,
  `lingua` char(2) NOT NULL DEFAULT 'it',
  `immagine` varchar(200) NOT NULL,
  `alt` varchar(200) NOT NULL,
  `width` char(10) NOT NULL DEFAULT '',
  `height` char(10) NOT NULL DEFAULT '',
  `crop` enum('Y','N') NOT NULL DEFAULT 'N',
  `tipo` char(20) NOT NULL DEFAULT 'TESTO',
  `testo_link` varchar(255) NOT NULL DEFAULT '',
  `url_link` varchar(255) NOT NULL DEFAULT '',
  `id_contenuto` int(11) NOT NULL DEFAULT '0',
  `target_link` char(20) NOT NULL DEFAULT 'INTERNO',
  `immagine_2x` varchar(255) NOT NULL DEFAULT '',
  `attributi` varchar(255) NOT NULL DEFAULT '',
  `id_categoria` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `testi`
--

INSERT INTO `testi` (`id_t`, `chiave`, `valore`, `lingua`, `immagine`, `alt`, `width`, `height`, `crop`, `tipo`, `testo_link`, `url_link`, `id_contenuto`, `target_link`, `immagine_2x`, `attributi`, `id_categoria`) VALUES
(4, '', '', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(5, 'prodotto', '&lt;div&gt;&lt;img style=&quot;display: block; margin-left: auto; margin-right: auto;&quot; src=&quot;http://easystart/images/generiche/21312_521660811224122_803846847_n.png&quot; alt=&quot;&quot; width=&quot;155&quot; height=&quot;155&quot; /&gt;&lt;/div&gt;', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(6, 'titolo_chi_siamo', '&lt;span&gt;GIARDINEGGIANDO SI RIVOLGE A CHI IL GIARDINAGGIO LO FA&nbsp;&lt;/span&gt;&lt;br /&gt;&lt;span&gt;PER PROFESSIONE O PER PASSIONE COME SE FOSSE UNA PROFESSIONE!&lt;/span&gt;', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(7, 'contenuto_sx_chi_siamo', 'Da anni lavoriamo al fianco dei manutentori del verde e grazie al lavoro duro e all&rsquo;impegno abbiamo conquistato la loro fiducia. Il nostro cliente si aspetta che i materiali forniti siano sempre di alta qualit&agrave; ma con un prezzo adeguato e questo riusciamo a garantirlo perch&eacute; abbiamo saputo costruire rapporti di fiducia e di mutua soddisfazione con i nostri fornitori, senza intermediari commerciali. &lt;strong&gt;Giardineggiando&lt;/strong&gt; &egrave; il tramite fra voi e il produttore ma in pi&ugrave;, grazie alla nostra esperienza, abbiamo scelto i prodotti migliori! Tuttavia il mondo del giardinaggio negli ultimi anni si &egrave; radicalmente trasformato, richiedendo flessibilit&agrave;, disponibilit&agrave; e tempi di risposta rapidi. Da qui nasce l&rsquo;idea di garantirvi una presenza ininterrotta, con una presenza online ventiquattrore su ventiquattro, una sorta di assistente sempre al vostro fianco e in grado di fornire una gamma selezionata di prodotti in tempi brevissimi. Siamo lieti di accogliervi nel nostro ecommerce!', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(8, 'immagine_1_chi_siamo', '', 'it', 'foto-chi-siamo-giardineggiando-e1513851649155.jpeg', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(9, 'chi_siamo_2', 'L&rsquo;IMPORTANZA DI UNA CORRETTA INFORMAZIONE', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(10, 'chi_siamo_3', '&lt;span&gt;Giardineggiando non si pone solo l&rsquo;obbiettivo di fornire materiali e attrezzature ma anche di informare attraverso post stagionali e una assistenza dedicata ai nostri clienti. Informazioni, chiare e semplici, che ognuno pu&ograve; apprezzare e valorizzare attraverso la propria esperienza e in base al contesto ambientale in cui lavora in modo da decidere quale &egrave; la cosa migliore da fare.&lt;/span&gt;', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(11, 'chi_img_1', '', 'it', '1-mano-con-semi-giardineggiando.jpg', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(12, 'chi_img_2', '', 'it', '2-concime-distribuzione-giardineggiando.jpg', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(13, 'chi_img_3', '', 'it', '3-corten-fioriera-giardineggiando.jpg', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(14, 'chi_img_4', '', 'it', '4-attrezzature-giardineggiando.jpg', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(15, 'I MIGLIORI SUGGERIMENTI DAL NOSTRO NEGOZIO', 'I MIGLIORI SUGGERIMENTI DAL NOSTRO NEGOZIO', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(16, 'home_1_top', 'home_1_top', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(17, 'home_1_middle', 'home_1_middle', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(18, 'home_1_bottom', 'home_1_bottom', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(19, 'home_2_top', 'GIARDINEGGIANDO, AL FIANCO DEL PROFESSIONISTA', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(20, 'home_2_middle', '&lt;span&gt;Registrandosi, manutentori professionisti, aziende agricole,&nbsp;&lt;/span&gt;&lt;br /&gt;&lt;span&gt;societ&agrave; sportive ed enti di gestione area verdi potranno beneficiare&lt;/span&gt;&lt;br /&gt;&lt;span&gt;di sconti aggiuntivi su tutta la linea sementi,&nbsp;&lt;/span&gt;&lt;br /&gt;&lt;span&gt;fertilizzanti e arredogiardino in acciaio COR-TEN.&lt;/span&gt;', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(21, 'home_2_img', '', 'it', 'logogiardineggiandobianco.png', 'Giardineggiando - i migliori prodotti per la cura del giardino e per l&#039;arredogiardino', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(22, 'home_1', 'LA NOSTRA ESPERIENZA, &lt;br /&gt;LA TUA PASSIONE', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(23, 'home_2', '&lt;br /&gt;&lt;br /&gt;SEMENTI E FERTILIZZANTI DEI MAGGIORI PRODUTTORI INTERNAZIONALI.&lt;br /&gt;ARREDOGIARDINO IN ACCIAIO COR-TEN PRODOTTO ARTIGIANALMENTE IN ITALIA, ANCHE SU MISURA!&lt;br /&gt;ATTREZZATURE PER IL GIARDINAGGIO PROFESSIONALE DEI MAGGIORI PRODUTTORI AL MONDO.', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(24, 'home_3', '&lt;br /&gt;ISCRIVITI ALLA NOSTRA NEWSLETTER,&lt;br /&gt; TI AGGIORNEREMO SULLE ULTIME NOVITA&#039; E SULLE NOSTRE PROMOZIONI', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(25, 'home_4', 'ISCRIVITI ALLA NEWSLETTER', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(26, 'indirizzo', 'Via Pioga, 74 35011&lt;br /&gt;Campodarsego (PD)', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(27, 'Desideri una lampada su misura?</BR>Oppure vorresti ricavarla da un oggetto? O ancora l’hai disegnata?</BR>Grazie alla nostra esperienza siamo in grado di crearla.</BR>Illustraci il tuo progetto, eseg', 'Desideri una lampada su misura?</BR>Oppure vorresti ricavarla da un oggetto? O ancora l’hai disegnata?</BR>Grazie alla nostra esperienza siamo in grado di crearla.</BR>Illustraci il tuo progetto, eseguiremo uno studio di fattibilità</BR>e una quotazione per te, senza impegno.', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(30, 'home_5', 'Registrandosi, manutentori professionisti, aziende agricole, &lt;br /&gt;societ&agrave; sportive ed enti di gestione aree verdi potranno beneficiare&lt;br /&gt;di sconti aggiuntivi su tutta la linea sementi, &lt;br /&gt;fertilizzanti e arredogiardino in acciaio COR-TEN.', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(31, 'Giardineggiando non si pone solo l’obbiettivo di fornire materiali e attrezzature ma anche di informare attraverso post stagionali e una assistenza dedicata ai nostri clienti. Informazioni, chiare e s', 'Giardineggiando non si pone solo l’obbiettivo di fornire materiali e attrezzature ma anche di informare attraverso post stagionali e una assistenza dedicata ai nostri clienti. Informazioni, chiare e semplici, che ognuno può apprezzare e valorizzare attraverso la propria esperienza e in base al contesto ambientale in cui lavora in modo da decidere quale è la cosa migliore da fare.', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(33, 'sottotitolo', 'Giardineggiando non si pone solo l&rsquo;obbiettivo di fornire materiali e attrezzature ma anche di informare attraverso post stagionali e una assistenza dedicata ai nostri clienti. Informazioni, chiare e semplici, che ognuno pu&ograve; apprezzare e valorizzare attraverso la propria esperienza e in base al contesto ambientale in cui lavora in modo da decidere quale &egrave; la cosa migliore da fare.', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(34, 'consulta', '&lt;div class=&quot;title-block-wrap&quot;&gt;\r\n&lt;h2 class=&quot;titleborderh2&quot; style=&quot;color: #e04028;&quot;&gt;&lt;span&gt;CONSULTA I NOSTRI CATALOGHI ON-LINE &lt;br /&gt;RELATIVI AI SEMENTI E AI FERTILIZZANTI, &lt;br /&gt;SE C&rsquo;&Egrave; QUALCOSA CHE TI INTERESSA &lt;br /&gt;E NON &Egrave; PRESENTE NELLO SHOP CONTATTACI, &lt;br /&gt;SAREMO LIETI DI FORNIRTI &lt;br /&gt;TUTTE LE INFORMAZIONI DI CUI HAI BISOGNO. &lt;/span&gt;&lt;/h2&gt;\r\n&lt;div class=&quot;titleborderOut&quot;&gt;&nbsp;&lt;/div&gt;\r\n&lt;div class=&quot;titletext margintitle&quot;&gt;&nbsp;&lt;/div&gt;\r\n&lt;/div&gt;', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(35, 'newsletter', 'Iscrivendoti alla nostra Newsletter potrete rimanere aggiornati su tutte le nostre novit&agrave; e sulle promozioni che periodicamente offriamo alla nostra chientela.', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(36, 'Nota', 'Nota: Le spese di spedizione sono solo stimate e saranno calcolate esattamente al momento dell&#039;acquisto, in base alle informazioni di fatturazione e di spedizione fornite.', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(37, 'img_1', '', 'it', '1-mano-con-semi-giardineggiando_1.jpg', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(38, 'img_2', '', 'it', '2-concime-distribuzione-giardineggiando_1.jpg', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(39, 'img_3', '', 'it', '3-corten-fioriera-giardineggiando_1.jpg', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(40, 'img_4', '', 'it', '4-attrezzature-giardineggiando_1.jpg', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(41, 'Completa registrazione', 'Completa registrazione', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(42, 'bonifico', 'Hai selezionato il pagamento tramite bonifico bancario e appena completerai l&rsquo;ordine ti invieremo una mail contenente le nostre coordinate bancarie.&lt;br /&gt; Ti preghiamo di riportare il numero dell&rsquo;ordine come causale. Il tuo ordine verr&agrave; processato non appena riceveremo conferma del trasferimento avvenuto.', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(43, 'contrassegno', 'Il pagamento avverr&agrave; alla consegna della merce.', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(44, 'paypal', 'paypal', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(45, 'Grazie per aver scelto i prodotti di Piccola Officina. Hai selezionato il pagamento tramite bonifico bancario e appena completerai l’ordine ti invieremo una mail contenente le nostre coordinate bancar', 'Grazie per aver scelto i prodotti di Piccola Officina. Hai selezionato il pagamento tramite bonifico bancario e appena completerai l’ordine ti invieremo una mail contenente le nostre coordinate bancarie.<br>\n								Ti preghiamo di riportare l’ID dell’ordine come causale. Il tuo ordine verrà processato non appena riceveremo conferma del trasferimento avvenuto.', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(48, 'Paga con Paypal. Se non disponi di un account Paypal, selenzionando questa opzione, potrai pagare in sicurezza anche con la sola carta di credito.', 'Paga con Paypal. Se non disponi di un account Paypal, selenzionando questa opzione, potrai pagare in sicurezza anche con la sola carta di credito.', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(49, 'Esegua il bonifico alle seguenti coordinate bancarie ...', '&lt;strong&gt;Esegua il bonifico alle seguenti coordinate bancarie:&lt;/strong&gt;&lt;br /&gt;Banco BPM&lt;br /&gt;Fil. Di Campodarsego (PD)&lt;br /&gt;IBAM: &lt;strong&gt;IT92T0503462420000000020185&lt;/strong&gt;&lt;br /&gt;Bic: BAPPIT21204', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(50, 'Esegua il pagamento al corriere alla consegna della merce.', 'Esegua il pagamento al corriere alla consegna della merce.', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(51, 'Esegua il bonifico alle seguenti coordinate bancarie ...', 'Esegua il bonifico alle seguenti coordinate bancarie ...', '', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(52, 'indirizzo', 'indirizzo', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(53, 'Nota', 'Nota', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(54, 'Paga con Paypal. Se non disponi di un account Paypal, selenzionando questa opzione, potrai pagare in sicurezza anche con la sola carta di credito.', 'Pay with Paypal. If you do not have a Paypal account, by selecting this option, you can pay safely even with a credit card only.', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(55, 'bonifico', 'bonifico', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(56, 'contrassegno', 'contrassegno', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(57, 'I MIGLIORI SUGGERIMENTI DAL NOSTRO NEGOZIO', 'I MIGLIORI SUGGERIMENTI DAL NOSTRO NEGOZIO', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(58, 'home_1', 'home_1', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(59, 'home_2', 'home_2', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(60, 'home_3', 'home_3', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(61, 'home_4', 'home_4', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(62, 'home_5', 'home_5', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(63, 'titolo_chi_siamo', 'aaaa', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(64, 'contenuto_sx_chi_siamo', 'dfgdfgdfgdfg', 'en', 'screenshot_20180702_104621_1.png', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(65, 'immagine_1_chi_siamo', 'immagine_1_chi_siamo', 'en', 'screenshot_20180702_104621.png', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(66, 'sottotitolo', 'sottotitolo', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(67, 'img_1', 'img_1', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(68, 'img_2', 'img_2', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(69, 'img_3', 'img_3', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(70, 'img_4', 'img_4', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(71, 'consulta', 'aaaa', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(72, 'Cookies', 'Cookies', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(73, 'Per cancellare l\'account è necessario inserire la password e confermare tramite il form sottostante.', 'Per cancellare l\'account è necessario inserire la password e confermare tramite il form sottostante.', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(74, 'Cookies', 'Cookies', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(75, 'Per cancellare l\'account è necessario inserire la password e confermare tramite il form sottostante.', 'To cancel the account you need to enter the password and confirm by means of the form below.', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(76, 'Indirizzo...', 'Scrivi l&#039;indirizzo qui', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(77, 'about_img_1', '', 'it', 'foto-about-01.jpg', 'aaaa', '560', '400', 'Y', 'IMMAGINE', '', '', 0, 'INTERNO', '', '', 0),
(78, 'about_img_2', '', 'it', 'foto-about-02.jpg', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(79, 'main_chi_siamo', '&lt;strong&gt;Father&amp;amp;Son&lt;/strong&gt; fonda le radici in oltre vent&rsquo;anni di storia a contatto con il mondo dell&rsquo;illuminazione e del design, prima come terzisti di marchi primari, poi come produttori di apparecchi propri. Questa esperienza costituisce un substrato essenziale nei nostri processi, dall&rsquo;ideazione alla produzione. Conoscenza dei materiali, attenzione ai dettagli, scelta del design, sono frutto dell&rsquo;interazione con i nostri fornitori, designer e rivenditori. Il valore aggiunto di Father&amp;amp;Son &egrave; la capacit&agrave; di realizzare prodotti di serie dall&rsquo;elevato valore qualitativo e la possibilit&agrave; di personalizzare i prodotti rendendoli soluzioni su misura.', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(80, 'Testo da modificare', '&lt;span&gt;Oltre vent&rsquo;anni di storia nel mondo dell&rsquo;illuminazione e del design prendono la forma di prodotti dall&#039;elevato valore qualitativo, con possibilit&agrave; di personalizzazione nei colori e attraverso soluzioni su misura.&lt;br /&gt;Sulla famiglia &lt;strong&gt;TIGE&lt;/strong&gt; e &lt;strong&gt;STILO&lt;/strong&gt; &egrave; possibile personalizzare la lunghezza fino a 3mt (contattateci per una quotazione gratuita).&lt;/span&gt;&lt;br /&gt;&lt;span&gt;Approfitta della comodit&agrave; di ordinare online, &lt;strong&gt;le spese di spedizione sono gratuite&lt;/strong&gt;.&lt;/span&gt;', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(81, 'about_img_3', '', 'it', 'foto-sede.jpg', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(82, 'testo_spedizione_shop', 'AAAA', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(83, 'Testo da modificare', 'Testo da modificare', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(84, 'testo_spedizione_shop', 'testo_spedizione_shop', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(85, 'testo_spedizione_shop', 'testo_spedizione_shop', 'fr', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(87, 'about_img_1', 'about_img_1', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(88, 'about_img_2', 'about_img_2', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(89, 'about_img_3', 'about_img_3', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(90, 'Testo da modificare', 'Testo da modificare', 'fr', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(91, 'Testo da modificare', '111', 'es', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(92, 'testo_spedizione_shop', 'testo_spedizione_shop', 'es', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(93, 'Nota', 'Nota', 'es', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(95, 'about_img_1', 'about_img_1', 'es', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(96, 'about_img_2', 'about_img_2', 'es', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(97, 'about_img_3', 'about_img_3', 'es', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(99, 'about_img_1', 'about_img_1', 'fr', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(100, 'about_img_2', 'about_img_2', 'fr', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(101, 'about_img_3', 'about_img_3', 'fr', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(112, 'link_1', '', 'it', '', '', '', '', 'N', 'LINK', 'AAAAAAAA', '', 204, 'INTERNO', '', '', 0),
(113, 'about_img_4', '', 'it', 'img1_5.jpg', '', '', '', 'N', 'IMMAGINE', '', '', 204, 'INTERNO', '', '', 0),
(114, 'about_img_6', '', 'it', 'img9.jpg', '', '300', '300', 'Y', 'IMMAGINE', '', '', 362, 'INTERNO', '', '', 0),
(121, 'main_chi_siamo', '&lt;strong&gt;Father&amp;amp;Son&lt;/strong&gt; fonda le &lt;strong&gt;radici&lt;/strong&gt; in oltre vent&rsquo;anni di storia a contatto con il mondo dell&rsquo;illuminazione e del design, prima come terzisti di marchi primari, poi come produttori di apparecchi propri. Questa esperienza costituisce un substrato essenziale nei nostri processi, dall&rsquo;ideazione alla produzione. Conoscenza dei materiali, attenzione ai dettagli, scelta del design, sono frutto dell&rsquo;interazione con i nostri fornitori, designer e rivenditori. Il valore aggiunto di Father&amp;amp;Son &egrave; la capacit&agrave; di realizzare prodotti di serie dall&rsquo;elevato valore qualitativo e la possibilit&agrave; di personalizzare i prodotti rendendoli soluzioni su misura.', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(122, 'link_1', '', 'en', '', '', '', '', 'N', 'LINK', 'ENENENENENENENENEN', '', 204, 'INTERNO', '', '', 0),
(123, 'main_chi_siamo', '&lt;strong&gt;Father&amp;amp;Son&lt;/strong&gt; fonda le radici in oltre vent&rsquo;anni di storia a contatto con il mondo dell&rsquo;illuminazione e del design, prima come terzisti di marchi primari, poi come produttori di apparecchi propri. Questa esperienza costituisce un substrato essenziale nei nostri processi, dall&rsquo;ideazione alla produzione. Conoscenza dei materiali, attenzione ai dettagli, scelta del design, sono frutto dell&rsquo;interazione con i nostri fornitori, designer e rivenditori. Il valore aggiunto di Father&amp;amp;Son &egrave; la capacit&agrave; di realizzare prodotti di serie dall&rsquo;elevato valore qualitativo e la possibilit&agrave; di personalizzare i prodotti rendendoli soluzioni su misura.', 'fr', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(124, 'link_1', '', 'fr', '', '', '', '', 'N', 'LINK', 'BBBBBBBBBBB', '', 204, 'INTERNO', '', '', 0),
(125, 'main_chi_siamo', '&lt;strong&gt;Father&amp;amp;Son&lt;/strong&gt; fonda le radici in oltre vent&rsquo;anni di storia a contatto con il mondo dell&rsquo;illuminazione e del design, prima come terzisti di marchi primari, poi come produttori di apparecchi propri. Questa esperienza costituisce un substrato essenziale nei nostri processi, dall&rsquo;ideazione alla produzione. Conoscenza dei materiali, attenzione ai dettagli, scelta del design, sono frutto dell&rsquo;interazione con i nostri fornitori, designer e rivenditori. Il valore aggiunto di Father&amp;amp;Son &egrave; la capacit&agrave; di realizzare prodotti di serie dall&rsquo;elevato valore qualitativo e la possibilit&agrave; di personalizzare i prodotti rendendoli soluzioni su misura.', 'es', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(126, 'link_1', '', 'es', '', '', '', '', 'N', 'LINK', 'AAAAAAAA', '', 204, 'INTERNO', '', '', 0),
(127, 'testo_1_10', 'testo_1_10', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(128, 'testo_1_11', '&lt;div style=&quot;text-align: center;&quot;&gt;AAAAAA&lt;/div&gt;\r\n&lt;div style=&quot;text-align: center;&quot;&gt;BBBBBB&lt;/div&gt;\r\n&lt;div style=&quot;text-align: center;&quot;&gt;CCCCCC&lt;/div&gt;\r\n&lt;div style=&quot;text-align: center;&quot;&gt;DDDDD&lt;/div&gt;', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(129, 'testo_1_11', 'BBBB', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(130, 'testo_1_12', 'testo_1_12', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(131, 'testo_1_12', 'GGGGGG', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(132, 'testo_1_2', 'testo_1_2', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(133, 'imm_1', '', 'it', 'img1_2.jpg', '', '300', '300', 'Y', 'IMMAGINE', '', '', 0, 'INTERNO', '', '', 0),
(134, 'imm_2', '', 'it', 'img9_1.jpg', 'aaaa', '300', '300', 'Y', 'IMMAGINE', '', '', 0, 'INTERNO', '', '', 0),
(135, 'imm_1_12', '', 'it', 'img1_3.jpg', 'aaaa', '300', '300', 'Y', 'IMMAGINE', '', 'https://www.gmail.com', 0, 'ESTERNO', '', '', 0),
(136, 'imm_2_12', '', 'it', 'img10-1-.jpg', '', '300', '300', 'Y', 'IMMAGINE', '', '', 0, 'INTERNO', '', '', 0),
(137, 'imm_1_11', '', 'it', '2-noleggio_strada_milano.jpg', 'AAAA', '300', '300', 'Y', 'IMMAGINE', '', '', 0, 'INTERNO', '', '', 0),
(138, 'imm_2_11', '', 'it', 'img9_2.jpg', 'BBBB', '300', '300', 'Y', 'IMMAGINE', '', '', 0, 'INTERNO', '', '', 0),
(139, 'link_1_12', '', 'it', '', '', '', '', 'N', 'LINK', 'Sfera in vetro soffiato', '', 360, 'ESTERNO', '', '', 0),
(140, 'link_1_11', '', 'it', '', '', '', '', 'N', 'LINK', 'VVVV', '', 362, 'ESTERNO', '', '', 0),
(141, 'link_1_12', '', 'en', '', '', '', '', 'N', 'LINK', 'Sfera in vetro soffiato', '', 360, 'ESTERNO', '', '', 0),
(142, 'link_1_11', '', 'en', '', '', '', '', 'N', 'LINK', 'VVVV', '', 362, 'ESTERNO', '', '', 0),
(143, 'testo_1_12', 'GGGGGG', 'fr', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(144, 'link_1_12', '', 'fr', '', '', '', '', 'N', 'LINK', 'Sfera in vetro soffiato', '', 360, 'ESTERNO', '', '', 0),
(145, 'testo_1_11', 'AAAAAA', 'fr', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(146, 'link_1_11', '', 'fr', '', '', '', '', 'N', 'LINK', 'VVVV', '', 362, 'ESTERNO', '', '', 0),
(147, 'testo_1_12', 'GGGGGG', 'es', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(148, 'link_1_12', '', 'es', '', '', '', '', 'N', 'LINK', 'Sfera in vetro soffiato', '', 360, 'ESTERNO', '', '', 0),
(149, 'testo_1_11', 'AAAAAA', 'es', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(150, 'link_1_11', '', 'es', '', '', '', '', 'N', 'LINK', 'VVVV', '', 362, 'ESTERNO', '', '', 0),
(151, 'testo_1_13', '7777', 'es', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(152, 'imm_1_13', '', 'it', 'categorie.png', '', '600', '300', 'Y', 'IMMAGINE', '', '', 0, 'INTERNO', '', '', 0),
(153, 'imm_2_13', '', 'it', 'img10-1-_1.jpg', '', '300', '300', 'Y', 'IMMAGINE', '', '', 0, 'INTERNO', '', '', 0),
(154, 'link_1_13', 'link_1_13', 'es', '', '', '', '', 'N', 'LINK', '', '', 0, 'INTERNO', '', '', 0),
(155, 'testo_1_13', '6666', 'fr', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(156, 'link_1_13', 'link_1_13', 'fr', '', '', '', '', 'N', 'LINK', '', '', 0, 'INTERNO', '', '', 0),
(157, 'testo_1_13', 'testo_1_13', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(158, 'link_1_13', 'link_1_13', 'en', '', '', '', '', 'N', 'LINK', '', '', 0, 'INTERNO', '', '', 0),
(159, 'testo_1_13', '4444', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(160, 'link_1_13', 'link_1_13', 'it', '', '', '', '', 'N', 'LINK', '', '', 0, 'INTERNO', '', '', 0),
(161, 'testo_1_14', 'testo_1_14', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(162, 'imm_1_14', '', 'it', '2-noleggio_strada_milano_1.jpg', '', '', '', 'N', 'IMMAGINE', '', '', 0, 'INTERNO', '', '', 0),
(163, 'imm_2_14', 'imm_2_14', 'it', '', '', '', '', 'N', 'IMMAGINE', '', '', 0, 'INTERNO', '', '', 0),
(164, 'link_1_14', 'link_1_14', 'it', '', '', '', '', 'N', 'LINK', '', '', 0, 'INTERNO', '', '', 0),
(165, 'testo_1_14', 'testo_1_14', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(166, 'link_1_14', 'link_1_14', 'en', '', '', '', '', 'N', 'LINK', '', '', 0, 'INTERNO', '', '', 0),
(167, 'testo_1_14', 'testo_1_14', 'fr', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(168, 'link_1_14', 'link_1_14', 'fr', '', '', '', '', 'N', 'LINK', '', '', 0, 'INTERNO', '', '', 0),
(169, 'testo_1_17', 'testo_1_17', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(170, 'imm_1_17', '', 'it', 'famiglie.png', '', '', '', 'N', 'IMMAGINE', '', '', 0, 'INTERNO', '', '', 0),
(171, 'imm_2_17', '', 'it', 'img10_1.jpg', '', '300', '300', 'Y', 'IMMAGINE', '', '', 0, 'INTERNO', '', '', 0),
(172, 'link_1_17', 'link_1_17', 'it', '', '', '', '', 'N', 'LINK', '', '', 0, 'INTERNO', '', '', 0),
(173, 'testo_1_16', 'AAA', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(174, 'imm_1_16', '', 'it', 'img1_4.jpg', 'AAAA', '300', '300', 'Y', 'IMMAGINE', '', '', 353, 'ESTERNO', '', '', 0),
(175, 'imm_2_16', 'imm_2_16', 'it', '', '', '', '', 'N', 'IMMAGINE', '', '', 0, 'INTERNO', '', '', 0),
(176, 'link_1_16', 'link_1_16', 'it', '', '', '', '', 'N', 'LINK', '', '', 0, 'INTERNO', '', '', 0),
(177, 'testo_1_17', 'testo_1_17', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(178, 'link_1_17', 'link_1_17', 'en', '', '', '', '', 'N', 'LINK', '', '', 0, 'INTERNO', '', '', 0),
(179, 'testo_1_17', 'testo_1_17', 'fr', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(180, 'link_1_17', '', 'fr', '', '', '', '', 'N', 'LINK', 'AAAAAAAAAAAAA', '', 361, 'ESTERNO', '', '', 0),
(181, 'testo_1_17', 'testo_1_17', 'es', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(182, 'link_1_17', 'link_1_17', 'es', '', '', '', '', 'N', 'LINK', '', '', 0, 'INTERNO', '', '', 0),
(183, 'testo_1_10', 'testo_1_10', 'fr', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(184, 'imm_1_10', 'imm_1_10', 'it', '', '', '', '', 'N', 'IMMAGINE', '', '', 0, 'INTERNO', '', '', 0),
(185, 'imm_2_10', 'imm_2_10', 'it', '', '', '', '', 'N', 'IMMAGINE', '', '', 0, 'INTERNO', '', '', 0),
(186, 'link_1_10', 'link_1_10', 'fr', '', '', '', '', 'N', 'LINK', '', '', 0, 'INTERNO', '', '', 0),
(187, 'testo_1_16', 'AAA', 'fr', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(188, 'link_1_16', 'link_1_16', 'fr', '', '', '', '', 'N', 'LINK', '', '', 0, 'INTERNO', '', '', 0),
(189, 'testo_1_8', 'testo_1_8', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(190, 'imm_1_8', 'imm_1_8', 'it', '', '', '', '', 'N', 'IMMAGINE', '', '', 0, 'INTERNO', '', '', 0),
(191, 'imm_2_8', 'imm_2_8', 'it', '', '', '', '', 'N', 'IMMAGINE', '', '', 0, 'INTERNO', '', '', 0),
(192, 'link_1_8', 'link_1_8', 'it', '', '', '', '', 'N', 'LINK', '', '', 0, 'INTERNO', '', '', 0),
(193, 'link_1_10', 'link_1_10', 'it', '', '', '', '', 'N', 'LINK', '', '', 0, 'INTERNO', '', '', 0),
(194, 'Nota', 'Nota: Le spese di spedizione sono solo stimate e saranno calcolate esattamente al momento dell&#039;acquisto, in base alle informazioni di fatturazione e di spedizione fornite.', 'fr', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(195, 'imm_1_2', 'imm_1_2', 'it', '', '', '', '', 'N', 'IMMAGINE', '', '', 0, 'INTERNO', '', '', 0),
(196, 'imm_2_2', 'imm_2_2', 'it', '', '', '', '', 'N', 'IMMAGINE', '', '', 0, 'INTERNO', '', '', 0),
(197, 'link_1_2', 'link_1_2', 'it', '', '', '', '', 'N', 'LINK', '', '', 0, 'INTERNO', '', '', 0),
(198, 'testo_1_2', 'testo_1_2', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(199, 'link_1_2', 'link_1_2', 'en', '', '', '', '', 'N', 'LINK', '', '', 0, 'INTERNO', '', '', 0),
(200, 'testo_1_2', 'testo_1_2', 'fr', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(201, 'link_1_2', 'link_1_2', 'fr', '', '', '', '', 'N', 'LINK', '', '', 0, 'INTERNO', '', '', 0),
(202, 'Blog', 'Blog', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(203, 'testo_1_21', 'testo_1_21', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(204, 'imm_1_21', 'imm_1_21', 'it', '', '', '', '', 'N', 'IMMAGINE', '', '', 0, 'INTERNO', '', '', 0),
(205, 'imm_2_21', 'imm_2_21', 'it', '', '', '', '', 'N', 'IMMAGINE', '', '', 0, 'INTERNO', '', '', 0),
(206, 'link_1_21', 'link_1_21', 'it', '', '', '', '', 'N', 'LINK', '', '', 0, 'INTERNO', '', '', 0),
(207, '<p>Per aziende o liberi professionisti la PEC o il CODICE DESTINATARIO sono obbligatori nella fatturazione elettronica. Nel caso non si possegga un CODICE DESTINATARIO, compilare solo il campo PEC. Se', '<p>Per aziende o liberi professionisti la PEC o il CODICE DESTINATARIO sono obbligatori nella fatturazione elettronica. Nel caso non si possegga un CODICE DESTINATARIO, compilare solo il campo PEC. Se non si dispone del CODICE DESTINATARIO o in caso di esonero dalla fatturazione elettronica, indicare nel campo CODICE DESTINATARIO 7 zeri (0000000).\n	Per i privati (non possessori di partita iva) tali dati non sono necessari.</br>\n	Per soggetti con sede all\'estero inserire all\'interno del codice destinatario 7 X (XXXXXXX).</p>', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(210, 'testo_fatt_elettronica', '&lt;p&gt;Per aziende o liberi professionisti la PEC o il CODICE DESTINATARIO sono obbligatori nella fatturazione elettronica. Nel caso non si possegga un CODICE DESTINATARIO, compilare solo il campo PEC. Se non si dispone del CODICE DESTINATARIO o in caso di esonero dalla fatturazione elettronica, indicare nel campo CODICE DESTINATARIO 7 zeri (0000000). Per i privati (non possessori di partita iva) tali dati non sono necessari. Per soggetti con sede all&#039;estero inserire all&#039;interno del codice destinatario 7 X (XXXXXXX).&lt;/p&gt;', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(211, 'testo_fatt_elettronica', '&lt;p&gt;For companies or freelancers the PEC or the RECIPIENT CODE are mandatory in electronic invoicing. If you do not have a RECIPIENT CODE, fill in only the PEC field. If you do not have the RECIPIENT CODE or in case of exemption from electronic invoicing, indicate 7 zeros (0000000) in the RECIPIENT CODE field. For private individuals (non-VAT holders) these data are not necessary. For subjects based abroad, enter 7 X (XXXXXXX) in the recipient code.&lt;/p&gt;', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(212, 'testo_1_10', 'testo_1_10', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(213, 'link_1_10', 'link_1_10', 'en', '', '', '', '', 'N', 'LINK', '', '', 0, 'INTERNO', '', '', 0),
(214, 'Esegua il bonifico alle seguenti coordinate bancarie ...', '&lt;p&gt;Make the transfer to the following bank details:&lt;br /&gt;Banco BPM&lt;br /&gt;Fil. By Campodarsego (PD)&lt;br /&gt;IBAM: &lt;strong&gt;IT92T0503462420000000020185&lt;/strong&gt;&lt;br /&gt;Bic: BAPPIT21204&lt;/p&gt;', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(215, 'testo_1_16', 'AAA', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(216, 'link_1_16', 'link_1_16', 'en', '', '', '', '', 'N', 'LINK', '', '', 0, 'INTERNO', '', '', 0),
(217, 'testo_1_28', 'testo_1_28', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(218, 'imm_1_28', 'imm_1_28', 'it', '', '', '', '', 'N', 'IMMAGINE', '', '', 0, 'INTERNO', '', '', 0),
(219, 'imm_2_28', 'imm_2_28', 'it', '', '', '', '', 'N', 'IMMAGINE', '', '', 0, 'INTERNO', '', '', 0),
(220, 'link_1_28', 'link_1_28', 'it', '', '', '', '', 'N', 'LINK', '', '', 0, 'INTERNO', '', '', 0),
(221, 'testo_1_24', 'testo_1_24', 'it', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(222, 'imm_1_24', 'imm_1_24', 'it', '', '', '', '', 'N', 'IMMAGINE', '', '', 0, 'INTERNO', '', '', 0),
(223, 'imm_2_24', 'imm_2_24', 'it', '', '', '', '', 'N', 'IMMAGINE', '', '', 0, 'INTERNO', '', '', 0),
(224, 'link_1_24', 'link_1_24', 'it', '', '', '', '', 'N', 'LINK', '', '', 0, 'INTERNO', '', '', 0),
(225, 'testo_1_28', 'testo_1_28', 'en', '', '', '', '', 'N', 'TESTO', '', '', 0, 'INTERNO', '', '', 0),
(226, 'link_1_28', 'link_1_28', 'en', '', '', '', '', 'N', 'LINK', '', '', 0, 'INTERNO', '', '', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `tipi_azienda`
--

CREATE TABLE `tipi_azienda` (
  `id_tipo_azienda` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `titolo` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `id_order` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `tipi_contenuto`
--

CREATE TABLE `tipi_contenuto` (
  `id_tipo` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `titolo` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `id_order` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `descrizione` text CHARACTER SET utf8,
  `tipo` char(20) NOT NULL DEFAULT 'FASCIA'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `tipi_contenuto`
--

INSERT INTO `tipi_contenuto` (`id_tipo`, `data_creazione`, `titolo`, `id_order`, `descrizione`, `tipo`) VALUES
(1, '2018-08-17 08:03:57', 'fascia tipo 1', 1, '&lt;div&gt;\r\n    [testo testo_1]\r\n    &lt;br /&gt;\r\n    [immagine imm_1] [immagine imm_2]\r\n    &lt;br /&gt;\r\n    [link link_1]\r\n&lt;/div&gt;', 'FASCIA'),
(2, '2018-08-17 08:03:57', 'Blocco prodotti', 2, '&lt;div&gt;\r\n    [prodotti]\r\n&lt;/div&gt;', 'FASCIA'),
(3, '2020-06-29 11:11:02', 'Slide + Carrello', 3, '&lt;div class=&quot;woocommerce-product-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-6 images&quot; data-columns=&quot;6&quot; style=&quot;opacity: 0; transition: opacity .25s ease-in-out;&quot;&gt;\r\n    [slide_prodotto]\r\n&lt;/div&gt;\r\n&lt;div class=&quot;summary entry-summary&quot;&gt;\r\n	&lt;div class=&quot;inner&quot;&gt;\r\n		[carrello_prodotto]\r\n	&lt;/div&gt;\r\n&lt;/div&gt;', 'FASCIA'),
(4, '2020-08-01 08:48:15', 'Contenuto 1', 4, '111', 'GENERICO');

-- --------------------------------------------------------

--
-- Struttura della tabella `tipi_documento`
--

CREATE TABLE `tipi_documento` (
  `id_tipo_doc` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `titolo` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `tipi_documento`
--

INSERT INTO `tipi_documento` (`id_tipo_doc`, `data_creazione`, `titolo`) VALUES
(1, '2020-07-07 09:20:55', 'Documentazione'),
(2, '2020-07-07 09:20:55', 'Certificazioni'),
(3, '2020-07-07 09:21:09', 'Disegni Tecnici');

-- --------------------------------------------------------

--
-- Struttura della tabella `traduzioni`
--

CREATE TABLE `traduzioni` (
  `id_t` int(10) UNSIGNED NOT NULL,
  `chiave` varchar(200) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `valore` tinytext NOT NULL,
  `lingua` char(2) NOT NULL DEFAULT 'it',
  `contesto` char(12) NOT NULL DEFAULT 'front',
  `tradotta` char(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `traduzioni`
--

INSERT INTO `traduzioni` (`id_t`, `chiave`, `valore`, `lingua`, `contesto`, `tradotta`) VALUES
(1, 'carrello', 'carrello', 'it', 'front', '1'),
(3, 'sei qui', 'sei qui', 'it', 'front', '1'),
(4, 'Accedi', 'Accedi', 'it', 'front', '1'),
(5, 'Indirizzo e-mail', 'Indirizzo e-mail', 'it', 'front', '1'),
(6, 'Password', 'Password', 'it', 'front', '1'),
(7, 'Hai dimenticato la password?', 'Hai dimenticato la password?', 'it', 'front', '1'),
(8, 'Crea un account', 'Crea un account', 'it', 'front', '1'),
(10, 'esegui il login', 'esegui il login', 'it', 'front', '1'),
(11, 'E-Mail o Password sbagliati', 'E-Mail o Password sbagliati', 'it', 'front', '1'),
(12, 'Privato', 'Privato', 'it', 'front', '1'),
(13, 'Azienda', 'Azienda', 'it', 'front', '1'),
(14, 'Nome', 'Nome', 'it', 'front', '1'),
(15, 'Cognome', 'Cognome', 'it', 'front', '1'),
(16, 'Ragione sociale', 'Ragione sociale', 'it', 'front', '1'),
(17, 'Codice fiscale', 'Codice fiscale', 'it', 'front', '1'),
(18, 'Partita iva', 'Partita iva', 'it', 'front', '1'),
(19, 'Indirizzo', 'Indirizzo', 'it', 'front', '1'),
(20, 'Cap', 'Cap', 'it', 'front', '1'),
(21, 'Provincia', 'Provincia', 'it', 'front', '1'),
(22, 'Città', 'Città', 'it', 'front', '1'),
(23, 'Telefono', 'Telefono', 'it', 'front', '1'),
(24, 'Email', 'Email', 'it', 'front', '1'),
(25, 'Conferma email', 'Conferma email', 'it', 'front', '1'),
(26, 'Continua come utente ospite', 'Continua come utente ospite', 'it', 'front', '1'),
(27, 'Crea account', 'Crea account', 'it', 'front', '1'),
(28, 'Conferma password', 'Conferma password', 'it', 'front', '1'),
(29, 'registrati', 'registrati', 'it', 'front', '1'),
(30, 'Si prega di ricontrollare <b>l\'indirizzo Email</b>', 'Si prega di ricontrollare <b>l\'indirizzo Email</b>', 'it', 'front', '1'),
(31, 'Si prega di ricontrollare il campo <b>conferma dell\'indirizzo Email</b>', 'Si prega di ricontrollare il campo <b>conferma dell\'indirizzo Email</b>', 'it', 'front', '1'),
(32, 'I due indirizzi email non corrispondono', 'I due indirizzi email non corrispondono', 'it', 'front', '1'),
(33, 'Si prega di accettare le condizioni di privacy', 'Si prega di accettare le condizioni di privacy', 'it', 'front', '1'),
(34, 'Si prega di indicare se siete un privato o un\'azienda', 'Si prega di indicare se siete un privato o un\'azienda', 'it', 'front', '1'),
(35, 'La sua E-Mail è già presente nel nostro sistema, significa che è già registrato nel nostro sito web.<br />Se non ricorda la password può impostarne una nuova al seguente', 'La sua E-Mail è già presente nel nostro sistema, significa che è già registrato nel nostro sito web.<br />Se non ricorda la password può impostarne una nuova al seguente', 'it', 'front', '1'),
(36, 'indirizzo web', 'indirizzo web', 'it', 'front', '1'),
(37, 'Questa E-Mail è già usata da un altro utente e non può quindi essere scelta', 'Questa E-Mail è già usata da un altro utente e non può quindi essere scelta', 'it', 'front', '1'),
(38, 'Le due password non coincidono', 'Le due password non coincidono', 'it', 'front', '1'),
(39, 'Solo i seguenti caratteri sono permessi per la password', 'Solo i seguenti caratteri sono permessi per la password', 'it', 'front', '1'),
(40, 'Tutte le lettere, maiuscole o minuscole', 'Tutte le lettere, maiuscole o minuscole', 'it', 'front', '1'),
(41, 'Tutti i numeri', 'Tutti i numeri', 'it', 'front', '1'),
(42, 'I seguenti caratteri', 'I seguenti caratteri', 'it', 'front', '1'),
(43, 'Si prega di controllare che il campo <b>telefono</b> contenga solo cifre numeriche', 'Si prega di controllare che il campo <b>telefono</b> contenga solo cifre numeriche', 'it', 'front', '1'),
(44, 'Si prega di controllare che il campo <b>cap</b> contenga solo cifre numeriche', 'Si prega di controllare che il campo <b>cap</b> contenga solo cifre numeriche', 'it', 'front', '1'),
(45, 'Si prega di controllare il campo <b>Codice Fiscale</b>', 'Si prega di controllare il campo <b>Codice Fiscale</b>', 'it', 'front', '1'),
(46, 'Si prega di controllare il campo <b>Partita Iva', 'Si prega di controllare il campo <b>Partita Iva', 'it', 'front', '1'),
(47, 'NON ACCETTO', 'NON ACCETTO', 'it', 'front', '1'),
(48, 'ACCETTO', 'ACCETTO', 'it', 'front', '1'),
(49, 'Completa registrazione', 'Completa registrazione', 'it', 'front', '1'),
(50, 'Si prega di controllare i campi evidenziati', 'Si prega di controllare i campi evidenziati', 'it', 'front', '1'),
(51, 'Area riservata', 'Area riservata', 'it', 'front', '1'),
(52, 'Modifica account', 'Modifica account', 'it', 'front', '1'),
(55, 'Modifica dati', 'Modifica dati', 'it', 'front', '1'),
(56, 'Gentile cliente, di seguito le credenziali per l\'accesso alla sua area riservata nel nostro sito web', 'Gentile cliente, di seguito le credenziali per l\'accesso alla sua area riservata nel nostro sito web', 'it', 'front', '1'),
(57, 'Username', 'Username', 'it', 'front', '1'),
(58, 'Potrà accedere alla propria area riservata visitando il seguente', 'Potrà accedere alla propria area riservata visitando il seguente', 'it', 'front', '1'),
(59, 'Lista indirizzi di spedizione', 'Lista indirizzi di spedizione', 'it', 'front', '1'),
(60, 'Indirizzi di spedizione', 'Indirizzi di spedizione', 'it', 'front', '1'),
(62, 'Non hai alcun indirizzo configurato', 'Non hai alcun indirizzo configurato', 'it', 'front', '1'),
(63, 'Lista ordini effettuati', 'Lista ordini effettuati', 'it', 'front', '1'),
(64, 'Modifica password', 'Modifica password', 'it', 'front', '1'),
(67, 'Vecchia password', 'Vecchia password', 'it', 'front', '1'),
(69, 'Vecchia password sbagliata', 'Vecchia password sbagliata', 'it', 'front', '1'),
(70, 'Descrizione', 'Descrizione', 'it', 'front', '1'),
(71, 'Prodotti correlati', 'Prodotti correlati', 'it', 'front', '1'),
(72, 'Informazioni aggiuntive', 'Informazioni aggiuntive', 'it', 'front', '1'),
(73, 'Peso', 'Peso', 'it', 'front', '1'),
(74, 'Altre immagini', 'Altre immagini', 'it', 'front', '1'),
(75, 'Carrello', 'Carrello', 'it', 'front', '1'),
(76, 'Leggi tutto', 'Leggi tutto', 'it', 'front', '1'),
(77, 'I NOSTRI RACCONTI', 'I NOSTRI RACCONTI', 'it', 'front', '1'),
(78, 'FAQ', 'FAQ', 'it', 'front', '1'),
(79, 'Condizioni generali di vendita', 'Condizioni generali di vendita', 'it', 'front', '1'),
(80, 'Condizioni di privacy', 'Condizioni di privacy', 'it', 'front', '1'),
(81, 'Articoli recenti', 'Articoli recenti', 'it', 'front', '1'),
(82, 'Categorie', 'Categorie', 'it', 'front', '1'),
(83, 'Inviaci un <b>messaggio</b>', 'Inviaci un <b>messaggio</b>', 'it', 'front', '1'),
(84, 'E-mail', 'E-mail', 'it', 'front', '1'),
(85, 'Messaggio', 'Messaggio', 'it', 'front', '1'),
(86, 'Accetto', 'Accetto', 'it', 'front', '1'),
(87, 'Invia il messaggio', 'Invia il messaggio', 'it', 'front', '1'),
(88, 'Si prega di compilare il campo Nome', 'Si prega di compilare il campo Nome', 'it', 'front', '1'),
(89, 'Si prega di ricontrollare l\'indirizzo E-Mail', 'Si prega di ricontrollare l\'indirizzo E-Mail', 'it', 'front', '1'),
(90, 'PRIVACY: I dati inseriti saranno trattati ai sensi del DL 196/2003 dal soggetto incaricato', 'PRIVACY: I dati inseriti saranno trattati ai sensi del DL 196/2003 dal soggetto incaricato', 'it', 'front', '1'),
(91, 'Autorizzo pertanto il trattamento dei dati da me comunicati', 'Autorizzo pertanto il trattamento dei dati da me comunicati', 'it', 'front', '1'),
(92, 'Nome (Richiesto)', 'Nome (Richiesto)', 'it', 'front', '1'),
(93, 'E-mail (Richiesto)', 'E-mail (Richiesto)', 'it', 'front', '1'),
(94, 'Oggetto (Richiesto)', 'Oggetto (Richiesto)', 'it', 'front', '1'),
(95, 'richiedi una nuova password', 'richiedi una nuova password', 'it', 'front', '1'),
(96, 'Richiesta nuova password', 'Richiesta nuova password', 'it', 'front', '1'),
(98, 'Inserisci l\'indirizzo e-mail con il quale ti sei registrato al sito, ti invieremo una mail attraverso la quale potrai ottenere una nuova password', 'Inserisci l\'indirizzo e-mail con il quale ti sei registrato al sito, ti invieremo una mail attraverso la quale potrai ottenere una nuova password', 'it', 'front', '1'),
(101, 'GIARDINEGGIANDO, AL FIANCO DEL PROFESSIONISTA', 'GIARDINEGGIANDO, AL FIANCO DEL PROFESSIONISTA', 'it', 'front', '1'),
(102, 'CONTATTACI ORA', 'MAGGIORI INFORMAZIONI', 'it', 'front', '1'),
(103, 'ISCRIVITI ALLA NEWSLETTER', 'ISCRIVITI ALLA NEWSLETTER', 'it', 'front', '1'),
(104, 'CERCA NEL SITO', 'CERCA NEL SITO', 'it', 'front', '1'),
(105, 'Ricerca per:', 'Ricerca per:', 'it', 'front', '1'),
(106, 'L’IMPORTANZA DI UNA CORRETTA INFORMAZIONE', 'L’IMPORTANZA DI UNA CORRETTA INFORMAZIONE', 'it', 'front', '1'),
(107, 'sottotitolo', 'sottotitolo', 'it', 'front', '1'),
(108, 'Nazione', 'Nazione', 'it', 'front', '1'),
(109, 'info@piccolaofficina.it', 'info@giardineggiando.it', 'it', 'front', '1'),
(110, 'Lian snc', 'Giardineggiando', 'it', 'front', '1'),
(111, 'p.iva', 'p.iva', 'it', 'front', '1'),
(112, '*********', 'IT04433980283', 'it', 'front', '1'),
(113, 'invio credenziali nuovo utente', 'invio credenziali nuovo utente', 'it', 'front', '1'),
(114, 'Ciao', 'Ciao', 'it', 'front', '1'),
(115, 'Account creato', 'Account creato', 'it', 'front', '1'),
(116, 'L\'account è stato creato correttamente. Le è stata inviata una mail con le credenziali d\'accesso che ha scelto', 'L\'account è stato creato correttamente. Le è stata inviata una mail con le credenziali d\'accesso che ha scelto', 'it', 'front', '1'),
(117, 'Vai all\'', 'Vai all\'', 'it', 'front', '1'),
(118, 'Notifiche', 'Notifiche', 'it', 'front', '1'),
(119, 'Torna alla', 'Torna alla', 'it', 'front', '1'),
(120, 'totale carrello', 'totale carrello', 'it', 'front', '1'),
(121, 'richiesta di modifica password', 'richiesta di modifica password', 'it', 'front', '1'),
(122, 'Gentile cliente, ha richiesto di poter impostare una nuova password per il suo account', 'Gentile cliente, ha richiesto di poter impostare una nuova password per il suo account', 'it', 'front', '1'),
(123, 'Le sarà possibile impostare una nuova password al seguente', 'Le sarà possibile impostare una nuova password al seguente', 'it', 'front', '1'),
(124, 'Se ha ricevuto questa e-mail per errore le chiediamo gentilmente di cancellarla', 'Se ha ricevuto questa e-mail per errore le chiediamo gentilmente di cancellarla', 'it', 'front', '1'),
(125, 'Invio mail per cambio password', 'Invio mail per cambio password', 'it', 'front', '1'),
(126, 'Le è stata inviata una mail con un link. Segua tale link se vuole impostare una nuova password', 'Le è stata inviata una mail con un link. Segua tale link se vuole impostare una nuova password', 'it', 'front', '1'),
(127, 'Cos\'è PayPal?', 'Cos\'è PayPal?', 'it', 'front', '1'),
(128, 'Voglio essere iscritto alla newsletter per conoscere le promozioni e le novità del negozio', 'Voglio essere iscritto alla newsletter per conoscere le promozioni e le novità del negozio', 'it', 'front', '1'),
(129, 'Ho letto e accettato i', 'Ho letto e accettato i', 'it', 'front', '1'),
(130, 'termini e condizioni di vendita', 'termini e condizioni di vendita', 'it', 'front', '1'),
(131, 'Questo sito utilizza cookie per migliorare la tua esperienza di navigazione. Se continui a navigare accetterai l\'uso di questi cookie.', 'Questo sito utilizza cookie per migliorare la tua esperienza di navigazione. Se continui a navigare accetterai l\'uso di questi cookie.', 'it', 'front', '1'),
(132, 'Ulteriori informazioni', 'Ulteriori informazioni', 'it', 'front', '1'),
(133, 'accetto', 'accetto', 'it', 'front', '1'),
(134, 'Pagina non trovata', 'Pagina non trovata', 'it', 'front', '1'),
(135, 'Bonifico bancario.', 'Bonifico bancario.', 'it', 'front', '1'),
(136, 'Contrassegno.', 'Contrassegno.', 'it', 'front', '1'),
(137, 'Paypal / Carta di credito.', 'Paypal / Carta di credito.', 'it', 'front', '1'),
(138, '<div>Continua come utente ospite</div>', '<div>Continua come utente ospite</div>', 'it', 'front', '1'),
(139, '<div>Crea account</div>', '<div>Crea account</div>', 'it', 'front', '1'),
(140, 'Grazie! Il suo ordine è stato ricevuto e verrà processato al più presto.', 'Grazie! Il suo ordine è stato ricevuto e verrà processato al più presto.', 'it', 'front', '1'),
(141, 'Controlli la sua casella di posta elettronica, le è stata inviata una mail con il resoconto dell\'ordine.', 'Controlli la sua casella di posta elettronica, le è stata inviata una mail con il resoconto dell\'ordine.', 'it', 'front', '1'),
(142, 'Può controllare in qualsiasi momento i dettagli dell\'ordine al', 'Può controllare in qualsiasi momento i dettagli dell\'ordine al', 'it', 'front', '1'),
(143, 'seguente indirizzo web', 'seguente indirizzo web', 'it', 'front', '1'),
(144, 'Dettagli pagamento:', 'Dettagli pagamento:', 'it', 'front', '1'),
(145, 'Iva inclusa', 'Iva inclusa', 'it', 'front', '1'),
(146, 'Libero professionista', 'Libero professionista', 'it', 'front', '1'),
(147, 'Imposta nuova password', 'Imposta nuova password', 'it', 'front', '1'),
(148, 'Imposta la password', 'Imposta la password', 'it', 'front', '1'),
(152, 'Password cambiata', 'Password cambiata', 'it', 'front', '1'),
(153, 'La password è stata correttamente impostata', 'La password è stata correttamente impostata', 'it', 'front', '1'),
(154, 'Vai al', 'Vai al', 'it', 'front', '1'),
(159, 'Aggiungi indirizzo', 'Aggiungi indirizzo', 'it', 'front', '1'),
(160, 'Modifica', 'Modifica', 'it', 'front', '1'),
(161, 'Elimina', 'Elimina', 'it', 'front', '1'),
(162, 'Gestisci spedizione', 'Gestisci spedizione', 'it', 'front', '1'),
(163, 'Modifica l\'indirizzo di spedizione', 'Modifica l\'indirizzo di spedizione', 'it', 'front', '1'),
(165, 'Aggiungi un indirizzo di spedizione', 'Aggiungi un indirizzo di spedizione', 'it', 'front', '1'),
(293, 'Visualizzazione di tutti i', 'Visualizzazione di tutti i', 'it', 'front', '1'),
(294, 'risultati', 'risultati', 'it', 'front', '1'),
(295, 'Ordinamento predefinito', 'Ordinamento predefinito', 'it', 'front', '1'),
(296, 'Prezzo: dal più economico', 'Prezzo: dal più economico', 'it', 'front', '1'),
(297, 'Prezzo: dal più caro', 'Prezzo: dal più caro', 'it', 'front', '1'),
(298, 'Sconti 15% per professionisti', 'Sconti per professionisti', 'it', 'front', '1'),
(299, 'Si prega di ricontrollare l\'indirizzo e-mail', 'Si prega di ricontrollare l\'indirizzo e-mail', 'it', 'front', '1'),
(488, 'Vedi l\'informativa sui cookie', 'Vedi l\'informativa sui cookie', 'it', 'front', '1'),
(489, 'Revoca l\'approvazione all\'utilizzo dei cookies', 'Revoca l\'approvazione all\'utilizzo dei cookies', 'it', 'front', '1'),
(495, 'DATI PER LA FATTURAZIONE ELETTRONICA', 'DATI PER LA FATTURAZIONE ELETTRONICA', 'it', 'front', '1'),
(496, 'Pec', 'Pec', 'it', 'front', '1'),
(497, 'Codice destinatario', 'Codice destinatario', 'it', 'front', '1'),
(498, 'Si prega di ricontrollare <b>l\'indirizzo Pec</b>', 'Si prega di ricontrollare <b>l\'indirizzo Pec</b>', 'it', 'front', '1'),
(499, 'Si prega di ricontrollare <b>il Codice Destinatario</b>', 'Si prega di ricontrollare <b>il Codice Destinatario</b>', 'it', 'front', '1'),
(500, 'Gentile cliente, di seguito le credenziali dell\'account creato dalla nostra APP', 'Gentile cliente, di seguito le credenziali dell\'account creato dalla nostra APP', 'it', 'front', '1'),
(501, 'Potrà utilizzare tali credenziali per eseguire gli acquisti desiderati dalla nostra APP', 'Potrà utilizzare tali credenziali per eseguire gli acquisti desiderati dalla nostra APP', 'it', 'front', '1'),
(502, 'Cordiali saluti.', 'Cordiali saluti.', 'it', 'front', '1'),
(503, 'Cordiali saluti', 'Cordiali saluti', 'it', 'front', '1'),
(504, 'Siamo spiacenti, non esiste alcun utente attivo corrispondente all\'email da lei inserita', 'Siamo spiacenti, non esiste alcun utente attivo corrispondente all\'email da lei inserita', 'it', 'front', '1'),
(505, 'wishlist', 'wishlist', 'it', 'front', '1'),
(506, 'La tua lista dei desideri', 'La tua lista dei desideri', 'it', 'front', '1'),
(507, 'Modifica dati fatturazione', 'Modifica dati fatturazione', 'it', 'front', '1'),
(511, 'Gestione della privacy', 'Gestione della privacy', 'it', 'front', '1'),
(513, 'Esci', 'Esci', 'it', 'front', '1'),
(515, 'non sei', 'non sei', 'it', 'front', '1'),
(516, 'Dalla tua area riservata puoi vedere gli', 'Dalla tua area riservata puoi vedere gli', 'it', 'front', '1'),
(517, 'ordini effettuati', 'ordini effettuati', 'it', 'front', '1'),
(518, 'gestire i tuoi', 'gestire i tuoi', 'it', 'front', '1'),
(519, 'Gestione privacy', 'Gestione privacy', 'it', 'front', '1'),
(520, 'Ordini effettuati', 'Ordini effettuati', 'it', 'front', '1'),
(522, 'Resoconto dell\'ordine<', 'Resoconto dell\'ordine<', 'it', 'front', '1'),
(523, 'Resoconto dell\'ordine', 'Resoconto dell\'ordine', 'it', 'front', '1'),
(524, 'Inserisci dati fatturazione', 'Inserisci dati fatturazione', 'it', 'front', '1'),
(526, 'Esegui il login', 'Esegui il login', 'it', 'front', '1'),
(527, 'Tutti', 'Tutti', 'it', 'front', '1'),
(528, 'Shop', 'Shop', 'it', 'front', '1'),
(529, 'Il prodotto', 'Il prodotto', 'it', 'front', '1'),
(530, 'è stato aggiunto al tuo carrello', 'è stato aggiunto al tuo carrello', 'it', 'front', '1'),
(531, 'è stato aggiunto al carrello', 'è stato aggiunto al carrello', 'it', 'front', '1'),
(532, 'Il carrello è vuoto', 'Il carrello è vuoto', 'it', 'front', '1'),
(533, 'Dati per la fatturazione elettronica', 'Dati per la fatturazione elettronica', 'it', 'front', '1'),
(534, 'Famiglie', 'Famiglie', 'it', 'front', '1'),
(535, 'Prodotti', 'Prodotti', 'it', 'front', '1'),
(536, 'Famiglia', 'Famiglia', 'it', 'front', '1'),
(537, 'Categoria', 'Categoria', 'it', 'front', '1'),
(538, 'testo home', 'testo home', 'it', 'front', '1'),
(539, 'testo home in evidenza da modificare', 'testo home in evidenza da modificare', 'it', 'front', '1'),
(540, '049 211111111', '049 211111111 ee dfgdfg', 'it', 'front', '1'),
(541, 'infi@tttttt', 'infi@tttttt', 'it', 'front', '1'),
(542, 'Indirizzo...', 'Indirizzo...', 'it', 'front', '1'),
(543, 'I nostri prodotti', 'I nostri prodotti', 'it', 'front', '1'),
(544, 'Cai al carrello', 'Vai al carrello', 'it', 'front', '1'),
(545, 'Completa acquisto', 'Completa acquisto', 'it', 'front', '1'),
(546, 'Very good Design. Flexible. Fast Support.', 'Very good Design. Flexible. Fast Support.', 'it', 'front', '1'),
(548, '(customer)', '(customer)', 'it', 'front', '1'),
(549, 'Copyright © 2019', 'Copyright © 2019', 'it', 'front', '1'),
(550, 'XXXXX', 'XXXXX', 'it', 'front', '1'),
(551, 'All rights reserved.', 'All rights reserved.', 'it', 'front', '1'),
(552, 'Father&Son', 'Father&Son', 'it', 'front', '1'),
(553, 'Perche noi?', 'Perche noi?', 'it', 'front', '1'),
(554, 'Via XXX...', 'Via XXX...', 'it', 'front', '1'),
(555, '123456', '123456', 'it', 'front', '1'),
(556, 'info@xxx', 'info@xxx', 'it', 'front', '1'),
(557, 'Contattaci', 'Contattaci', 'it', 'front', '1'),
(558, 'Form contatto', 'Form contatto', 'it', 'front', '1'),
(559, 'clicca qui', 'clicca qui', 'it', 'front', '1'),
(561, 'Articolo aggiunto alla tua lista dei desideri.', 'Articolo aggiunto alla tua lista dei desideri.', 'it', 'front', '1'),
(562, 'Aggiungi alla lista dei desideri', 'Aggiungi alla lista dei desideri', 'it', 'front', '1'),
(563, 'Elimina dalla lista dei desideri', 'Elimina dalla lista dei desideri', 'it', 'front', '1'),
(564, 'Nuovo Design', 'Nuovo Design', 'it', 'front', '1'),
(565, 'info@tttttt', 'info@tttttt', 'it', 'front', '1'),
(568, 'Il nostro blog', 'Il nostro blog', 'it', 'front', '1'),
(569, 'Testo da modificare', 'Oltre vent&rsquo;anni di storia nel mondo dell&rsquo;illuminazione e del design prendono la forma di prodotti dall&#039;elevato valore qualitativo, con possibilit&agrave; di personalizzazione nei colori e attraverso soluzioni su misura. Sulla famiglia TIG', 'it', 'front', '1'),
(570, '+ Leggi tutto', '+ Leggi tutto', 'it', 'front', '1'),
(573, 'Immagine in slide', 'Immagine in slide', 'it', 'front', '1'),
(581, 'Immagine piccola', 'Immagine piccola', 'it', 'front', '1'),
(608, 'Scopri', 'Scopri', 'it', 'front', '1'),
(612, 'Post precedente', 'Post precedente', 'it', 'front', '1'),
(613, 'p_iva_footer', 'p_iva_footer', 'it', 'front', '1'),
(614, 'Vai alla home', 'Vai alla home', 'it', 'front', '1'),
(615, 'VAI ALLA HOME', 'VAI ALLA HOME', 'it', 'front', '1'),
(616, 'ragione sociale', 'ragione sociale', 'it', 'front', '1'),
(617, 'Scarica documenti', 'Scarica documenti', 'it', 'front', '1'),
(618, 'Foglio istruzioni', 'Foglio istruzioni', 'it', 'front', '1'),
(619, 'Scheda tecnica', 'Scheda tecnica', 'it', 'front', '1'),
(620, 'Etichetta energetica', 'Etichetta energetica', 'it', 'front', '1'),
(621, 'Downloads', 'Downloads', 'it', 'front', '1'),
(622, 'Ho letto e accettato le', 'Ho letto e accettato le', 'it', 'front', '1'),
(623, 'condizioni di privacy', 'condizioni di privacy', 'it', 'front', '1'),
(624, 'Questo sito utilizza cookie per migliorare la tua esperienza di navigazione. Cliccando su OK o continuando a navigare ne consenti l\'utilizzo.', 'Questo sito utilizza cookie per migliorare la tua esperienza di navigazione. Cliccando su OK o continuando a navigare ne consenti l\'utilizzo.', 'it', 'front', '1'),
(640, 'famiglie', 'marchi', 'it', 'back', '1'),
(641, 'famiglia', 'marchio', 'it', 'back', '1'),
(642, 'Immagine piccola', 'Immagine piccola', 'it', 'back', '1'),
(1629, 'Blog', 'News & Eventi', 'it', 'back', '1'),
(1630, 'Referenze', 'Referenze', 'it', 'back', '1'),
(1635, 'Non esiste il prodotto con la seguente variante:', 'Non esiste il prodotto con la seguente variante:', 'it', 'front', '1'),
(1636, 'Si prega di selezionare la variante:', 'Si prega di selezionare la variante:', 'it', 'front', '1'),
(1637, 'Si prega di indicare una quantità maggiore di zero', 'Si prega di indicare una quantità maggiore di zero', 'it', 'front', '1'),
(1638, 'Si prega di selezionare la variante del prodotto', 'Si prega di selezionare la variante del prodotto', 'it', 'front', '1'),
(2376, '(customer)', '(customer)', 'en', 'front', '1'),
(2377, '+ Leggi tutto', '+ Read all', 'en', 'front', '1'),
(2378, '049 211111111', '049 211111111', 'en', 'front', '1'),
(2379, '123456', '123456', 'en', 'front', '1'),
(2380, '<div>Continua come utente ospite</div>', '&lt;div&gt;Continue as a guest user&lt;/div&gt;', 'en', 'front', '1'),
(2381, '<div>Crea account</div>', '&lt;div&gt;Create account&lt;/div&gt;', 'en', 'front', '1'),
(2382, 'Accedi', 'Log in', 'en', 'front', '1'),
(2383, 'ACCETTO', 'I ACCEPT', 'en', 'front', '1'),
(2384, 'Accetto', 'I accept', 'en', 'front', '1'),
(2385, 'accetto', 'I accept', 'en', 'front', '1'),
(2386, 'Account creato', 'Account created', 'en', 'front', '1'),
(2387, 'Aggiungi alla lista dei desideri', 'Add to wishlist', 'en', 'front', '1'),
(2388, 'Aggiungi indirizzo', 'Add address', 'en', 'front', '1'),
(2389, 'Aggiungi un indirizzo di spedizione', 'Add a shipping address', 'en', 'front', '1'),
(2390, 'All rights reserved.', 'All rights reserved.', 'en', 'front', '1'),
(2391, 'Altre immagini', 'Other pictures', 'en', 'front', '1'),
(2392, 'Area riservata', 'Reserved area', 'en', 'front', '1'),
(2393, 'Articoli recenti', 'Recent articles', 'en', 'front', '1'),
(2394, 'Articolo aggiunto alla tua lista dei desideri.', 'Item added to your wish list.', 'en', 'front', '1'),
(2395, 'Autorizzo pertanto il trattamento dei dati da me comunicati', 'I therefore authorize the treatment of the data communicated by me', 'en', 'front', '1'),
(2396, 'Azienda', 'Company', 'en', 'front', '1'),
(2397, 'Bonifico bancario.', 'Bank transfer.', 'en', 'front', '1'),
(2398, 'Cap', 'Postal Code', 'en', 'front', '1'),
(2399, 'carrello', 'shopping cart', 'en', 'front', '1'),
(2400, 'Carrello', 'Shopping cart', 'en', 'front', '1'),
(2401, 'Categoria', 'Category', 'en', 'front', '1'),
(2402, 'Categorie', 'Categories', 'en', 'front', '1'),
(2403, 'CERCA NEL SITO', 'SEARCH IN THE SITE', 'en', 'front', '1'),
(2404, 'Ciao', 'Hi', 'en', 'front', '1'),
(2405, 'Città', 'City', 'en', 'front', '1'),
(2406, 'clicca qui', 'click here', 'en', 'front', '1'),
(2407, 'Codice destinatario', 'SDI code', 'en', 'front', '1'),
(2408, 'Codice fiscale', 'Fiscal Code', 'en', 'front', '1'),
(2409, 'Cognome', 'Surname', 'en', 'front', '1'),
(2410, 'Completa acquisto', 'Complete purchase', 'en', 'front', '1'),
(2411, 'Completa registrazione', 'Complete registration', 'en', 'front', '1'),
(2412, 'Condizioni di privacy', 'Privacy conditions', 'en', 'front', '1'),
(2413, 'condizioni di privacy', 'privacy conditions', 'en', 'front', '1'),
(2414, 'Condizioni generali di vendita', 'General conditions of Sale', 'en', 'front', '1'),
(2415, 'Conferma email', 'Confirm email', 'en', 'front', '1'),
(2416, 'Conferma password', 'Confirm password', 'en', 'front', '1'),
(2417, 'Contattaci', 'Contact', 'en', 'front', '1'),
(2418, 'Continua come utente ospite', 'Continue as a guest user', 'en', 'front', '1'),
(2419, 'Contrassegno.', 'Cash on delivery', 'en', 'front', '1'),
(2420, 'Controlli la sua casella di posta elettronica, le è stata inviata una mail con il resoconto dell\'ordine.', 'Check your email, an email has been sent with the order report.', 'en', 'front', '1'),
(2421, 'Copyright © 2019', 'Copyright © 2019', 'en', 'front', '1'),
(2422, 'Cordiali saluti', 'Best regards', 'en', 'front', '1'),
(2423, 'Cordiali saluti.', 'Best regards.', 'en', 'front', '1'),
(2424, 'Cos\'è PayPal?', 'What is PayPal?', 'en', 'front', '1'),
(2425, 'Crea account', 'Create account', 'en', 'front', '1'),
(2426, 'Crea un account', 'Create an account', 'en', 'front', '1'),
(2427, 'Dalla tua area riservata puoi vedere gli', 'From your reserved area you can see the', 'en', 'front', '1'),
(2428, 'Dati per la fatturazione elettronica', 'Electronic invoicing data', 'en', 'front', '1'),
(2429, 'DATI PER LA FATTURAZIONE ELETTRONICA', 'DATA FOR ELECTRONIC BILLING', 'en', 'front', '1'),
(2430, 'Descrizione', 'Description', 'en', 'front', '1'),
(2431, 'Dettagli pagamento:', 'Payment details:', 'en', 'front', '1'),
(2432, 'Downloads', 'Downloads', 'en', 'front', '1'),
(2433, 'è stato aggiunto al carrello', 'has been added to your cart', 'en', 'front', '1'),
(2434, 'è stato aggiunto al tuo carrello', 'has been added to your cart', 'en', 'front', '1'),
(2435, 'E-mail', 'E-mail', 'en', 'front', '1'),
(2436, 'E-mail (Richiesto)', 'E-mail (required)', 'en', 'front', '1'),
(2437, 'E-Mail o Password sbagliati', 'Wrong E-Mail or Password', 'en', 'front', '1'),
(2438, 'Elimina', 'Delete', 'en', 'front', '1'),
(2439, 'Elimina dalla lista dei desideri', 'Remove from wish list', 'en', 'front', '1'),
(2440, 'Email', 'Email', 'en', 'front', '1'),
(2441, 'Esci', 'Logout', 'en', 'front', '1'),
(2442, 'esegui il login', 'log in', 'en', 'front', '1'),
(2443, 'Esegui il login', 'Log in', 'en', 'front', '1'),
(2444, 'Etichetta energetica', 'Energy label', 'en', 'front', '1'),
(2445, 'Famiglia', 'Family', 'en', 'front', '1'),
(2446, 'Famiglie', 'Family', 'en', 'front', '1'),
(2447, 'FAQ', 'FAQ', 'en', 'front', '1'),
(2448, 'Father&Son', 'Father&Son', 'en', 'front', '1'),
(2449, 'Foglio istruzioni', 'Instruction sheet', 'en', 'front', '1'),
(2450, 'Form contatto', 'Contact form', 'en', 'front', '1'),
(2451, 'Gentile cliente, di seguito le credenziali dell\'account creato dalla nostra APP', 'Dear customer, below the credentials of the account created by our APP', 'en', 'front', '1'),
(2452, 'Gentile cliente, di seguito le credenziali per l\'accesso alla sua area riservata nel nostro sito web', 'Dear customer, here are the credentials for accessing your reserved area on our website', 'en', 'front', '1'),
(2453, 'Gentile cliente, ha richiesto di poter impostare una nuova password per il suo account', 'Dear customer, you have requested to be able to set a new password for your account', 'en', 'front', '1'),
(2454, 'Gestione della privacy', 'Privacy management', 'en', 'front', '1'),
(2455, 'Gestione privacy', 'Privacy management', 'en', 'front', '1'),
(2456, 'gestire i tuoi', 'manage yours', 'en', 'front', '1'),
(2457, 'Gestisci spedizione', 'Manage shipping', 'en', 'front', '1'),
(2458, 'Lian snc', 'Lian snc', 'en', 'front', '1'),
(2459, 'GIARDINEGGIANDO, AL FIANCO DEL PROFESSIONISTA', 'GIARDINEGGIANDO, AL FIANCO DEL PROFESSIONISTA', 'en', 'front', '1'),
(2460, 'Grazie! Il suo ordine è stato ricevuto e verrà processato al più presto.', 'Thank you! Your order has been received and will be processed as soon as possible.', 'en', 'front', '1'),
(2461, 'Hai dimenticato la password?', 'Did you forget your password?', 'en', 'front', '1'),
(2462, 'Ho letto e accettato i', 'I have read and accepted i', 'en', 'front', '1'),
(2463, 'Ho letto e accettato le', 'I have read and accepted the', 'en', 'front', '1'),
(2464, 'I due indirizzi email non corrispondono', 'The two email addresses don&#039;t match', 'en', 'front', '1'),
(2465, 'I nostri prodotti', 'Our products', 'en', 'front', '1'),
(2466, 'I NOSTRI RACCONTI', 'OUR STORIES', 'en', 'front', '1'),
(2467, 'I seguenti caratteri', 'The following characters', 'en', 'front', '1'),
(2468, 'Il carrello è vuoto', 'The cart is empty', 'en', 'front', '1'),
(2469, 'Il nostro blog', 'Our blog', 'en', 'front', '1'),
(2470, 'Il prodotto', 'The product', 'en', 'front', '1'),
(2471, 'Immagine in slide', 'Slide image', 'en', 'front', '1'),
(2472, 'Immagine piccola', 'Small image', 'en', 'front', '1'),
(2473, 'Imposta la password', 'Set your password', 'en', 'front', '1'),
(2474, 'Imposta nuova password', 'Set new password', 'en', 'front', '1'),
(2475, 'Indirizzi di spedizione', 'Shipping addresses', 'en', 'front', '1'),
(2476, 'Indirizzo', 'Address', 'en', 'front', '1'),
(2477, 'Indirizzo e-mail', 'Email address', 'en', 'front', '1'),
(2478, 'indirizzo web', 'website address', 'en', 'front', '1'),
(2479, 'Indirizzo...', 'Address...', 'en', 'front', '1'),
(2480, 'infi@tttttt', 'infi@tttttt', 'en', 'front', '1'),
(2481, 'info@piccolaofficina.it', 'info@piccolaofficina.it', 'en', 'front', '1'),
(2482, 'info@tttttt', 'info@tttttt', 'en', 'front', '1'),
(2483, 'info@xxx', 'info@xxx', 'en', 'front', '1'),
(2484, 'Informazioni aggiuntive', 'Additional information', 'en', 'front', '1'),
(2485, 'Inserisci dati fatturazione', 'Enter billing information', 'en', 'front', '1'),
(2486, 'Inserisci l\'indirizzo e-mail con il quale ti sei registrato al sito, ti invieremo una mail attraverso la quale potrai ottenere una nuova password', 'Enter the e-mail address with which you registered on the site, we will send you an email through which you can get a new password', 'en', 'front', '1'),
(2487, 'Invia il messaggio', 'Send the message', 'en', 'front', '1'),
(2488, 'Inviaci un <b>messaggio</b>', 'Send us a &lt;b&gt; message &lt;/b&gt;', 'en', 'front', '1'),
(2489, 'invio credenziali nuovo utente', 'send new user credentials', 'en', 'front', '1'),
(2490, 'Invio mail per cambio password', 'Sending email to change password', 'en', 'front', '1'),
(2491, 'ISCRIVITI ALLA NEWSLETTER', 'SUBSCRIBE TO THE NEWSLETTER', 'en', 'front', '1'),
(2492, '*********', '*********', 'en', 'front', '1'),
(2493, 'Iva inclusa', 'VAT included', 'en', 'front', '1'),
(2494, 'L\'account è stato creato correttamente. Le è stata inviata una mail con le credenziali d\'accesso che ha scelto', 'The account was successfully created. An email has been sent to her with the login credentials she has chosen', 'en', 'front', '1'),
(2495, 'La password è stata correttamente impostata', 'The password has been correctly set', 'en', 'front', '1'),
(2496, 'La sua E-Mail è già presente nel nostro sistema, significa che è già registrato nel nostro sito web.<br />Se non ricorda la password può impostarne una nuova al seguente', 'Your E-Mail is already present in our system, it means that it is already registered on our website. &lt;br /&gt; If you don&#039;t remember your password you can set a new one to the following', 'en', 'front', '1'),
(2497, 'La tua lista dei desideri', 'Your wish list', 'en', 'front', '1'),
(2498, 'Le due password non coincidono', 'The two passwords do not match', 'en', 'front', '1'),
(2499, 'Le è stata inviata una mail con un link. Segua tale link se vuole impostare una nuova password', 'An email has been sent to you with a link. Follow this link if you want to set a new password', 'en', 'front', '1'),
(2500, 'Le sarà possibile impostare una nuova password al seguente', 'You will be able to set a new password to the following', 'en', 'front', '1'),
(2501, 'Leggi tutto', 'Real all', 'en', 'front', '1'),
(2502, 'Libero professionista', 'Freelance', 'en', 'front', '1'),
(2503, 'Lista indirizzi di spedizione', 'Shipping address list', 'en', 'front', '1'),
(2504, 'Lista ordini effettuati', 'List of orders placed', 'en', 'front', '1'),
(2505, 'L’IMPORTANZA DI UNA CORRETTA INFORMAZIONE', 'THE IMPORTANCE OF CORRECT INFORMATION', 'en', 'front', '1'),
(2506, 'CONTATTACI ORA', 'CONTACT US NOW', 'en', 'front', '1'),
(2507, 'Messaggio', 'Message', 'en', 'front', '1'),
(2508, 'Modifica', 'Edit', 'en', 'front', '1'),
(2509, 'Modifica account', 'Change account', 'en', 'front', '1'),
(2510, 'Modifica dati', 'Change data', 'en', 'front', '1'),
(2511, 'Modifica dati fatturazione', 'Change billing information', 'en', 'front', '1'),
(2512, 'Modifica l\'indirizzo di spedizione', 'Change the shipping address', 'en', 'front', '1'),
(2513, 'Modifica password', 'Change Password', 'en', 'front', '1'),
(2514, 'Nazione', 'Nation', 'en', 'front', '1'),
(2515, 'Nome', 'First name', 'en', 'front', '1'),
(2516, 'Nome (Richiesto)', 'Name (required)', 'en', 'front', '1'),
(2517, 'NON ACCETTO', 'I DO NOT ACCEPT', 'en', 'front', '1'),
(2518, 'Non esiste il prodotto con la seguente variante:', 'There is no product with the following variant:', 'en', 'front', '1'),
(2519, 'Non hai alcun indirizzo configurato', 'You have no configured address', 'en', 'front', '1'),
(2520, 'non sei', 'you are not', 'en', 'front', '1'),
(2521, 'Notifiche', 'Notifications', 'en', 'front', '1'),
(2522, 'Nuovo Design', 'New Design', 'en', 'front', '1'),
(2523, 'Oggetto (Richiesto)', 'Subject (Required)', 'en', 'front', '1'),
(2524, 'Testo da modificare', 'Text to be edited', 'en', 'front', '1'),
(2525, 'Ordinamento predefinito', 'Default sort order', 'en', 'front', '1'),
(2526, 'ordini effettuati', 'orders placed', 'en', 'front', '1'),
(2527, 'Ordini effettuati', 'Orders placed', 'en', 'front', '1'),
(2528, 'p.iva', 'VAT number', 'en', 'front', '1'),
(2529, 'Pagina non trovata', 'Page not found', 'en', 'front', '1'),
(2530, 'Partita iva', 'VAT number', 'en', 'front', '1'),
(2531, 'Password', 'Password', 'en', 'front', '1'),
(2532, 'Password cambiata', 'Password changed', 'en', 'front', '1'),
(2533, 'Paypal / Carta di credito.', 'Paypal / Credit card.', 'en', 'front', '1'),
(2534, 'Pec', 'Pec', 'en', 'front', '1'),
(2535, 'Perche noi?', 'Because we?', 'en', 'front', '1'),
(2536, 'Peso', 'Weight', 'en', 'front', '1'),
(2537, 'Post precedente', 'Previous post', 'en', 'front', '1'),
(2540, 'Potrà accedere alla propria area riservata visitando il seguente', 'You can access your private area by visiting the following', 'en', 'front', '1'),
(2541, 'Potrà utilizzare tali credenziali per eseguire gli acquisti desiderati dalla nostra APP', 'You can use these credentials to make the desired purchases from our APP', 'en', 'front', '1'),
(2542, 'Prezzo: dal più caro', 'Price: from the most expensive', 'en', 'front', '1'),
(2543, 'Prezzo: dal più economico', 'Price: from the cheapest', 'en', 'front', '1'),
(2544, 'PRIVACY: I dati inseriti saranno trattati ai sensi del DL 196/2003 dal soggetto incaricato', 'PRIVACY: The data entered will be processed in accordance with Legislative Decree 196/2003 by the person in charge', 'en', 'front', '1'),
(2545, 'Privato', 'Private', 'en', 'front', '1'),
(2546, 'Prodotti', 'Products', 'en', 'front', '1'),
(2547, 'Prodotti correlati', 'Related products', 'en', 'front', '1'),
(2548, 'Provincia', 'Province', 'en', 'front', '1'),
(2549, 'Può controllare in qualsiasi momento i dettagli dell\'ordine al', 'You can check the order details at any time at', 'en', 'front', '1'),
(2550, 'p_iva_footer', 'p_iva_footer', 'en', 'front', '1'),
(2551, 'Questa E-Mail è già usata da un altro utente e non può quindi essere scelta', 'This E-Mail is already used by another user and therefore cannot be chosen', 'en', 'front', '1'),
(2552, 'Questo sito utilizza cookie per migliorare la tua esperienza di navigazione. Cliccando su OK o continuando a navigare ne consenti l\'utilizzo.', 'This site uses cookies to improve your browsing experience. By clicking on OK or continuing to browse, you consent to its use.', 'en', 'front', '1'),
(2553, 'Questo sito utilizza cookie per migliorare la tua esperienza di navigazione. Se continui a navigare accetterai l\'uso di questi cookie.', 'This site uses cookies to improve your browsing experience. If you continue browsing you will accept the use of these cookies.', 'en', 'front', '1'),
(2554, 'Ragione sociale', 'Business name', 'en', 'front', '1'),
(2555, 'ragione sociale', 'business name', 'en', 'front', '1'),
(2556, 'registrati', 'sign in', 'en', 'front', '1'),
(2557, 'Resoconto dell\'ordine', 'Order report', 'en', 'front', '1'),
(2558, 'Resoconto dell\'ordine<', 'Order report', 'en', 'front', '1'),
(2559, 'Revoca l\'approvazione all\'utilizzo dei cookies', 'Revoke the approval to use cookies', 'en', 'front', '1'),
(2560, 'Ricerca per:', 'Search for:', 'en', 'front', '1'),
(2561, 'richiedi una nuova password', 'request a new password', 'en', 'front', '1'),
(2562, 'richiesta di modifica password', 'password change request', 'en', 'front', '1'),
(2563, 'Richiesta nuova password', 'Password change request', 'en', 'front', '1'),
(2564, 'risultati', 'results', 'en', 'front', '1'),
(2565, 'Scarica documenti', 'Download documents', 'en', 'front', '1'),
(2566, 'Scheda tecnica', 'Data sheet', 'en', 'front', '1'),
(2567, 'Sconti 15% per professionisti', 'Discounts 15% for professionals', 'en', 'front', '1'),
(2568, 'Scopri', 'Discover', 'en', 'front', '1'),
(2569, 'Se ha ricevuto questa e-mail per errore le chiediamo gentilmente di cancellarla', 'If you have received this e-mail in error, we kindly ask you to cancel it', 'en', 'front', '1'),
(2570, 'seguente indirizzo web', 'following web address', 'en', 'front', '1'),
(2571, 'sei qui', 'you are here', 'en', 'front', '1'),
(2572, 'Shop', 'Shop', 'en', 'front', '1'),
(2573, 'Si prega di accettare le condizioni di privacy', 'Please accept the privacy conditions', 'en', 'front', '1'),
(2574, 'Si prega di compilare il campo Nome', 'Please fill in the Name field', 'en', 'front', '1'),
(2575, 'Si prega di controllare che il campo <b>cap</b> contenga solo cifre numeriche', 'Please check that the &lt;b&gt; Postal Code &lt;/b&gt; field contains only numeric digits', 'en', 'front', '1'),
(2576, 'Si prega di controllare che il campo <b>telefono</b> contenga solo cifre numeriche', 'Please check that the &lt;b&gt; phone &lt;/b&gt; field contains only numeric digits', 'en', 'front', '1'),
(2577, 'Si prega di controllare i campi evidenziati', 'Please check the highlighted fields', 'en', 'front', '1'),
(2578, 'Si prega di controllare il campo <b>Codice Fiscale</b>', 'Please check the &lt;b&gt; Tax Code &lt;/b&gt; field', 'en', 'front', '1'),
(2579, 'Si prega di controllare il campo <b>Partita Iva', 'Please check the field &lt;b&gt; VAT number', 'en', 'front', '1'),
(2580, 'Si prega di indicare se siete un privato o un\'azienda', 'Please indicate if you are an individual or a company', 'en', 'front', '1'),
(2581, 'Si prega di indicare una quantità maggiore di zero', 'Please indicate a quantity greater than zero', 'en', 'front', '1'),
(2582, 'Si prega di ricontrollare <b>il Codice Destinatario</b>', 'Please double check the &lt;b&gt; SDI Code &lt;/b&gt;', 'en', 'front', '1'),
(2583, 'Si prega di ricontrollare <b>l\'indirizzo Email</b>', 'Please double-check your &lt;b&gt; email address &lt;/b&gt;', 'en', 'front', '1'),
(2584, 'Si prega di ricontrollare <b>l\'indirizzo Pec</b>', 'Please double check &lt;b&gt; Pec address &lt;/b&gt;', 'en', 'front', '1'),
(2585, 'Si prega di ricontrollare il campo <b>conferma dell\'indirizzo Email</b>', 'Please double check the &lt;b&gt; Email address confirmation &lt;/b&gt; field', 'en', 'front', '1'),
(2586, 'Si prega di ricontrollare l\'indirizzo e-mail', 'Please double check your email address', 'en', 'front', '1'),
(2587, 'Si prega di ricontrollare l\'indirizzo E-Mail', 'Please double check your e-mail address', 'en', 'front', '1'),
(2588, 'Si prega di selezionare la variante del prodotto', 'Please select the product variant', 'en', 'front', '1'),
(2589, 'Si prega di selezionare la variante:', 'Please select the variant:', 'en', 'front', '1'),
(2590, 'Siamo spiacenti, non esiste alcun utente attivo corrispondente all\'email da lei inserita', 'Sorry, there is no active user matching the email you entered', 'en', 'front', '1'),
(2591, 'Solo i seguenti caratteri sono permessi per la password', 'Only the following characters are allowed for the password', 'en', 'front', '1'),
(2592, 'sottotitolo', 'subtitle', 'en', 'front', '1'),
(2594, 'Telefono', 'Phone', 'en', 'front', '1'),
(2595, 'termini e condizioni di vendita', 'terms and conditions of sale', 'en', 'front', '1'),
(2596, 'testo home', 'home text', 'en', 'front', '1'),
(2597, 'testo home in evidenza da modificare', 'highlighted home text to be edited', 'en', 'front', '1'),
(2598, 'Torna alla', 'Go back to', 'en', 'front', '1'),
(2599, 'totale carrello', 'total cart', 'en', 'front', '1'),
(2600, 'Tutte le lettere, maiuscole o minuscole', 'All letters, uppercase or lowercase', 'en', 'front', '1'),
(2601, 'Tutti', 'All', 'en', 'front', '1'),
(2602, 'Tutti i numeri', 'All the numbers', 'en', 'front', '1'),
(2603, 'Ulteriori informazioni', 'Further information', 'en', 'front', '1'),
(2604, 'Username', 'Username', 'en', 'front', '1'),
(2605, 'Vai al', 'Go to', 'en', 'front', '1'),
(2606, 'Cai al carrello', 'Go to cart', 'en', 'front', '1'),
(2607, 'Vai all\'', 'Go to', 'en', 'front', '1'),
(2608, 'Vai alla home', 'Go to home', 'en', 'front', '1'),
(2609, 'VAI ALLA HOME', 'GO TO HOME', 'en', 'front', '1'),
(2610, 'Vecchia password', 'Old password', 'en', 'front', '1'),
(2611, 'Vecchia password sbagliata', 'Old  password wrong', 'en', 'front', '1'),
(2612, 'Vedi l\'informativa sui cookie', 'See the information on cookies', 'en', 'front', '1'),
(2613, 'Very good Design. Flexible. Fast Support.', 'Very good Design. Flexible. Fast Support.', 'en', 'front', '1'),
(2614, 'Via XXX...', 'Via XXX...', 'en', 'front', '1'),
(2615, 'Visualizzazione di tutti i', 'Viewing all', 'en', 'front', '1'),
(2616, 'Voglio essere iscritto alla newsletter per conoscere le promozioni e le novità del negozio', 'I want to be subscribed to the newsletter to find out about the promotions and news of the shop', 'en', 'front', '1'),
(2617, 'wishlist', 'wishlist', 'en', 'front', '1'),
(2618, 'XXXXX', 'XXXXX', 'en', 'front', '1'),
(2619, 'Impostazione nuova password', 'Impostazione nuova password', 'en', 'front', '1'),
(2620, 'Steve John.', 'Steve John.', 'en', 'front', '1'),
(2621, 'Prodotto', 'Product', 'en', 'front', '1'),
(2622, 'Codice', 'Code', 'en', 'front', '1'),
(2623, 'Prezzo (Iva esclusa)', 'Price (VAT excluded)', 'en', 'front', '1'),
(2624, 'Quantità', 'Quantity', 'en', 'front', '1'),
(2625, 'Totale (Iva esclusa)', 'Total (VAT excluded)', 'en', 'front', '1'),
(2626, 'elimina il prodotto dal carrello', 'remove the product from the cart', 'en', 'front', '1'),
(2627, 'Invia codice promozione', 'Send promotion code', 'en', 'front', '1'),
(2628, 'Aggiorna carrello', 'Update cart', 'en', 'front', '1'),
(2629, 'Totali carrello', 'Cart totals', 'en', 'front', '1'),
(2630, 'Totale merce', 'Total goods', 'en', 'front', '1'),
(2631, 'Spese spedizione', 'Shipping costs', 'en', 'front', '1'),
(2632, 'Iva', 'VAT', 'en', 'front', '1'),
(2633, 'Totale ordine', 'Total order', 'en', 'front', '1'),
(2634, 'PROCEDI ALL\'ACQUISTO', 'PROCEED TO PURCHASE', 'en', 'front', '1'),
(2635, 'Prodotto', 'Prodotto', 'it', 'front', '1'),
(2636, 'Codice', 'Codice', 'it', 'front', '1'),
(2637, 'Prezzo (Iva esclusa)', 'Prezzo (Iva esclusa)', 'it', 'front', '1'),
(2638, 'Quantità', 'Quantità', 'it', 'front', '1'),
(2639, 'Totale (Iva esclusa)', 'Totale (Iva esclusa)', 'it', 'front', '1'),
(2640, 'elimina il prodotto dal carrello', 'elimina il prodotto dal carrello', 'it', 'front', '1'),
(2641, 'Invia codice promozione', 'Invia codice promozione', 'it', 'front', '1'),
(2642, 'Aggiorna carrello', 'Aggiorna carrello', 'it', 'front', '1'),
(2643, 'Totali carrello', 'Totali carrello', 'it', 'front', '1'),
(2644, 'Totale merce', 'Totale merce', 'it', 'front', '1'),
(2645, 'Spese spedizione', 'Spese spedizione', 'it', 'front', '1'),
(2646, 'Iva', 'Iva', 'it', 'front', '1'),
(2647, 'Totale ordine', 'Totale ordine', 'it', 'front', '1'),
(2648, 'PROCEDI ALL\'ACQUISTO', 'PROCEDI ALL\'ACQUISTO', 'it', 'front', '1'),
(2649, 'Home', 'Home', 'en', 'front', '1'),
(2650, 'Il tuo Carrello', 'Your cart', 'en', 'front', '1'),
(2651, 'Home', 'Home', 'it', 'front', '1'),
(2652, 'Il tuo Carrello', 'Il tuo Carrello', 'it', 'front', '1'),
(2653, 'Subtotale', 'Subtotale', 'it', 'front', '1'),
(2654, 'VAI AL CARRELLO', 'VAI AL CARRELLO', 'it', 'front', '1'),
(2655, 'CONCLUDI ACQUISTO', 'CONCLUDI ACQUISTO', 'it', 'front', '1'),
(2656, 'CONCLUDI ACQUISTO', 'COMPLETE PURCHASE', 'en', 'front', '1'),
(2657, 'Subtotale', 'Subtotal', 'en', 'front', '1'),
(2658, 'VAI AL CARRELLO', 'GO TO THE CART', 'en', 'front', '1'),
(2659, 'Checkout', 'Checkout', 'it', 'front', '1'),
(2661, 'Hai già un account?', 'Hai già un account?', 'it', 'front', '1'),
(2662, 'Clicca qui per accedere', 'Clicca qui per accedere', 'it', 'front', '1'),
(2663, 'Altrimenti continua pure inserendo i tuoi dati.', 'Altrimenti continua pure inserendo i tuoi dati.', 'it', 'front', '1'),
(2664, 'Possiedi il codice di una promozione attiva?', 'Possiedi il codice di una promozione attiva?', 'it', 'front', '1'),
(2665, 'Aggiungi il tuo codice all\'ordine', 'Aggiungi il tuo codice all\'ordine', 'it', 'front', '1'),
(2666, 'Se hai un codice promozione, inseriscilo sotto.', 'Se hai un codice promozione, inseriscilo sotto.', 'it', 'front', '1'),
(2667, 'Codice promozione', 'Codice promozione', 'it', 'front', '1'),
(2668, 'Dettagli di fatturazione', 'Dettagli di fatturazione', 'it', 'front', '1'),
(2669, 'Metodo di pagamento', 'Metodo di pagamento', 'it', 'front', '1'),
(2670, 'Checkout', 'Checkout', 'en', 'front', '1'),
(2672, 'Hai già un account?', 'Do you already have an account?', 'en', 'front', '1'),
(2673, 'Clicca qui per accedere', 'Click here to log in', 'en', 'front', '1'),
(2674, 'Altrimenti continua pure inserendo i tuoi dati.', 'Otherwise continue by entering your data.', 'en', 'front', '1'),
(2675, 'Possiedi il codice di una promozione attiva?', 'Do you have the code of an active promotion?', 'en', 'front', '1'),
(2676, 'Aggiungi il tuo codice all\'ordine', 'Add your code to the order', 'en', 'front', '1'),
(2677, 'Se hai un codice promozione, inseriscilo sotto.', 'If you have a promotion code, enter it below.', 'en', 'front', '1'),
(2678, 'Codice promozione', 'Promotion code', 'en', 'front', '1'),
(2679, 'Dettagli di fatturazione', 'Billing details', 'en', 'front', '1'),
(2680, 'Metodo di pagamento', 'Payment method', 'en', 'front', '1'),
(2681, 'Steve John.', 'Steve John.', 'it', 'front', '1'),
(2682, 'Il tuo ordine', 'Il tuo ordine', 'it', 'front', '1'),
(2684, 'Il tuo ordine', 'Your Order', 'en', 'front', '1'),
(2686, 'Thumb', 'Thumb', 'it', 'front', '1'),
(2687, 'Thumb', 'Thumb', 'en', 'front', '1'),
(2688, 'Indirizzo di spedizione', 'Indirizzo di spedizione', 'it', 'front', '1'),
(2689, 'Aggiungi un nuovo indirizzo di spedizione', 'Aggiungi un nuovo indirizzo di spedizione', 'it', 'front', '1'),
(2690, 'Seleziona un indirizzo di spedizione esistente', 'Seleziona un indirizzo di spedizione esistente', 'it', 'front', '1'),
(2691, 'Indirizzo di spedizione', 'Indirizzo di spedizione', 'en', 'front', '1'),
(2692, 'Aggiungi un nuovo indirizzo di spedizione', 'Aggiungi un nuovo indirizzo di spedizione', 'en', 'front', '1'),
(2693, 'Seleziona un indirizzo di spedizione esistente', 'Seleziona un indirizzo di spedizione esistente', 'en', 'front', '1'),
(2694, 'Gestisci', 'Gestisci', 'it', 'front', '1'),
(2695, 'Ordine', 'Ordine', 'it', 'front', '1'),
(2696, 'Data', 'Data', 'it', 'front', '1'),
(2697, 'Stato', 'Stato', 'it', 'front', '1'),
(2698, 'Totale (€)', 'Totale (€)', 'it', 'front', '1'),
(2699, 'Fattura', 'Fattura', 'it', 'front', '1'),
(2700, 'Ordine ricevuto', 'Ordine ricevuto', 'it', 'front', '1'),
(2701, 'Ordine pagato e in lavorazione', 'Ordine pagato e in lavorazione', 'it', 'front', '1'),
(2702, 'Ordine completato e spedito', 'Ordine completato e spedito', 'it', 'front', '1'),
(2703, 'Ordine annullato', 'Ordine annullato', 'it', 'front', '1'),
(2716, 'Ordine ricevuto', 'Order received', 'en', 'front', '1'),
(2717, 'Ordine pagato e in lavorazione', 'Order paid and in progress', 'en', 'front', '1'),
(2718, 'Ordine completato e spedito', 'Order completed and shipped', 'en', 'front', '1'),
(2719, 'Ordine annullato', 'Order canceled', 'en', 'front', '1'),
(2720, 'E-commerce', 'E-commerce', 'it', 'back', '1'),
(2721, 'Downloads', 'Downloads', 'it', 'back', '1'),
(2722, 'Data', 'Date', 'en', 'front', '1'),
(2723, 'Fattura', 'Invoice', 'en', 'front', '1'),
(2724, 'Gestisci', 'Manage', 'en', 'front', '1'),
(2725, 'Ordine', 'Order', 'en', 'front', '1'),
(2726, 'Stato', 'Country', 'en', 'front', '1'),
(2727, 'Totale (€)', 'Total (&euro;)', 'en', 'front', '1'),
(2728, 'Coupon', 'Coupon', 'it', 'front', '1'),
(2729, 'Coupon', 'Coupon', 'en', 'front', '1'),
(2730, 'Utilizza gli stessi dati per fatturazione e spedizione', 'Utilizza gli stessi dati per fatturazione e spedizione', 'it', 'front', '1'),
(2731, 'Utilizza dati diversi per la spedizione', 'Utilizza dati diversi per la spedizione', 'it', 'front', '1'),
(2732, 'Utilizza gli stessi dati per fatturazione e spedizione', 'Use the same information for billing and shipping', 'en', 'front', '1'),
(2733, 'Utilizza dati diversi per la spedizione', 'Use different data for shipping', 'en', 'front', '1'),
(2734, '<b>I due indirizzi email non corrispondono</b>', '<b>I due indirizzi email non corrispondono</b>', 'it', 'front', '1'),
(2735, '<b>Si prega di accettare le condizioni di privacy</b>', '<b>Si prega di accettare le condizioni di privacy</b>', 'it', 'front', '1'),
(2736, '<b>Si prega di scegliere la modalità di pagamento</b>', '<b>Si prega di scegliere la modalità di pagamento</b>', 'it', 'front', '1'),
(2738, '<b>L\'indirizzo di spedizione non può superare i 300 caratteri</b>', '<b>L\'indirizzo di spedizione non può superare i 300 caratteri</b>', 'it', 'front', '1'),
(2739, 'Si prega di controllare il campo <b>Partita Iva</b>', 'Si prega di controllare il campo <b>Partita Iva</b>', 'it', 'front', '1'),
(2740, 'Si prega di indicare se volete continuare come utente anonimo oppure creare un account', 'Si prega di indicare se volete continuare come utente anonimo oppure creare un account', 'it', 'front', '1');
INSERT INTO `traduzioni` (`id_t`, `chiave`, `valore`, `lingua`, `contesto`, `tradotta`) VALUES
(2741, 'La sua E-Mail è già presente nel nostro sistema, significa che è già registrato nel nostro sito web.', 'La sua E-Mail è già presente nel nostro sistema, significa che è già registrato nel nostro sito web.', 'it', 'front', '1'),
(2742, 'Può eseguire il login (se non ricorda la password può impostarne una nuova al seguente', 'Può eseguire il login (se non ricorda la password può impostarne una nuova al seguente', 'it', 'front', '1'),
(2743, 'oppure decidere di completare l\'acquisto come utente anonimo', 'oppure decidere di completare l\'acquisto come utente anonimo', 'it', 'front', '1'),
(2744, 'Si prega di controllare i campi segnati in rosso', 'Si prega di controllare i campi segnati in rosso', 'it', 'front', '1'),
(2745, '<b>I due indirizzi email non corrispondono</b>', '&lt;b&gt; The two email addresses do not match &lt;/b&gt;', 'en', 'front', '1'),
(2746, '<b>L\'indirizzo di spedizione non può superare i 300 caratteri</b>', '&lt;b&gt; The shipping address cannot exceed 300 characters &lt;/b&gt;', 'en', 'front', '1'),
(2747, '<b>Si prega di accettare le condizioni di privacy</b>', '&lt;b&gt; Please accept the privacy conditions &lt;/b&gt;', 'en', 'front', '1'),
(2748, '<b>Si prega di scegliere la modalità di pagamento</b>', '&lt;b&gt; Please choose your payment method &lt;/b&gt;', 'en', 'front', '1'),
(2749, 'La sua E-Mail è già presente nel nostro sistema, significa che è già registrato nel nostro sito web.', 'Your e-mail is already in our system, it means that you are already registered on our website.', 'en', 'front', '1'),
(2750, 'oppure decidere di completare l\'acquisto come utente anonimo', 'or decide to complete the purchase as an anonymous user', 'en', 'front', '1'),
(2751, 'Può eseguire il login (se non ricorda la password può impostarne una nuova al seguente', 'He can login (if he does not remember the password he can set a new one to the following', 'en', 'front', '1'),
(2752, 'Si prega di controllare i campi segnati in rosso', 'Please check the fields marked in red', 'en', 'front', '1'),
(2753, 'Si prega di controllare il campo <b>Partita Iva</b>', 'Please check the &lt;b&gt; VAT number &lt;/b&gt; field', 'en', 'front', '1'),
(2754, 'Si prega di indicare se volete continuare come utente anonimo oppure creare un account', 'Please indicate if you want to continue as an anonymous user or create an account', 'en', 'front', '1'),
(2755, 'dati di fatturazione', 'dati di fatturazione', 'it', 'front', '1'),
(2756, 'e i tuoi', 'e i tuoi', 'it', 'front', '1'),
(2757, 'dati di spedizione', 'dati di spedizione', 'it', 'front', '1'),
(2758, 'Totale', 'Totale', 'it', 'front', '1'),
(2759, 'Stato ordine', 'Stato ordine', 'it', 'front', '1'),
(2760, 'Dettagli ordine', 'Dettagli ordine', 'it', 'front', '1'),
(2761, 'Dati di fatturazione', 'Dati di fatturazione', 'it', 'front', '1'),
(2762, 'P. IVA', 'P. IVA', 'it', 'front', '1'),
(2763, 'Dati di spedizione', 'Dati di spedizione', 'it', 'front', '1'),
(2764, 'Salva', 'Salva', 'it', 'front', '1'),
(2765, 'Cookie', 'Cookie', 'it', 'front', '1'),
(2766, 'Cancella account', 'Cancella account', 'it', 'front', '1'),
(2767, 'Inserisci la password', 'Inserisci la password', 'it', 'front', '1'),
(2769, 'Resoconto Ordine', 'Resoconto Ordine', 'it', 'front', '1'),
(2770, 'Cancella account', 'Delete account', 'en', 'front', '1'),
(2771, 'Cookie', 'Cookie', 'en', 'front', '1'),
(2772, 'dati di fatturazione', 'billing information', 'en', 'front', '1'),
(2773, 'Dati di fatturazione', 'Billing information', 'en', 'front', '1'),
(2774, 'dati di spedizione', 'shipping data', 'en', 'front', '1'),
(2775, 'Dati di spedizione', 'Shipping data', 'en', 'front', '1'),
(2776, 'Dettagli ordine', 'Order details', 'en', 'front', '1'),
(2777, 'e i tuoi', 'and yours', 'en', 'front', '1'),
(2778, 'Inserisci la password', 'Enter your password', 'en', 'front', '1'),
(2779, 'P. IVA', 'VAT number', 'en', 'front', '1'),
(2780, 'Resoconto Ordine', 'Order Report', 'en', 'front', '1'),
(2781, 'Salva', 'Save', 'en', 'front', '1'),
(2782, 'Stato ordine', 'Order status', 'en', 'front', '1'),
(2783, 'Totale', 'Total', 'en', 'front', '1'),
(2784, 'Attenzione, password non corretta.', 'Attenzione, password non corretta.', 'it', 'front', '1'),
(2785, 'Approvazione all\'utilizzo di cookies revocata correttamente.', 'Approvazione all\'utilizzo di cookies revocata correttamente.', 'it', 'front', '1'),
(2786, 'Approvazione all\'utilizzo di cookies revocata correttamente.', 'Approval for the use of cookies revoked correctly.', 'en', 'front', '1'),
(2787, 'Attenzione, password non corretta.', 'Warning, incorrect password.', 'en', 'front', '1'),
(2788, 'Wishlist', 'Wishlist', 'en', 'front', '1'),
(2789, 'Non ci sono prodotti nella lista dei desideri', 'There are no products in the wish list', 'en', 'front', '1'),
(2790, 'Torna al negozio', 'back to the shop\n', 'en', 'front', '1'),
(2791, 'Wishlist', 'Wishlist', 'it', 'front', '1'),
(2792, 'Non ci sono prodotti nella lista dei desideri', 'Non ci sono prodotti nella lista dei desideri', 'it', 'front', '1'),
(2793, 'Torna al negozio', 'Torna al negozio', 'it', 'front', '1'),
(2794, 'da', 'da', 'it', 'front', '1'),
(2810, 'Articolo aggiunto!', 'Articolo aggiunto!', 'it', 'front', '1'),
(2826, 'Acquista', 'Acquista', 'it', 'front', '1'),
(2831, 'Aggiung al carrello', 'Aggiung al carrello', 'it', 'front', '1'),
(2843, 'Acquista', 'Buy', 'en', 'front', '1'),
(2844, 'Aggiung al carrello', 'Add to cart\n', 'en', 'front', '1'),
(2845, 'Articolo aggiunto!', 'Article added!', 'en', 'front', '1'),
(2846, 'da', 'from', 'en', 'front', '1'),
(2847, 'Ordine ricevuto', 'Ordine ricevuto', 'it', 'back', '1'),
(2848, 'Ordine pagato e in lavorazione', 'Ordine pagato e in lavorazione', 'it', 'back', '1'),
(2849, 'Ordine completato e spedito', 'Ordine completato e spedito', 'it', 'back', '1'),
(2850, 'Ordine annullato', 'Ordine annullato', 'it', 'back', '1'),
(3072, 'Ordine N° [ID_ORDINE]', 'Ordine N° [ID_ORDINE]', 'it', 'front', '1'),
(3112, 'Conferma pagamento ordine N° [ID_ORDINE]', 'Conferma pagamento ordine N° [ID_ORDINE]', 'it', 'front', '1'),
(3113, 'Gentile cliente, le confermiamo che il pagamento dell\' ordine #', 'Gentile cliente, le confermiamo che il pagamento dell\' ordine #', 'it', 'front', '1'),
(3114, 'del', 'del', 'it', 'front', '1'),
(3115, 'è andato a buon fine e che l\'ordine è entrato in lavorazione', 'è andato a buon fine e che l\'ordine è entrato in lavorazione', 'it', 'front', '1'),
(3117, 'Annullamento ordine N° [ID_ORDINE]', 'Annullamento ordine N° [ID_ORDINE]', 'it', 'front', '1'),
(3118, 'Gentile cliente, l\'ordine #', 'Gentile cliente, l\'ordine #', 'it', 'front', '1'),
(3120, 'è stato annullato', 'è stato annullato', 'it', 'front', '1'),
(3167, 'Annullamento ordine N° [ID_ORDINE]', 'Order N &deg; [ID_ORDINE] cancelled', 'en', 'front', '1'),
(3168, 'Conferma pagamento ordine N° [ID_ORDINE]', 'Order N&deg; [ID_ORDINE] paid', 'en', 'front', '1'),
(3169, 'del', 'of', 'en', 'front', '1'),
(3170, 'è andato a buon fine e che l\'ordine è entrato in lavorazione', 'has been successful and that the order has been processed', 'en', 'front', '1'),
(3171, 'è stato annullato', 'was canceled', 'en', 'front', '1'),
(3172, 'Gentile cliente, l\'ordine #', 'Dear customer, the order #', 'en', 'front', '1'),
(3173, 'Gentile cliente, le confermiamo che il pagamento dell\' ordine #', 'Dear customer, we confirm that the payment of the order #', 'en', 'front', '1'),
(3174, 'Ordine N° [ID_ORDINE]', 'Order N&deg; [ID_ORDINE]', 'en', 'front', '1'),
(3220, 'Ordine N° [ID_ORDINE] spedito e chiuso', 'Order N&deg; [ID_ORDINE] has been shipped and closed', 'en', 'front', '1'),
(3221, 'Gentile cliente, i prodotti acquistati con l\'ordine #', 'Gentile cliente, i prodotti acquistati con l\'ordine #', 'en', 'front', '1'),
(3222, 'sono stati spediti all\'indirizzo indicato', 'have been sent to the address indicated', 'en', 'front', '1'),
(3223, 'Ordine N° [ID_ORDINE] spedito e chiuso', 'Ordine N° [ID_ORDINE] spedito e chiuso', 'it', 'front', '1'),
(3224, 'Gentile cliente, i prodotti acquistati con l\'ordine #', 'Gentile cliente, i prodotti acquistati con l\'ordine #', 'it', 'front', '1'),
(3225, 'sono stati spediti all\'indirizzo indicato', 'sono stati spediti all\'indirizzo indicato', 'it', 'front', '1'),
(3226, 'Invio fattura ordine N° [ID_ORDINE]', 'Invio fattura ordine N° [ID_ORDINE]', 'it', 'front', '0'),
(3227, 'Gentile cliente, in allegato la fattura relativa all\' ordine #', 'Gentile cliente, in allegato la fattura relativa all\' ordine #', 'it', 'front', '0'),
(3228, 'Gentile cliente, in allegato la fattura relativa all\' ordine #', 'Dear customer, attached the invoice for the order #', 'en', 'front', '1'),
(3229, 'Invio fattura ordine N° [ID_ORDINE]', 'Sending invoice order N &deg; [ID_ORDINE]', 'en', 'front', '1'),
(3230, 'Sfoglia la lista dei desideri', 'Sfoglia la lista dei desideri', 'it', 'front', '0'),
(3231, 'Sfoglia la lista dei desideri', 'Browse the wish list', 'en', 'front', '1'),
(3232, 'Condividi', 'Condividi', 'it', 'front', '0'),
(3233, 'Condividi', 'Share', 'en', 'front', '1'),
(3234, 'Non ci sono prodotti nel carrello', 'There are no products in the cart', 'en', 'front', '1'),
(3235, 'Può controllare l\'ordine al', 'You can check the order at', 'en', 'front', '1'),
(3236, 'Può controllare l\'ordine al', 'Può controllare l\'ordine al', 'it', 'front', '0'),
(3237, 'Pagamento online tramite PayPal', 'Pagamento online tramite PayPal/Carta di credito ', 'it', 'front', '1'),
(3238, 'Bonifico bancario', 'Bonifico bancario', 'it', 'front', '0'),
(3239, 'Pagamento online tramite PayPal', 'Online payment via PayPal/Credit Card', 'en', 'front', '1'),
(3240, 'Bonifico bancario', 'Bank transfer', 'en', 'front', '1'),
(3241, 'operazione eseguita!', 'operation performed!', 'en', 'front', '1'),
(3242, 'operazione eseguita!', 'operazione eseguita!', 'it', 'front', '0'),
(3243, 'Bonifico bancario', 'Bonifico bancario', 'it', 'back', '0'),
(3244, 'Non ci sono prodotti nel carrello', 'Non ci sono prodotti nel carrello', 'it', 'front', '0'),
(3245, 'seleziona', 'seleziona', 'it', 'front', '0'),
(3248, 'Non esiste il prodotto con la combinazione di varianti selezionate', 'Non esiste il prodotto con la combinazione di varianti selezionate', 'it', 'front', '0'),
(3249, 'Non esiste il prodotto con la combinazione di varianti selezionate', 'There is no product with the combination of selected variants', 'en', 'front', '1'),
(3250, 'Modifica prodotto nel carrello', 'Modifica prodotto nel carrello', 'it', 'front', '0'),
(3252, 'Aggiungi al carrello', 'Aggiungi al carrello', 'it', 'front', '0'),
(3253, 'Aggiungi al carrello', 'Add to cart', 'en', 'front', '1'),
(3254, 'Modifica prodotto nel carrello', 'Change product in cart', 'en', 'front', '1'),
(3255, 'seleziona', 'select', 'en', 'front', '1'),
(3256, 'Si prega di selezionare la variante', 'Si prega di selezionare la variante', 'it', 'front', '0'),
(3257, 'pezzo rimasto', 'pezzo rimasto', 'it', 'front', '0'),
(3258, 'pezzi rimasti', 'pezzi rimasti', 'it', 'front', '0'),
(3261, 'Da', 'Da', 'it', 'front', '0'),
(3263, 'pezzo rimasto', 'piece left', 'en', 'front', '1'),
(3264, 'pezzi rimasti', 'remaining pieces', 'en', 'front', '1'),
(3265, 'Non hai effettuato alcun ordine', 'Non hai effettuato alcun ordine', 'it', 'front', '0'),
(3266, 'Attenzione, sono rimasti solo [N] prodotti in magazzino', 'Attenzione, sono rimasti solo [N] prodotti in magazzino', 'it', 'front', '0'),
(3267, 'Attenzione, prodotto esaurito', 'Attenzione, prodotto esaurito', 'it', 'front', '0'),
(3268, 'Attenzione, è rimasto un solo prodotto in magazzino', 'Attenzione, è rimasto un solo prodotto in magazzino', 'it', 'front', '0'),
(3269, 'Attenzione, hai già inserito nel carrello tutti i pezzi presenti a magazzino', 'Attenzione, hai già inserito nel carrello tutti i pezzi presenti a magazzino', 'it', 'front', '0'),
(3270, 'Attenzione, controllare la quantità delle righe evidenziate', 'Attenzione, controllare la quantità delle righe evidenziate', 'it', 'front', '0'),
(3271, 'Pagamento online tramite PayPal', 'Pagamento online tramite PayPal', 'it', 'back', '0'),
(3272, 'Attenzione, alcune righe nel tuo carrello hanno una quantità maggiore di quella presente a magazzino.', 'Attenzione, alcune righe nel tuo carrello hanno una quantità maggiore di quella presente a magazzino.', 'it', 'front', '0'),
(3273, 'marchio', 'marchio', 'it', 'back', '0'),
(3274, 'Attenzione, alcune righe nel tuo carrello hanno una quantità maggiore di quella presente a magazzino.', 'Attention, some lines in your cart have a greater quantity than the one in stock.\n', 'en', 'front', '1'),
(3275, 'Attenzione, controllare la quantità delle righe evidenziate', 'Attention, check the quantity of the highlighted lines', 'en', 'front', '1'),
(3276, 'Attenzione, è rimasto un solo prodotto in magazzino', 'Attention, there is only one product left in stock', 'en', 'front', '1'),
(3277, 'Attenzione, hai già inserito nel carrello tutti i pezzi presenti a magazzino', 'Attention, you have already added to the cart all the pieces in stock', 'en', 'front', '1'),
(3278, 'Attenzione, prodotto esaurito', 'Attention, product out of stock', 'en', 'front', '1'),
(3279, 'Attenzione, sono rimasti solo [N] prodotti in magazzino', 'Attention, there are only [N] products left in stock', 'en', 'front', '1'),
(3280, 'Da', 'From', 'en', 'front', '1'),
(3281, 'Non hai effettuato alcun ordine', 'You have not placed any order', 'en', 'front', '1'),
(3282, 'Si prega di selezionare la variante', 'Please select the variant', 'en', 'front', '1'),
(3283, 'Tag / Linee', 'Tag / Linee', 'it', 'back', '0'),
(3285, 'Tag', 'Tag', 'it', 'back', '0'),
(3286, 'Seleziona', 'Seleziona', 'it', 'front', '0'),
(3287, '<b>Si prega di selezionare una nazione tra quelle permesse</b>', '<b>Si prega di selezionare una nazione tra quelle permesse</b>', 'it', 'front', '0'),
(3288, '<b>Si prega di selezionare una nazione di spedizione tra quelle permesse</b>', '<b>Si prega di selezionare una nazione di spedizione tra quelle permesse</b>', 'it', 'front', '0'),
(3289, 'Seleziona il corriere', 'Seleziona il corriere', 'it', 'front', '0'),
(3290, '<b>Si prega di selezionare un corriere tra quelli permessi</b>', '<b>Si prega di selezionare un corriere tra quelli permessi</b>', 'it', 'front', '0'),
(3291, '<b>Non è possibile spedire nella nazione selezionata</b>', '<b>Non è possibile spedire nella nazione selezionata</b>', 'it', 'front', '0'),
(3292, 'Non spedibile ', 'Non spedibile ', 'it', 'front', '0'),
(3293, 'Non spedibile nella nazione scelta', 'Non spedibile nella nazione scelta', 'it', 'front', '0'),
(3294, 'Non spedibile nella nazione selezionata', 'Non spedibile nella nazione selezionata', 'it', 'front', '0'),
(3295, 'Seleziona', 'Seleziona', 'en', 'front', '0');

-- --------------------------------------------------------

--
-- Struttura della tabella `variabili`
--

CREATE TABLE `variabili` (
  `id_v` int(10) UNSIGNED NOT NULL,
  `chiave` varchar(200) CHARACTER SET utf8 NOT NULL,
  `valore` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `variabili`
--

INSERT INTO `variabili` (`id_v`, `chiave`, `valore`) VALUES
(5, 'usa_marchi', '1'),
(7, 'db_version', '81'),
(8, 'contenuti_in_prodotti', '1'),
(9, 'scaglioni_in_prodotti', '1'),
(10, 'correlati_in_prodotti', '1'),
(11, 'caratteristiche_in_prodotti', '1'),
(12, 'combinazioni_in_prodotti', '1'),
(13, 'documenti_in_prodotti', '1'),
(14, 'ecommerce_attivo', '1'),
(15, 'contenuti_in_categorie', '0'),
(16, 'fasce_in_prodotti', '0'),
(17, 'fasce_in_categorie', '0'),
(18, 'mostra_link_in_blog', '0'),
(19, 'has_child_class', 'menu-item-has-children'),
(20, 'attiva_ruoli', '0'),
(21, 'in_evidenza_blog', '0'),
(22, 'contenuti_in_blog', '0'),
(23, 'team_attivo', '1'),
(24, 'immagini_in_referenze', '0'),
(25, 'nome_cognome_anche_azienda', '0'),
(26, 'attiva_gruppi_utenti', '1'),
(27, 'accessori_in_prodotti', '1'),
(28, 'contenuti_in_pagine', '1'),
(29, 'fasce_in_pagine', '1'),
(30, 'mostra_tipi_documento', '1'),
(31, 'download_attivi', '1'),
(32, 'attiva_personalizzazioni', '1'),
(33, 'attiva_giacenza', '1'),
(34, 'usa_tag', '1'),
(35, 'shop_in_alias_marchio', '0'),
(36, 'reg_expr_file', '/^[a-zA-Z0-9_\\-]+\\.(jpg|jpeg|gif|png)$/i'),
(37, 'nazione_default', 'IT'),
(38, 'referenze_attive', '1'),
(39, 'blog_attivo', '1'),
(40, 'divisone_breadcrum', ' &raquo; '),
(41, 'shop_in_alias_tag', '0'),
(42, 'menu_class_prefix', ''),
(43, 'primo_attributo_selezionato', '0'),
(44, 'prodotti_per_pagina', '999999'),
(45, 'template_attributo', ''),
(46, 'template_personalizzazione', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `wishlist`
--

CREATE TABLE `wishlist` (
  `id_wishlist` int(10) UNSIGNED NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `wishlist_uid` char(32) NOT NULL,
  `id_page` int(10) UNSIGNED NOT NULL,
  `creation_time` int(10) UNSIGNED NOT NULL,
  `id_order` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `wishlist`
--

INSERT INTO `wishlist` (`id_wishlist`, `data_creazione`, `wishlist_uid`, `id_page`, `creation_time`, `id_order`) VALUES
(103, '2019-10-28 10:36:25', '55d1d2f3a2210155e468986241c1381f', 360, 1572258985, 4),
(104, '2020-04-16 16:12:00', 'ec90b34e1438f46211761d83a211b2bd', 358, 1587053520, 5),
(105, '2020-06-29 09:57:40', 'e974dc83e488bde46f199d332c4fdfb7', 380, 1593424660, 6),
(109, '2020-06-29 10:14:23', 'e974dc83e488bde46f199d332c4fdfb7', 381, 1593424660, 8),
(112, '2020-08-01 10:25:23', 'ba3b82d85dd5efc5901d630763af0d0a', 350, 1596277523, 11);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `accesses`
--
ALTER TABLE `accesses`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `admingroups`
--
ALTER TABLE `admingroups`
  ADD PRIMARY KEY (`id_group`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indici per le tabelle `adminsessions`
--
ALTER TABLE `adminsessions`
  ADD KEY `uid` (`uid`);

--
-- Indici per le tabelle `adminusers`
--
ALTER TABLE `adminusers`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `username_2` (`username`,`password`);

--
-- Indici per le tabelle `adminusers_groups`
--
ALTER TABLE `adminusers_groups`
  ADD PRIMARY KEY (`id_ug`),
  ADD UNIQUE KEY `id_group` (`id_group`,`id_user`),
  ADD KEY `group_indx` (`id_group`),
  ADD KEY `user_indx` (`id_user`);

--
-- Indici per le tabelle `attributi`
--
ALTER TABLE `attributi`
  ADD PRIMARY KEY (`id_a`);

--
-- Indici per le tabelle `attributi_valori`
--
ALTER TABLE `attributi_valori`
  ADD PRIMARY KEY (`id_av`);

--
-- Indici per le tabelle `caratteristiche`
--
ALTER TABLE `caratteristiche`
  ADD PRIMARY KEY (`id_car`);

--
-- Indici per le tabelle `caratteristiche_valori`
--
ALTER TABLE `caratteristiche_valori`
  ADD PRIMARY KEY (`id_cv`);

--
-- Indici per le tabelle `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id_cart`);

--
-- Indici per le tabelle `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_c`);

--
-- Indici per le tabelle `classi_sconto`
--
ALTER TABLE `classi_sconto`
  ADD PRIMARY KEY (`id_classe`);

--
-- Indici per le tabelle `classi_sconto_categories`
--
ALTER TABLE `classi_sconto_categories`
  ADD PRIMARY KEY (`id_csc`),
  ADD UNIQUE KEY `id_classe` (`id_classe`,`id_c`),
  ADD KEY `classe_indx` (`id_classe`),
  ADD KEY `cat_indx` (`id_c`);

--
-- Indici per le tabelle `combinazioni`
--
ALTER TABLE `combinazioni`
  ADD PRIMARY KEY (`id_c`);

--
-- Indici per le tabelle `contenuti`
--
ALTER TABLE `contenuti`
  ADD PRIMARY KEY (`id_cont`);

--
-- Indici per le tabelle `contenuti_tradotti`
--
ALTER TABLE `contenuti_tradotti`
  ADD PRIMARY KEY (`id_ct`),
  ADD KEY `id_c` (`id_c`),
  ADD KEY `id_page` (`id_page`),
  ADD KEY `id_car` (`id_car`),
  ADD KEY `id_cv` (`id_cv`),
  ADD KEY `id_marchio` (`id_marchio`),
  ADD KEY `id_ruolo` (`id_ruolo`),
  ADD KEY `id_tipo_azienda` (`id_tipo_azienda`),
  ADD KEY `id_a` (`id_a`),
  ADD KEY `id_av` (`id_av`),
  ADD KEY `lingua` (`lingua`),
  ADD KEY `id_pers` (`id_pers`),
  ADD KEY `id_tag` (`id_tag`);

--
-- Indici per le tabelle `corrieri`
--
ALTER TABLE `corrieri`
  ADD PRIMARY KEY (`id_corriere`);

--
-- Indici per le tabelle `corrieri_spese`
--
ALTER TABLE `corrieri_spese`
  ADD PRIMARY KEY (`id_spesa`);

--
-- Indici per le tabelle `documenti`
--
ALTER TABLE `documenti`
  ADD PRIMARY KEY (`id_doc`);

--
-- Indici per le tabelle `fatture`
--
ALTER TABLE `fatture`
  ADD PRIMARY KEY (`id_f`);

--
-- Indici per le tabelle `immagini`
--
ALTER TABLE `immagini`
  ADD PRIMARY KEY (`id_immagine`);

--
-- Indici per le tabelle `impostazioni`
--
ALTER TABLE `impostazioni`
  ADD PRIMARY KEY (`id_imp`);

--
-- Indici per le tabelle `iva`
--
ALTER TABLE `iva`
  ADD PRIMARY KEY (`id_iva`);

--
-- Indici per le tabelle `lingue`
--
ALTER TABLE `lingue`
  ADD PRIMARY KEY (`id_lingua`);

--
-- Indici per le tabelle `mail_ordini`
--
ALTER TABLE `mail_ordini`
  ADD PRIMARY KEY (`id_mail`);

--
-- Indici per le tabelle `marchi`
--
ALTER TABLE `marchi`
  ADD PRIMARY KEY (`id_marchio`);

--
-- Indici per le tabelle `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_m`);

--
-- Indici per le tabelle `menu_sec`
--
ALTER TABLE `menu_sec`
  ADD PRIMARY KEY (`id_m`);

--
-- Indici per le tabelle `nazioni`
--
ALTER TABLE `nazioni`
  ADD PRIMARY KEY (`id_nazione`);

--
-- Indici per le tabelle `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id_n`);

--
-- Indici per le tabelle `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_o`);

--
-- Indici per le tabelle `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id_page`);

--
-- Indici per le tabelle `pages_attributi`
--
ALTER TABLE `pages_attributi`
  ADD PRIMARY KEY (`id_pa`),
  ADD UNIQUE KEY `id_page` (`id_page`,`id_a`),
  ADD KEY `attr_indx` (`id_a`),
  ADD KEY `page_indx` (`id_page`);

--
-- Indici per le tabelle `pages_caratteristiche_valori`
--
ALTER TABLE `pages_caratteristiche_valori`
  ADD PRIMARY KEY (`id_pcv`),
  ADD UNIQUE KEY `id_page` (`id_page`,`id_cv`),
  ADD KEY `attr_indx` (`id_cv`),
  ADD KEY `page_indx` (`id_page`);

--
-- Indici per le tabelle `pages_link`
--
ALTER TABLE `pages_link`
  ADD PRIMARY KEY (`id_page_link`);

--
-- Indici per le tabelle `pages_personalizzazioni`
--
ALTER TABLE `pages_personalizzazioni`
  ADD PRIMARY KEY (`id_pp`),
  ADD UNIQUE KEY `id_page` (`id_page`,`id_pers`),
  ADD KEY `pers_indx` (`id_pers`),
  ADD KEY `page_indx` (`id_page`);

--
-- Indici per le tabelle `pages_tag`
--
ALTER TABLE `pages_tag`
  ADD PRIMARY KEY (`id_pt`),
  ADD UNIQUE KEY `id_page` (`id_page`,`id_tag`),
  ADD KEY `tag_indx` (`id_tag`),
  ADD KEY `page_indx` (`id_page`);

--
-- Indici per le tabelle `personalizzazioni`
--
ALTER TABLE `personalizzazioni`
  ADD PRIMARY KEY (`id_pers`);

--
-- Indici per le tabelle `prodotti_correlati`
--
ALTER TABLE `prodotti_correlati`
  ADD PRIMARY KEY (`id_pc`),
  ADD UNIQUE KEY `id_page` (`id_page`,`id_corr`,`accessorio`) USING BTREE,
  ADD KEY `corr_indx` (`id_corr`),
  ADD KEY `page_indx` (`id_page`);

--
-- Indici per le tabelle `promozioni`
--
ALTER TABLE `promozioni`
  ADD PRIMARY KEY (`id_p`);

--
-- Indici per le tabelle `promozioni_categorie`
--
ALTER TABLE `promozioni_categorie`
  ADD PRIMARY KEY (`id_pc`),
  ADD UNIQUE KEY `id_p` (`id_p`,`id_c`),
  ADD KEY `p_indx` (`id_p`),
  ADD KEY `c_indx` (`id_c`);

--
-- Indici per le tabelle `promozioni_pages`
--
ALTER TABLE `promozioni_pages`
  ADD PRIMARY KEY (`id_pp`),
  ADD UNIQUE KEY `id_p` (`id_p`,`id_page`),
  ADD KEY `p_indx` (`id_p`),
  ADD KEY `page_indx` (`id_page`);

--
-- Indici per le tabelle `province`
--
ALTER TABLE `province`
  ADD PRIMARY KEY (`id_prov`);

--
-- Indici per le tabelle `regaccesses`
--
ALTER TABLE `regaccesses`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `reggroups`
--
ALTER TABLE `reggroups`
  ADD PRIMARY KEY (`id_group`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indici per le tabelle `reggroups_categories`
--
ALTER TABLE `reggroups_categories`
  ADD PRIMARY KEY (`id_gc`),
  ADD UNIQUE KEY `id_group` (`id_group`,`id_c`),
  ADD KEY `group_indx` (`id_group`),
  ADD KEY `cat_indx` (`id_c`);

--
-- Indici per le tabelle `regsessions`
--
ALTER TABLE `regsessions`
  ADD KEY `uid` (`uid`);

--
-- Indici per le tabelle `regusers`
--
ALTER TABLE `regusers`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `username_2` (`username`,`password`);

--
-- Indici per le tabelle `regusers_groups`
--
ALTER TABLE `regusers_groups`
  ADD PRIMARY KEY (`id_ug`),
  ADD UNIQUE KEY `id_group` (`id_group`,`id_user`),
  ADD KEY `group_indx` (`id_group`),
  ADD KEY `user_indx` (`id_user`);

--
-- Indici per le tabelle `righe`
--
ALTER TABLE `righe`
  ADD PRIMARY KEY (`id_r`);

--
-- Indici per le tabelle `ruoli`
--
ALTER TABLE `ruoli`
  ADD PRIMARY KEY (`id_ruolo`);

--
-- Indici per le tabelle `scaglioni`
--
ALTER TABLE `scaglioni`
  ADD PRIMARY KEY (`id_scaglione`);

--
-- Indici per le tabelle `slide_layer`
--
ALTER TABLE `slide_layer`
  ADD PRIMARY KEY (`id_layer`);

--
-- Indici per le tabelle `spedizioni`
--
ALTER TABLE `spedizioni`
  ADD PRIMARY KEY (`id_spedizione`);

--
-- Indici per le tabelle `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id_tag`);

--
-- Indici per le tabelle `testi`
--
ALTER TABLE `testi`
  ADD PRIMARY KEY (`id_t`),
  ADD UNIQUE KEY `chiave` (`chiave`,`lingua`);

--
-- Indici per le tabelle `tipi_azienda`
--
ALTER TABLE `tipi_azienda`
  ADD PRIMARY KEY (`id_tipo_azienda`);

--
-- Indici per le tabelle `tipi_contenuto`
--
ALTER TABLE `tipi_contenuto`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Indici per le tabelle `tipi_documento`
--
ALTER TABLE `tipi_documento`
  ADD PRIMARY KEY (`id_tipo_doc`);

--
-- Indici per le tabelle `traduzioni`
--
ALTER TABLE `traduzioni`
  ADD PRIMARY KEY (`id_t`),
  ADD UNIQUE KEY `chiave` (`chiave`,`lingua`,`contesto`) USING BTREE;

--
-- Indici per le tabelle `variabili`
--
ALTER TABLE `variabili`
  ADD PRIMARY KEY (`id_v`),
  ADD UNIQUE KEY `chiave` (`chiave`);

--
-- Indici per le tabelle `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id_wishlist`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `accesses`
--
ALTER TABLE `accesses`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `admingroups`
--
ALTER TABLE `admingroups`
  MODIFY `id_group` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `adminusers`
--
ALTER TABLE `adminusers`
  MODIFY `id_user` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `adminusers_groups`
--
ALTER TABLE `adminusers_groups`
  MODIFY `id_ug` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `attributi`
--
ALTER TABLE `attributi`
  MODIFY `id_a` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT per la tabella `attributi_valori`
--
ALTER TABLE `attributi_valori`
  MODIFY `id_av` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT per la tabella `caratteristiche`
--
ALTER TABLE `caratteristiche`
  MODIFY `id_car` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `caratteristiche_valori`
--
ALTER TABLE `caratteristiche_valori`
  MODIFY `id_cv` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `cart`
--
ALTER TABLE `cart`
  MODIFY `id_cart` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT per la tabella `categories`
--
ALTER TABLE `categories`
  MODIFY `id_c` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT per la tabella `classi_sconto`
--
ALTER TABLE `classi_sconto`
  MODIFY `id_classe` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `classi_sconto_categories`
--
ALTER TABLE `classi_sconto_categories`
  MODIFY `id_csc` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `combinazioni`
--
ALTER TABLE `combinazioni`
  MODIFY `id_c` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=347;

--
-- AUTO_INCREMENT per la tabella `contenuti`
--
ALTER TABLE `contenuti`
  MODIFY `id_cont` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT per la tabella `contenuti_tradotti`
--
ALTER TABLE `contenuti_tradotti`
  MODIFY `id_ct` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=281;

--
-- AUTO_INCREMENT per la tabella `corrieri`
--
ALTER TABLE `corrieri`
  MODIFY `id_corriere` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT per la tabella `corrieri_spese`
--
ALTER TABLE `corrieri_spese`
  MODIFY `id_spesa` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT per la tabella `documenti`
--
ALTER TABLE `documenti`
  MODIFY `id_doc` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `fatture`
--
ALTER TABLE `fatture`
  MODIFY `id_f` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `immagini`
--
ALTER TABLE `immagini`
  MODIFY `id_immagine` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=554;

--
-- AUTO_INCREMENT per la tabella `impostazioni`
--
ALTER TABLE `impostazioni`
  MODIFY `id_imp` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `iva`
--
ALTER TABLE `iva`
  MODIFY `id_iva` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `lingue`
--
ALTER TABLE `lingue`
  MODIFY `id_lingua` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `mail_ordini`
--
ALTER TABLE `mail_ordini`
  MODIFY `id_mail` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT per la tabella `marchi`
--
ALTER TABLE `marchi`
  MODIFY `id_marchio` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `menu`
--
ALTER TABLE `menu`
  MODIFY `id_m` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT per la tabella `menu_sec`
--
ALTER TABLE `menu_sec`
  MODIFY `id_m` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `nazioni`
--
ALTER TABLE `nazioni`
  MODIFY `id_nazione` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;

--
-- AUTO_INCREMENT per la tabella `news`
--
ALTER TABLE `news`
  MODIFY `id_n` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT per la tabella `orders`
--
ALTER TABLE `orders`
  MODIFY `id_o` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `pages`
--
ALTER TABLE `pages`
  MODIFY `id_page` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=396;

--
-- AUTO_INCREMENT per la tabella `pages_attributi`
--
ALTER TABLE `pages_attributi`
  MODIFY `id_pa` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT per la tabella `pages_caratteristiche_valori`
--
ALTER TABLE `pages_caratteristiche_valori`
  MODIFY `id_pcv` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `pages_link`
--
ALTER TABLE `pages_link`
  MODIFY `id_page_link` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `pages_personalizzazioni`
--
ALTER TABLE `pages_personalizzazioni`
  MODIFY `id_pp` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `pages_tag`
--
ALTER TABLE `pages_tag`
  MODIFY `id_pt` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `personalizzazioni`
--
ALTER TABLE `personalizzazioni`
  MODIFY `id_pers` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `prodotti_correlati`
--
ALTER TABLE `prodotti_correlati`
  MODIFY `id_pc` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT per la tabella `promozioni`
--
ALTER TABLE `promozioni`
  MODIFY `id_p` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `promozioni_categorie`
--
ALTER TABLE `promozioni_categorie`
  MODIFY `id_pc` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `promozioni_pages`
--
ALTER TABLE `promozioni_pages`
  MODIFY `id_pp` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `province`
--
ALTER TABLE `province`
  MODIFY `id_prov` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=551;

--
-- AUTO_INCREMENT per la tabella `regaccesses`
--
ALTER TABLE `regaccesses`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=306;

--
-- AUTO_INCREMENT per la tabella `reggroups`
--
ALTER TABLE `reggroups`
  MODIFY `id_group` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `reggroups_categories`
--
ALTER TABLE `reggroups_categories`
  MODIFY `id_gc` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `regusers`
--
ALTER TABLE `regusers`
  MODIFY `id_user` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `regusers_groups`
--
ALTER TABLE `regusers_groups`
  MODIFY `id_ug` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `righe`
--
ALTER TABLE `righe`
  MODIFY `id_r` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ruoli`
--
ALTER TABLE `ruoli`
  MODIFY `id_ruolo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `scaglioni`
--
ALTER TABLE `scaglioni`
  MODIFY `id_scaglione` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `slide_layer`
--
ALTER TABLE `slide_layer`
  MODIFY `id_layer` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT per la tabella `spedizioni`
--
ALTER TABLE `spedizioni`
  MODIFY `id_spedizione` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `tag`
--
ALTER TABLE `tag`
  MODIFY `id_tag` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `testi`
--
ALTER TABLE `testi`
  MODIFY `id_t` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=227;

--
-- AUTO_INCREMENT per la tabella `tipi_azienda`
--
ALTER TABLE `tipi_azienda`
  MODIFY `id_tipo_azienda` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `tipi_contenuto`
--
ALTER TABLE `tipi_contenuto`
  MODIFY `id_tipo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `tipi_documento`
--
ALTER TABLE `tipi_documento`
  MODIFY `id_tipo_doc` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `traduzioni`
--
ALTER TABLE `traduzioni`
  MODIFY `id_t` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3296;

--
-- AUTO_INCREMENT per la tabella `variabili`
--
ALTER TABLE `variabili`
  MODIFY `id_v` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT per la tabella `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id_wishlist` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `adminusers_groups`
--
ALTER TABLE `adminusers_groups`
  ADD CONSTRAINT `adminusers_groups_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `admingroups` (`id_group`),
  ADD CONSTRAINT `adminusers_groups_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `adminusers` (`id_user`);

--
-- Limiti per la tabella `classi_sconto_categories`
--
ALTER TABLE `classi_sconto_categories`
  ADD CONSTRAINT `classi_sconto_categories_ibfk_1` FOREIGN KEY (`id_classe`) REFERENCES `classi_sconto` (`id_classe`),
  ADD CONSTRAINT `classi_sconto_categories_ibfk_2` FOREIGN KEY (`id_c`) REFERENCES `categories` (`id_c`);

--
-- Limiti per la tabella `pages_attributi`
--
ALTER TABLE `pages_attributi`
  ADD CONSTRAINT `pages_attributi_ibfk_1` FOREIGN KEY (`id_a`) REFERENCES `attributi` (`id_a`),
  ADD CONSTRAINT `pages_attributi_ibfk_2` FOREIGN KEY (`id_page`) REFERENCES `pages` (`id_page`);

--
-- Limiti per la tabella `pages_caratteristiche_valori`
--
ALTER TABLE `pages_caratteristiche_valori`
  ADD CONSTRAINT `pages_caratteristiche_valori_ibfk_1` FOREIGN KEY (`id_cv`) REFERENCES `caratteristiche_valori` (`id_cv`),
  ADD CONSTRAINT `pages_caratteristiche_valori_ibfk_2` FOREIGN KEY (`id_page`) REFERENCES `pages` (`id_page`);

--
-- Limiti per la tabella `prodotti_correlati`
--
ALTER TABLE `prodotti_correlati`
  ADD CONSTRAINT `prodotti_correlati_ibfk_1` FOREIGN KEY (`id_corr`) REFERENCES `pages` (`id_page`),
  ADD CONSTRAINT `prodotti_correlati_ibfk_2` FOREIGN KEY (`id_page`) REFERENCES `pages` (`id_page`);

--
-- Limiti per la tabella `reggroups_categories`
--
ALTER TABLE `reggroups_categories`
  ADD CONSTRAINT `reggroups_categories_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `reggroups` (`id_group`),
  ADD CONSTRAINT `reggroups_categories_ibfk_2` FOREIGN KEY (`id_c`) REFERENCES `categories` (`id_c`);

--
-- Limiti per la tabella `regusers_groups`
--
ALTER TABLE `regusers_groups`
  ADD CONSTRAINT `regusers_groups_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `reggroups` (`id_group`),
  ADD CONSTRAINT `regusers_groups_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `regusers` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
