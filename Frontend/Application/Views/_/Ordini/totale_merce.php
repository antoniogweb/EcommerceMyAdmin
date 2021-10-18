<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php foreach ($pages as $p) {
	$prezzoUnitario = p($p["cart"],$p["cart"]["price"]);
?>
<div class="uk-grid-column-small uk-child-width-1-3" uk-grid>
	<div>
		<img src="<?php echo $this->baseUrl."/thumb/carrello/".$p["cart"]["immagine"];?>" />
	</div>
	<div>
		<strong><?php echo field($p, "title");?></strong>
		<?php if ($p["cart"]["attributi"]) { echo "<br />".$p["cart"]["attributi"]; } ?>
		<?php if ($p["cart"]["codice"]) { ?>
			<br /><?php echo gtext("Codice");?>: <?php echo $p["cart"]["codice"];?>
		<?php } ?>
		<br />
		<?php echo setPriceReverse($prezzoUnitario);?> €
		<?php if (strcmp($p["cart"]["in_promozione"],"Y")===0){ echo "<del>".setPriceReverse(p($p["cart"],$p["cart"]["prezzo_intero"]))." €</del>"; } ?> &times; <?php echo $p["cart"]["quantity"];?>
		<br />
		
	</div>
	<div class="uk-text-right">
		<?php echo setPriceReverse($p["cart"]["quantity"] * $prezzoUnitario);?> €
	</div>
</div>
<?php } ?>
<hr />
<?php include(tpf("/Ordini/totali.php"));?>

<?php if (v("prezzi_ivati_in_carrello") && isset(IvaModel::$titoloAliquotaEstera)) { ?>
<div class="uk-grid-small uk-grid" uk-grid="">
	<div class="uk-width-expand uk-text-muted uk-first-column"><?php echo gtext("Di cui IVA")?><br />
	(<?php echo IvaModel::$titoloAliquotaEstera;?>)</span></div>
	<div><?php echo getIva();?> €</div>
</div>
<?php } ?>


<?php if (v("attiva_spedizione") && isset($_POST["id_corriere"]) && isset($_POST["nazione_spedizione"]) && !spedibile($_POST["id_corriere"], $_POST["nazione_spedizione"])) { ?>
	<div class="uk-text-danger uk-text-bold"><?php echo gtext("Non spedibile nella nazione selezionata");?></div>
<?php } ?>
