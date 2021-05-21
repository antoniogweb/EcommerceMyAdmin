<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php foreach ($pages as $p) {
$urlAlias = getUrlAlias($p["pages"]["id_page"]);
$urlAliasCategoria = getCategoryUrlAlias($p["categories"]["id_c"]);

$titoloPagina = field($p, "title");
$noNumeroProdotti = $noTitolo = $noContainer = true;

include(tpf("/Elementi/Pagine/page_top.php"));?>
<div class="uk-margin-large-bottom">
	<div class="uk-container">
		<div class="uk-grid-large uk-grid uk-text-left" uk-grid="">
			<div class="uk-width-1-1 uk-width-expand@m uk-first-column">
				<div class="detail_left">
					<?php include(tpf("/Elementi/Pagine/slide_prodotto.php"));?>
				</div>
			</div>
			<div class="uk-width-1-1 tm-aside-column uk-width-1-2@m">
				<?php include(tpf("/Elementi/Pagine/carrello_prodotto.php"));?>
			</div>
		</div>
	</div>
</div>
<?php include(tpf("/Elementi/Pagine/dettagli_pagina.php"));?>
<?php include(tpf("/Elementi/Pagine/prodotti_correlati.php"));?>
<?php include(tpf("/Elementi/Pagine/page_bottom.php"));?>
<?php } ?>
