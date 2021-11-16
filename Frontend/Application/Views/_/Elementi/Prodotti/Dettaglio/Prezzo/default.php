<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$prezzoProdotto = $prezzoMinimo;
$prezzoPienoIvato = calcolaPrezzoIvato($p["pages"]["id_page"], $prezzoMinimo);
$prezzoFinaleIvato = calcolaPrezzoFinale($p["pages"]["id_page"], $prezzoMinimo);
?>
<div class="">
	<?php if ($haVarianti) { ?><div class="blocco-prezzo"><?php } ?>
	<div class="uk-flex uk-flex-left uk-margin-small">
		<div class="uk-text-bold uk-margin-small-right"><span class="price_value"><?php echo setPriceReverse($prezzoFinaleIvato);?></span>€</div>
		<?php if (inPromozioneTot($p["pages"]["id_page"])) { ?>
		<div class="uk-text-muted uk-margin-small-right" style="text-decoration:line-through;"><span class="price_full"><?php echo setPriceReverse($prezzoPienoIvato);?></span>€</div>
		<?php } ?>
		
		<?php if (getPercSconto($prezzoPienoIvato, $prezzoFinaleIvato) > 0) { ?>
		<div class="uk-margin-small-right uk-text-bold"><?php echo getPercScontoF($prezzoPienoIvato, $prezzoFinaleIvato);?>%</div>
		<?php } ?>
	</div>
	<?php if ($haVarianti) { ?></div><?php } ?>
	
	<?php if (ImpostazioniModel::$valori["mostra_scritta_iva_inclusa"] == "Y") { ?>
	<span class="uk-text-muted"><?php echo gtext("Iva inclusa");?></span>
	<?php } ?>
</div>
