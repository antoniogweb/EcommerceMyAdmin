<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$costoPagamento = setPrice(getPagamento(v("prezzi_ivati_in_carrello")));
$scrittaFinaleTotale = "Totale ordine";
$haCouponAttivo = hasActiveCoupon();
if ($haCouponAttivo)
	$couponAttivo = PromozioniModel::getCouponAttivo();
?>
<?php if (v("attiva_spedizione") || $haCouponAttivo || $costoPagamento > 0) { ?>
<div class="uk-grid-small uk-grid" uk-grid="">
	<div class="uk-width-expand uk-text-muted uk-first-column"><?php echo gtext("Totale merce");?></div>
	<div><?php echo getSubTotal(v("prezzi_ivati_in_carrello"));?> €</div>
</div>
<?php } ?>
<?php if ($haCouponAttivo && $couponAttivo["tipo_sconto"] == "PERCENTUALE") { ?>
<div class="uk-grid-small uk-grid" uk-grid="">
	<div class="uk-width-expand uk-text-muted uk-first-column">
		<?php echo gtext("Totale scontato");?> (<i><?php echo $couponAttivo["titolo"];?></i>)
		<?php include(tpf("/Ordini/totale_promo_attiva.php"));?>
	</div>
	<div><?php echo getPrezzoScontato(v("prezzi_ivati_in_carrello"));?> €</div>
</div>
<?php } ?>
<?php if ($costoPagamento > 0) { ?>
<div class="uk-grid-small uk-grid" uk-grid="">
	<div class="uk-width-expand uk-text-muted uk-first-column"><?php echo gtext("Spese pagamento");?></div>
	<div><?php echo setPriceReverse($costoPagamento);?> €</div>
</div>
<?php } ?>
<?php if (v("attiva_spedizione")) { ?>
<div class="uk-grid-small uk-grid" uk-grid="">
	<div class="uk-width-expand uk-text-muted uk-first-column"><?php echo gtext("Spese spedizione");?></div>
	<div><?php echo getSpedizione(v("prezzi_ivati_in_carrello"));?> €</div>
</div>
<?php } ?>
<?php if (!v("prezzi_ivati_in_carrello")) { ?>
<div class="uk-grid-small uk-grid" uk-grid="">
	<div class="uk-width-expand uk-text-muted uk-first-column"><?php echo gtext("Iva");?></div>
	<div><?php echo getIva();?> €</div>
</div>
<?php } ?>

<?php if (v("attiva_spedizione") || $haCouponAttivo || $costoPagamento > 0) { ?><hr><?php } ?>

<?php if ($haCouponAttivo && $couponAttivo["tipo_sconto"] == "ASSOLUTO") {
	$scrittaFinaleTotale = "Totale da pagare";
?>
<div class="uk-grid-small uk-flex-middle uk-grid" uk-grid="">
	<div class="uk-width-expand uk-text-muted uk-first-column"><?php echo gtext("Totale ordine");?></div>
	<div class="uk-text-lead uk-text-bolder"><?php echo getTotal(true);?> €</div>
</div>
<div class="uk-grid-small uk-grid" uk-grid="">
	<div class="uk-width-expand uk-text-muted uk-first-column">
		<?php echo gtext("Sconto coupon");?><br />(<i><?php echo $couponAttivo["titolo"];?></i>)
		<?php include(tpf("/Ordini/totale_promo_attiva.php"));?>
		<div class="uk-text-small uk-text-primary">
		<?php echo gtext("Credito rimanente");?>: <b><?php echo setPriceReverse(PromozioniModel::gNumeroEuroRimasti($couponAttivo["id_p"]));?> €</b>
		</div>
	</div>
	<div><?php echo setPriceReverse(getTotalN() - getTotalN(true));?> €</div>
</div>
<?php } ?>

<div class="uk-grid-small uk-flex-middle uk-grid" uk-grid="">
	<div class="uk-width-expand uk-text-muted uk-first-column"><?php echo gtext($scrittaFinaleTotale);?></div>
	<div class="uk-text-lead uk-text-bolder"><?php echo getTotal();?> €</div>
</div>
