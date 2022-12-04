ALTER TABLE `mail_ordini` CHANGE `tipo` `tipo` ENUM('F','P','C','A','R','G') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'A';
