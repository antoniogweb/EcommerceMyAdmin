<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php $haCouponAttivo = hasActiveCoupon();?>
<?php if (v("attiva_spedizione") || $haCouponAttivo) { ?>
<div class="uk-grid-small uk-grid" uk-grid="">
	<div class="uk-width-expand uk-text-muted uk-first-column"><?php echo gtext("Totale merce");?></div>
	<div><?php echo getSubTotal(v("prezzi_ivati_in_carrello"));?> €</div>
</div>
<?php } ?>
<?php if ($haCouponAttivo) {
	$couponAttivo = PromozioniModel::getCouponAttivo();
?>
<div class="uk-grid-small uk-grid" uk-grid="">
	<div class="uk-width-expand uk-text-muted uk-first-column"><?php echo gtext("Totale scontato");?> (<i><?php echo $couponAttivo["titolo"];?></i>)
	<?php if ($couponAttivo["tipo_sconto"] == "ASSOLUTO") { ?>
	<div class="uk-text-small uk-text-primary">
	<?php echo gtext("Credito coupon");?>: <b><?php echo PromozioniModel::gNumeroEuroRimasti($couponAttivo["id_p"]);?> €</b>
	</div>
	<?php } ?>
	</div>
	<div><?php echo getPrezzoScontato(v("prezzi_ivati_in_carrello"));?> €</div>
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

<?php if (v("attiva_spedizione") || $haCouponAttivo) { ?><hr><?php } ?>
<div class="uk-grid-small uk-flex-middle uk-grid" uk-grid="">
	<div class="uk-width-expand uk-text-muted uk-first-column"><?php echo gtext("Totale ordine");?></div>
	<div class="uk-text-lead uk-text-bolder"><?php echo getTotal();?> €</div>
</div>
