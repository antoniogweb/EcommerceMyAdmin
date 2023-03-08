ALTER TABLE `pages` DROP INDEX `pages_multi_id_page`, ADD INDEX `pages_multi_id_page` (`attivo`, `acquistabile`, `bloccato`, `test`, `temp`, `cestino`) USING BTREE;
