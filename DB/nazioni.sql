-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Set 26, 2020 alle 11:30
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

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `nazioni`
--
ALTER TABLE `nazioni`
  ADD PRIMARY KEY (`id_nazione`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `nazioni`
--
ALTER TABLE `nazioni`
  MODIFY `id_nazione` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
