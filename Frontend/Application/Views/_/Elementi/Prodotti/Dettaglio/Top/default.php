<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$urlAlias = getUrlAlias($p["pages"]["id_page"]);
$urlAliasCategoria = getCategoryUrlAlias($p["categories"]["id_c"]);

$titoloPagina = "";
$sottotitoloPagina = "";
$noNumeroProdotti = $noTitolo = $noContainer = true;

include(tpf("/Elementi/Pagine/page_top.php"));?>
<div class="uk-margin-large-bottom uk-position-relative">
	<div id="<?php echo v("fragmento_dettaglio_prodotto");?>" style="position:absolute;top:-100px;"></div>
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
