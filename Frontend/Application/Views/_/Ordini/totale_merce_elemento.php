<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<strong><?php echo field($p, "title");?></strong>
<span class="uk-text-small">
	<?php if ($p["cart"]["attributi"]) { echo "<br />".$p["cart"]["attributi"]; } ?>
	<?php if (v("mostra_codice_in_carrello") && $p["cart"]["codice"]) { ?>
		<br /><?php echo gtext("Codice");?>: <?php echo $p["cart"]["codice"];?>
	<?php } ?>
	<br />
	<?php if (v("attiva_prezzo_fisso") && $prezzoUnitarioFisso > 0) { ?>
	<span class="uk-text-bold"><?php echo setPriceReverse($prezzoUnitarioFisso);?> €</span>
	<?php if (strcmp($p["cart"]["in_promozione"],"Y")===0){ echo "<del>".setPriceReverse(p($p["cart"],$p["cart"]["prezzo_fisso_intero"]))." €</del>"; } ?>
	+ 
	<br />
	<?php } ?>
	<span class="uk-text-bold"><?php echo setPriceReverse($prezzoUnitario);?> €</span>
	<?php if (strcmp($p["cart"]["in_promozione"],"Y")===0){ echo "<del>".setPriceReverse(p($p["cart"],$p["cart"]["prezzo_intero"]))." €</del>"; } ?> &times; <?php echo $p["cart"]["quantity"];?>
	<br />
</span>
