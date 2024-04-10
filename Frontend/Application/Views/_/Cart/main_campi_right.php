<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-flex uk-flex-middle uk-flex-center uk-grid-small uk-child-width-1-1 uk-child-width-expand@m uk-text-center@m uk-text-left uk-grid" uk-grid="">
	<div class="uk-first-column descrizione_prodotto_carrello <?php echo v("classe_css_dimensione_testo_colonne_carrello");?>">
		<?php include(tpf("Cart/Colonne/descrizione.php"));?>
	</div>
	<?php if (v("mostra_codice_in_carrello")) { ?>
	<div class="<?php echo v("classe_css_dimensione_testo_colonne_carrello");?>">
		<?php include(tpf("Cart/Colonne/codice.php"));?>
	</div>
	<?php } ?>
	<?php if (v("attiva_prezzo_fisso")) { ?>
	<div class="uk-visible@m <?php echo v("classe_css_dimensione_testo_colonne_carrello");?>">
		<?php include(tpf("Cart/Colonne/prezzo_fisso.php"));?>
	</div>
	<?php } ?>
	<div class="uk-visible@m <?php echo v("classe_css_dimensione_testo_colonne_carrello");?>">
		<?php include(tpf("Cart/Colonne/prezzo.php"));?>
	</div>
	<div class="<?php echo v("classe_css_dimensione_testo_colonne_carrello");?>">
		<?php include(tpf("Cart/Colonne/quantita.php"));?>
	</div>
	<div class="<?php echo v("classe_css_dimensione_testo_colonne_carrello");?>">
		<?php include(tpf("Cart/Colonne/prezzo_totale_riga.php"));?>
	</div>
	<div class="uk-visible@m">
		<?php include(tpf("Cart/Colonne/elimina.php"));?>
	</div>
</div>
