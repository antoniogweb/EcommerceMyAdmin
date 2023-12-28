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
(103, '2019-10-26 11:17:16', '', 'Slide sotto', '', 'slide-sotto', 1, 34, 35, 19, 'slidesotto', '', '', 'Y', '', '', 'Y'),
(109, '2020-07-11 10:05:29', '', 'Referenze', '', 'referenze', 1, 36, 37, 25, 'referenze', '', '', 'Y', '', '', 'Y'),
(110, '2020-07-27 16:04:17', '', 'Team', '', 'team', 1, 38, 39, 26, 'team', '', '', 'Y', '', '', 'Y'),
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
