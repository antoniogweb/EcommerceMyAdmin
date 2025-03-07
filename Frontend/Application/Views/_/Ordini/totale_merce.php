<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php foreach ($pages as $p) {
	$prezzoUnitario = p($p["cart"],$p["cart"]["price"]);
	$prezzoUnitarioFisso = p($p["cart"],$p["cart"]["prezzo_fisso"]);
?>
<div class="uk-grid-column-small uk-grid" uk-grid>
	<div class="uk-width-1-3">
		<img src="<?php echo $this->baseUrl."/thumb/carrello/".$p["cart"]["immagine"];?>" />
	</div>
	<div class="uk-margin-remove-top uk-width-2-3">
		<div class="uk-grid-column-small uk-grid" uk-grid>
			<div class="uk-width-2-3 descrizione_prodotto_totali <?php echo v("classe_css_dimensione_testo_colonne_carrello");?>">
				<?php include(tpf("Ordini/totale_merce_elemento.php"));?>
				<?php include(tpf("Cart/main_testo_disponibilita.php"));?>
			</div>
			<div class="uk-margin-remove-top uk-width-1-3 uk-text-right <?php echo v("classe_css_dimensione_testo_colonne_carrello");?>">
				<?php echo setPriceReverse(($p["cart"]["quantity"] * $prezzoUnitario) + $prezzoUnitarioFisso);?> €
			</div>
		</div>
	</div>
</div>
<?php include(tpf("Ordini/totale_elementi.php"));?>
<?php } ?>
<?php include(tpf("Ordini/totale_merce_divisorio.php"));?>
<?php include(tpf("/Ordini/totali.php"));?>

<?php if (v("prezzi_ivati_in_carrello") && isset(IvaModel::$titoloAliquotaEstera) && !IvaModel::$nascondiAliquotaEstera && CartModel::scorporaIvaPrezzoEstero()) { ?>
<div class="uk-grid-small uk-grid" uk-grid="">
	<div class="uk-width-expand uk-text-muted uk-first-column"><?php echo gtext("Di cui IVA")?><br />
	(<?php echo gtext(IvaModel::$titoloAliquotaEstera);?>)</span></div>
	<div><?php echo getIva();?> €</div>
</div>
<?php } ?>

<?php if (v("attiva_spedizione") && isset($_POST["id_corriere"]) && isset($_POST["nazione_spedizione"]) && !spedibile($_POST["id_corriere"], $_POST["nazione_spedizione"])) { ?>
	<div class="uk-text-danger uk-text-bold"><?php echo gtext("Non spedibile nella nazione selezionata");?></div>
<?php } ?>

<?php if (User::$isPhone && !v("piattaforma_di_demo") && v("mostra_doppio_pulsante_acquista_mobile")) {
include(tpf(ElementitemaModel::p("CHECKOUT_PULSANTE_ACQUISTA","", array(
	"titolo"	=>	"Pulsante completa acquisto",
	"percorso"	=>	"Elementi/Ordini/PulsanteCompletaAcquisto",
))));
} ?>
