<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php foreach ($pages as $p) {
	$prezzoUnitario = p($p["cart"],$p["cart"]["price"]);
?>
<div class="uk-grid-column-small uk-child-width-1-3 uk-grid" uk-grid>
	<div>
		<img src="<?php echo $this->baseUrl."/thumb/carrello/".$p["cart"]["immagine"];?>" />
	</div>
	<div class="descrizione_prodotto_totali">
		<strong><?php echo field($p, "title");?></strong>
		<span class="uk-text-small">
			<?php if ($p["cart"]["attributi"]) { echo "<br />".$p["cart"]["attributi"]; } ?>
			<?php if (v("mostra_codice_in_carrello") && $p["cart"]["codice"]) { ?>
				<br /><?php echo gtext("Codice");?>: <?php echo $p["cart"]["codice"];?>
			<?php } ?>
			<br />
			<span class="uk-text-bold"><?php echo setPriceReverse($prezzoUnitario);?> €</span>
			<?php if (strcmp($p["cart"]["in_promozione"],"Y")===0){ echo "<del>".setPriceReverse(p($p["cart"],$p["cart"]["prezzo_intero"]))." €</del>"; } ?> &times; <?php echo $p["cart"]["quantity"];?>
			<br />
		</span>
	</div>
	<div class="uk-text-right">
		<?php echo setPriceReverse($p["cart"]["quantity"] * $prezzoUnitario);?> €
	</div>
</div>
<?php include(tpf("Ordini/totale_elementi.php"));?>
<?php } ?>
<hr />
<?php include(tpf("/Ordini/totali.php"));?>

<?php if (v("prezzi_ivati_in_carrello") && isset(IvaModel::$titoloAliquotaEstera) && !IvaModel::$nascondiAliquotaEstera) { ?>
<div class="uk-grid-small uk-grid" uk-grid="">
	<div class="uk-width-expand uk-text-muted uk-first-column"><?php echo gtext("Di cui IVA")?><br />
	(<?php echo IvaModel::$titoloAliquotaEstera;?>)</span></div>
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
