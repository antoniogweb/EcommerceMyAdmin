<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$prezzoProdotto = $prezzoMinimo;
$prezzoPienoIvato = calcolaPrezzoIvato($p["pages"]["id_page"], $prezzoMinimo);
$prezzoFinaleIvato = calcolaPrezzoFinale($p["pages"]["id_page"], $prezzoMinimo);
$percentualeSconto = getPercSconto($prezzoPienoIvato, $prezzoFinaleIvato);
$inPromozioneTot = inPromozioneTot($p["pages"]["id_page"]);

$strPrezzoFissoIvato = $strPrezzoFissoFinaleIvato = 0;

if (v("attiva_prezzo_fisso"))
{
	$prezzoFissoIvato = calcolaPrezzoIvato($p["pages"]["id_page"],$p["pages"]["prezzo_fisso"]);
	$prezzoFissoFinaleIvato = calcolaPrezzoFinale($p["pages"]["id_page"], $p["pages"]["prezzo_fisso"]);
	
	$strPrezzoFissoIvato = ($prezzoFissoIvato > 0) ? setPriceReverse($prezzoFissoIvato)." + " : "";
	$strPrezzoFissoFinaleIvato = ($prezzoFissoFinaleIvato > 0) ? setPriceReverse($prezzoFissoFinaleIvato)." + " : "";
}
?>
<div class="">
	<?php if ($haVarianti) { ?><div class="blocco-prezzo"><?php } ?>
	<div class="uk-flex uk-flex-left uk-margin-small">
		<div class="uk-text-bold uk-margin-small-right"><span class="price_value"><?php echo $strPrezzoFissoFinaleIvato . setPriceReverse($prezzoFinaleIvato);?></span>€</div>
		<?php if ($percentualeSconto > 0 && $inPromozioneTot) { ?>
		<div class="uk-text-muted uk-margin-small-right" style="text-decoration:line-through;"><span class="price_full"><?php echo $strPrezzoFissoIvato . setPriceReverse($prezzoPienoIvato);?></span>€</div>
		<?php } ?>
		
		<?php if ($percentualeSconto > 0 && $inPromozioneTot) { ?>
		<div class="uk-margin-small-right uk-text-bold uk-text-danger">- <?php echo getPercScontoF($prezzoPienoIvato, $prezzoFinaleIvato);?>%</div>
		<?php } ?>
	</div>
	<?php if ($haVarianti) { ?></div><?php } ?>
	
	<?php if (ImpostazioniModel::$valori["mostra_scritta_iva_inclusa"] == "Y") { ?>
	<span class="uk-text-muted"><?php echo gtext("Iva inclusa");?></span>
	<?php } ?>
	
	<?php if ($percentualeSconto > 0 && $inPromozioneTot) {
		$earlier = new DateTime();
		$later = new DateTime($p["pages"]["al"]);

		$abs_diff = $later->diff($earlier, false)->format("%a") + 2; //3
	?>
	<div class="uk-margin-small uk-padding-remove-vertical uk-text-small uk-text-bold uk-text-danger">
		<?php if ($p["pages"]["al"] == date("Y-m-d")) { ?>
		<?php echo gtext("In promozione solo per oggi!")?>
		<?php } else if ($abs_diff == 2) { ?>
		<?php echo gtext("In promozione fino a domani!")?>
		<?php } else { ?>
		<?php echo gtext("In promozione fino al")?> <?php echo strtolower(traduci(date("j F", strtotime($p["pages"]["al"]))));?>.
		<?php if ($abs_diff <= 6) { ?>
		<br /><?php echo $abs_diff." ".gtext("giorni rimasti!")?>
		<?php } ?>
		<?php } ?>
	</div>
	<?php } ?>
</div>
