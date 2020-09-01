-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Lug 27, 2020 alle 14:41
-- Versione del server: 5.7.30-0ubuntu0.18.04.1
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
-- Database: `brofer`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `nazioni`
--

CREATE TABLE `nazioni` (
  `id_nazione` int(11) NOT NULL,
  `titolo` varchar(255) NOT NULL DEFAULT '',
  `iso_country_code` varchar(6) NOT NULL,
  `flag_abilitato` int(11) NOT NULL DEFAULT '1',
  `idtipo` tinyint(4) DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `nazioni`
--

INSERT INTO `nazioni` (`id_nazione`, `titolo`, `iso_country_code`, `flag_abilitato`, `idtipo`) VALUES
(166, 'Italia', 'IT', 1, 1),
(659, 'Afghanistan', 'AF', 1, 2),
(661, 'Albania', 'AL', 1, 2),
(662, 'Algeria', 'DZ', 1, 2),
(663, 'American Samoa', 'AS', 1, 2),
(664, 'Andorra', 'AD', 1, 2),
(665, 'Angola', 'AO', 1, 2),
(666, 'Anguilla', 'AI', 1, 2),
(667, 'Antarctica', 'AQ', 1, 2),
(668, 'Antigua and Barbuda', 'AG', 1, 2),
(669, 'Argentina', 'AR', 1, 2),
(670, 'Armenia', 'AM', 1, 2),
(671, 'Aruba', 'AW', 1, 2),
(672, 'Australia', 'AU', 1, 2),
(673, 'Austria', 'AT', 1, 1),
(674, 'Azerbaijan', 'AZ', 1, 2),
(675, 'Bahamas', 'BS', 1, 2),
(676, 'Bahrain', 'BH', 1, 2),
(677, 'Bangladesh', 'BD', 1, 2),
(678, 'Barbados', 'BB', 1, 2),
(679, 'Belarus', 'BY', 1, 2),
(680, 'Belgium', 'BE', 1, 1),
(681, 'Belize', 'BZ', 1, 2),
(682, 'Benin', 'BJ', 1, 2),
(683, 'Bermuda', 'BM', 1, 2),
(684, 'Bhutan', 'BT', 1, 2),
(685, 'Bolivia', 'BO', 1, 2),
(686, 'Bonaire, Sint Eustatius and Saba', 'BQ', 1, 2),
(687, 'Bosnia and Herzegovina', 'BA', 1, 2),
(688, 'Botswana', 'BW', 1, 2),
(689, 'Brazil', 'BR', 1, 2),
(690, 'British Indian Ocean Territory', 'IO', 1, 2),
(691, 'Brunei Darussalam', 'BN', 1, 2),
(692, 'Bulgaria', 'BG', 1, 1),
(693, 'Burkina Faso', 'BF', 1, 2),
(694, 'Burundi', 'BI', 1, 2),
(695, 'Cambodia', 'KH', 1, 2),
(696, 'Cameroon', 'CM', 1, 2),
(697, 'Canada', 'CA', 1, 2),
(698, 'Cape Verde', 'CV', 1, 2),
(699, 'Cayman Islands', 'KY', 1, 2),
(700, 'Central African Republic', 'CF', 1, 2),
(701, 'Chad', 'TD', 1, 2),
(702, 'Chile', 'CL', 1, 2),
(703, 'China', 'CN', 1, 2),
(704, 'Christmas Island', 'CX', 1, 2),
(705, 'Cocos (Keeling) Islands', 'CC', 1, 2),
(706, 'Colombia', 'CO', 1, 2),
(707, 'Comoros', 'KM', 1, 2),
(708, 'Congo', 'CG', 1, 2),
(709, 'Congo, The Democratic Republic of the', 'CD', 1, 2),
(710, 'Cook Islands', 'CK', 1, 2),
(711, 'Costa Rica', 'CR', 1, 2),
(712, 'CÃ´te d\'Ivoire', 'CI', 1, 2),
(713, 'Croatia', 'HR', 1, 1),
(714, 'Cuba', 'CU', 1, 2),
(715, 'CuraÃ§ao', 'CW', 1, 2),
(716, 'Cyprus', 'CY', 1, 1),
(717, 'Czech Republic', 'CZ', 1, 1),
(718, 'Denmark', 'DK', 1, 1),
(719, 'Djibouti', 'DJ', 1, 2),
(720, 'Dominica', 'DM', 1, 2),
(721, 'Dominican Republic', 'DO', 1, 2),
(722, 'Ecuador', 'EC', 1, 2),
(723, 'Egypt', 'EG', 1, 2),
(724, 'El Salvador', 'SV', 1, 2),
(725, 'Equatorial Guinea', 'GQ', 1, 2),
(726, 'Eritrea', 'ER', 1, 2),
(727, 'Estonia', 'EE', 1, 1),
(728, 'Ethiopia', 'ET', 1, 2),
(729, 'Falkland Islands (Malvinas)', 'FK', 1, 2),
(730, 'Faroe Islands', 'FO', 1, 2),
(731, 'Fiji', 'FJ', 1, 2),
(732, 'Finland', 'FI', 1, 1),
(733, 'France', 'FR', 1, 1),
(734, 'French Guiana', 'GF', 1, 2),
(735, 'French Polynesia', 'PF', 1, 2),
(736, 'French Southern Territories', 'TF', 1, 2),
(737, 'Gabon', 'GA', 1, 2),
(738, 'Gambia', 'GM', 1, 2),
(739, 'Georgia', 'GE', 1, 2),
(740, 'Germania', 'DE', 1, 1),
(741, 'Ghana', 'GH', 1, 2),
(742, 'Gibraltar', 'GI', 1, 2),
(743, 'Greece', 'GR', 1, 1),
(744, 'Greenland', 'GL', 1, 2),
(745, 'Grenada', 'GD', 1, 2),
(746, 'Guadeloupe', 'GP', 1, 2),
(747, 'Guam', 'GU', 1, 2),
(748, 'Guatemala', 'GT', 1, 2),
(749, 'Guernsey', 'GG', 1, 2),
(750, 'Guinea', 'GN', 1, 2),
(751, 'Guinea-Bissau', 'GW', 1, 2),
(752, 'Guyana', 'GY', 1, 2),
(753, 'Haiti', 'HT', 1, 2),
(754, 'Heard Island and McDonald Islands', 'HM', 1, 2),
(755, 'CittÃ  del vaticano', 'VA', 1, 2),
(756, 'Honduras', 'HN', 1, 2),
(757, 'Hong Kong', 'HK', 1, 2),
(758, 'Hungary', 'HU', 1, 1),
(759, 'Iceland', 'IS', 1, 2),
(760, 'India', 'IN', 1, 2),
(761, 'Indonesia', 'ID', 1, 2),
(762, 'Installations in International Waters', 'XZ', 1, 2),
(763, 'Iran, Islamic Republic of', 'IR', 1, 2),
(764, 'Iraq', 'IQ', 1, 2),
(765, 'Ireland', 'IE', 1, 1),
(766, 'Isle of Man', 'IM', 1, 2),
(767, 'Israel', 'IL', 1, 2),
(768, 'Jamaica', 'JM', 1, 2),
(769, 'Japan', 'JP', 1, 2),
(770, 'Jersey', 'JE', 1, 2),
(771, 'Jordan', 'JO', 1, 2),
(772, 'Kazakhstan', 'KZ', 1, 2),
(773, 'Kenya', 'KE', 1, 2),
(774, 'Kiribati', 'KI', 1, 2),
(775, 'Korea, Democratic People\'s Republic of', 'KP', 1, 2),
(776, 'Korea, Republic of', 'KR', 1, 2),
(777, 'Kuwait', 'KW', 1, 2),
(778, 'Kyrgyzstan', 'KG', 1, 2),
(779, 'Lao People\'s Democratic Republic', 'LA', 1, 2),
(780, 'Latvia', 'LV', 1, 1),
(781, 'Lebanon', 'LB', 1, 2),
(782, 'Lesotho', 'LS', 1, 2),
(783, 'Liberia', 'LR', 1, 2),
(784, 'Libya', 'LY', 1, 2),
(785, 'Liechtenstein', 'LI', 1, 2),
(786, 'Lithuania', 'LT', 1, 1),
(787, 'Luxembourg', 'LU', 1, 1),
(788, 'Macao', 'MO', 1, 2),
(789, 'Macedonia, The former Yugoslav Republic of', 'MK', 1, 2),
(790, 'Madagascar', 'MG', 1, 2),
(791, 'Malawi', 'MW', 1, 2),
(792, 'Malaysia', 'MY', 1, 2),
(793, 'Maldives', 'MV', 1, 2),
(794, 'Mali', 'ML', 1, 2),
(795, 'Malta', 'MT', 1, 1),
(796, 'Marshall Islands', 'MH', 1, 2),
(797, 'Martinique', 'MQ', 1, 2),
(798, 'Mauritania', 'MR', 1, 2),
(799, 'Mauritius', 'MU', 1, 2),
(800, 'Mayotte', 'YT', 1, 2),
(801, 'Mexico', 'MX', 1, 2),
(802, 'Micronesia, Federated States of', 'FM', 1, 2),
(803, 'Moldavia', 'MD', 1, 2),
(804, 'Monaco', 'MC', 1, 2),
(805, 'Mongolia', 'MN', 1, 2),
(806, 'Montenegro', 'ME', 1, 2),
(807, 'Montserrat', 'MS', 1, 2),
(808, 'Morocco', 'MA', 1, 2),
(809, 'Mozambique', 'MZ', 1, 2),
(810, 'Myanmar', 'MM', 1, 2),
(811, 'Namibia', 'NA', 1, 2),
(812, 'Nauru', 'NR', 1, 2),
(813, 'Nepal', 'NP', 1, 2),
(814, 'Netherlands', 'NL', 1, 1),
(815, 'New Caledonia', 'NC', 1, 2),
(816, 'New Zealand', 'NZ', 1, 2),
(817, 'Nicaragua', 'NI', 1, 2),
(818, 'Niger', 'NE', 1, 2),
(819, 'Nigeria', 'NG', 1, 2),
(820, 'Niue', 'NU', 1, 2),
(821, 'Norfolk Island', 'NF', 1, 2),
(822, 'Northern Mariana Islands', 'MP', 1, 2),
(823, 'Norway', 'NO', 1, 2),
(824, 'Oman', 'OM', 1, 2),
(825, 'Pakistan', 'PK', 1, 2),
(826, 'Palau', 'PW', 1, 2),
(827, 'Palestine, State of', 'PS', 1, 2),
(828, 'Panama', 'PA', 1, 2),
(829, 'Papua New Guinea', 'PG', 1, 2),
(830, 'Paraguay', 'PY', 1, 2),
(831, 'Peru', 'PE', 1, 2),
(832, 'Philippines', 'PH', 1, 2),
(833, 'Pitcairn', 'PN', 1, 2),
(834, 'Poland', 'PL', 1, 1),
(835, 'Portugal', 'PT', 1, 1),
(836, 'Puerto Rico', 'PR', 1, 2),
(837, 'Qatar', 'QA', 1, 2),
(838, 'Reunion', 'RE', 1, 2),
(839, 'Romania', 'RO', 1, 1),
(840, 'Russian Federation', 'RU', 1, 2),
(841, 'Rwanda', 'RW', 1, 2),
(842, 'Saint BarthÃ©lemy', 'BL', 1, 2),
(843, 'Saint Helena, Ascension and Tristan Da Cunha', 'SH', 1, 2),
(844, 'Saint Kitts and Nevis', 'KN', 1, 2),
(845, 'Saint Lucia', 'LC', 1, 2),
(846, 'Saint Martin (French Part)', 'MF', 1, 2),
(847, 'Saint Pierre and Miquelon', 'PM', 1, 2),
(848, 'Saint Vincent and the Grenadines', 'VC', 1, 2),
(849, 'Samoa', 'WS', 1, 2),
(850, 'San Marino', 'SM', 1, 2),
(851, 'Sao Tome and Principe', 'ST', 1, 2),
(852, 'Saudi Arabia', 'SA', 1, 2),
(853, 'Senegal', 'SN', 1, 2),
(854, 'Serbia', 'RS', 1, 2),
(855, 'Seychelles', 'SC', 1, 2),
(856, 'Sierra Leone', 'SL', 1, 2),
(857, 'Singapore', 'SG', 1, 2),
(858, 'Sint Maarten (Dutch Part)', 'SX', 1, 2),
(859, 'Slovakia', 'SK', 1, 1),
(860, 'Slovenia', 'SI', 1, 1),
(861, 'Solomon Islands', 'SB', 1, 2),
(862, 'Somalia', 'SO', 1, 2),
(863, 'South Africa', 'ZA', 1, 2),
(864, 'South Georgia and the South Sandwich Islands', 'GS', 1, 2),
(865, 'South Sudan', 'SS', 1, 2),
(866, 'Spain', 'ES', 1, 1),
(867, 'Sri Lanka', 'LK', 1, 2),
(868, 'Sudan', 'SD', 1, 2),
(869, 'Suriname', 'SR', 1, 2),
(870, 'Svalbard and Jan Mayen', 'SJ', 1, 2),
(871, 'Swaziland', 'SZ', 1, 2),
(872, 'Sweden', 'SE', 1, 1),
(873, 'Switzerland', 'CH', 1, 2),
(874, 'Syrian Arab Republic', 'SY', 1, 2),
(875, 'Taiwan, Province of China', 'TW', 1, 2),
(876, 'Tajikistan', 'TJ', 1, 2),
(877, 'Tanzania, United Republic of', 'TZ', 1, 2),
(878, 'Thailand', 'TH', 1, 2),
(879, 'Timor-Leste', 'TL', 1, 2),
(880, 'Togo', 'TG', 1, 2),
(881, 'Tokelau', 'TK', 1, 2),
(882, 'Tonga', 'TO', 1, 2),
(883, 'Trinidad and Tobago', 'TT', 1, 2),
(884, 'Tunisia', 'TN', 1, 2),
(885, 'Turkey', 'TR', 1, 2),
(886, 'Turkmenistan', 'TM', 1, 2),
(887, 'Turks and Caicos Islands', 'TC', 1, 2),
(888, 'Tuvalu', 'TV', 1, 2),
(889, 'Uganda', 'UG', 1, 2),
(890, 'Ukraine', 'UA', 1, 2),
(891, 'United Arab Emirates', 'AE', 1, 2),
(892, 'Regno Unito', 'GB', 1, 2),
(893, 'United States', 'US', 1, 2),
(894, 'United States Minor Outlying Islands', 'UM', 1, 2),
(895, 'Uruguay', 'UY', 1, 2),
(896, 'Uzbekistan', 'UZ', 1, 2),
(897, 'Vanuatu', 'VU', 1, 2),
(898, 'Venezuela', 'VE', 1, 2),
(899, 'Viet Nam', 'VN', 1, 2),
(900, 'Virgin Islands, British', 'VG', 1, 2),
(901, 'Virgin Islands, U.S.', 'VI', 1, 2),
(902, 'Wallis and Futuna', 'WF', 1, 2),
(903, 'Western Sahara', 'EH', 1, 2),
(904, 'Yemen', 'YE', 1, 2),
(905, 'Zambia', 'ZM', 1, 2),
(906, 'Zimbabwe', 'ZW', 1, 2);

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
  MODIFY `id_nazione` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=907;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
