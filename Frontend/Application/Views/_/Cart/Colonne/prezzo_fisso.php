<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php echo setPriceReverse($prezzoUnitarioFisso);?> €
<?php if (strcmp($p["cart"]["in_promozione"],"Y")===0 && $p["cart"]["prezzo_fisso_intero"] > 0){ echo "<del class='uk-text-small uk-text-muted'>".setPriceReverse(p($p["cart"],$p["cart"]["prezzo_fisso_intero"]))." €</del>"; } ?>
<?php if (!v("prezzi_ivati_in_carrello")) { ?>
<div class="uk-text-meta"><?php echo gtext("Iva")?>: <?php echo setPriceReverse($p["cart"]["iva"]);?>%</div>
<?php } ?>
