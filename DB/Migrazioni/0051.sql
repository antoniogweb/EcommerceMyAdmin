ALTER TABLE `prodotti_correlati` DROP INDEX `id_page`, ADD UNIQUE `id_page` (`id_page`, `id_corr`, `accessorio`) USING BTREE;
