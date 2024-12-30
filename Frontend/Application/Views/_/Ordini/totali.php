<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$costoPagamento = setPrice(getPagamento(v("prezzi_ivati_in_carrello")));
$scrittaFinaleTotale = "Totale ordine";
$haCouponAttivo = hasActiveCoupon();
if ($haCouponAttivo)
	$couponAttivo = PromozioniModel::getCouponAttivo();

$numeroEuroCrediti = 0;

if (v("attiva_crediti"))
	$numeroEuroCrediti = CreditiModel::gNumeroEuroRimasti(User::$id);

if (!isset($classeLabelTotali))
	$classeLabelTotali = "uk-text-muted";

if (!isset($classeLabelTotaleOrdine))
	$classeLabelTotaleOrdine = "uk-text-muted";
?>
<?php if (v("attiva_spedizione") || $haCouponAttivo || $costoPagamento > 0 || $numeroEuroCrediti > 0) { ?>
<div class="uk-grid-small uk-grid" uk-grid="">
	<div class="uk-width-expand <?php echo $classeLabelTotali;?> uk-first-column"><?php echo gtext("Totale merce");?></div>
	<div class="uk-width-1-3 uk-text-right uk-margin-remove-top"><?php echo getSubTotal(v("prezzi_ivati_in_carrello"));?> €</div>
</div>
<?php } ?>
<?php if ($haCouponAttivo && $couponAttivo["tipo_sconto"] == "PERCENTUALE") { ?>
<div class="uk-grid-small uk-grid" uk-grid="">
	<div class="uk-width-expand <?php echo $classeLabelTotali;?> uk-first-column">
		<?php echo gtext("Totale scontato");?> (<i><?php echo $couponAttivo["titolo"];?></i>)
		<?php include(tpf("/Ordini/totale_promo_attiva.php"));?>
	</div>
	<div class="uk-width-1-3 uk-text-right uk-margin-remove-top"><?php echo setPriceReverse(getPrezzoScontatoN(false, v("prezzi_ivati_in_carrello"), false, false));?> €</div>
</div>
<?php } ?>
<?php if ($costoPagamento > 0) { ?>
<div class="uk-grid-small uk-grid" uk-grid="">
	<div class="uk-width-expand <?php echo $classeLabelTotali;?> uk-first-column"><?php echo gtext("Spese pagamento");?></div>
	<div class="uk-width-1-3 uk-text-right uk-margin-remove-top"><?php echo setPriceReverse($costoPagamento);?> €</div>
</div>
<?php } ?>
<?php if (v("attiva_spedizione")) { ?>
<div class="uk-grid-small uk-grid" uk-grid="">
	<div class="uk-width-expand <?php echo $classeLabelTotali;?> uk-first-column"><?php echo gtext("Spese spedizione");?></div>
	<div class="uk-width-1-3 uk-text-right uk-margin-remove-top"><?php echo getSpedizione(v("prezzi_ivati_in_carrello"));?> €</div>
</div>
<?php } ?>
<?php if (!v("prezzi_ivati_in_carrello")) { ?>
<div class="uk-grid-small uk-grid" uk-grid="">
	<div class="uk-width-expand <?php echo $classeLabelTotali;?> uk-first-column">
		<?php echo gtext("Iva");?>
		<?php if (isset(IvaModel::$titoloAliquotaEstera) && !IvaModel::$nascondiAliquotaEstera) { ?>
		<br />
		(<?php echo IvaModel::$titoloAliquotaEstera;?>)</span>
		<?php } ?>
	</div>
	<div class="uk-width-1-3 uk-text-right uk-margin-remove-top"><?php echo getIva();?> €</div>
</div>
<?php } ?>

<?php if (v("attiva_spedizione") || $haCouponAttivo || $costoPagamento > 0 || $numeroEuroCrediti > 0) { ?><hr><?php } ?>

<?php if (($haCouponAttivo && $couponAttivo["tipo_sconto"] == "ASSOLUTO") || $numeroEuroCrediti > 0) {
	$scrittaFinaleTotale = "Totale da pagare";
?>
<div class="uk-grid-small uk-flex-middle uk-grid" uk-grid="">
	<div class="uk-width-expand <?php echo $classeLabelTotaleOrdine;?> uk-first-column"><?php echo gtext("Totale ordine");?></div>
	<div class="uk-text-lead uk-text-bolder"><?php echo setPriceReverse(getPrezzoScontatoN(true,1,false,false,false));?> €</div>
</div>
<?php } ?>

<?php if ($numeroEuroCrediti > 0) {
	$scrittaFinaleTotale = "Totale da pagare";
?>
<div class="uk-grid-small uk-grid" uk-grid="">
	<div class="uk-width-expand <?php echo $classeLabelTotali;?> uk-first-column">
		<?php echo gtext("Sconto crediti");?>
		<div class="uk-text-small uk-text-primary">
		<?php echo gtext("Crediti utilizzabili");?>: <b><?php echo setPriceReverse($numeroEuroCrediti);?> €</b>
		</div>
	</div>
	<div class="uk-margin-remove-top"><?php echo setPriceReverse(getPrezzoScontatoN(true,1,false,true,false) - getPrezzoScontatoN(true,1,false,false,false));?> €</div>
</div>
<?php } ?>

<?php if ($haCouponAttivo && $couponAttivo["tipo_sconto"] == "ASSOLUTO") {
// 	$scrittaFinaleTotale = "Totale da pagare";
?>
<!--<div class="uk-grid-small uk-flex-middle uk-grid" uk-grid="">
	<div class="uk-width-expand <?php echo $classeLabelTotali;?> uk-first-column"><?php echo gtext("Totale ordine");?></div>
	<div class="uk-text-lead uk-text-bolder"><?php echo getTotal(true);?> €</div>
</div>-->
<div class="uk-grid-small uk-grid" uk-grid="">
	<div class="uk-width-expand <?php echo $classeLabelTotali;?> uk-first-column">
		<?php echo gtext("Sconto coupon");?><br />(<i><?php echo $couponAttivo["titolo"];?></i>)
		<?php include(tpf("/Ordini/totale_promo_attiva.php"));?>
		<div class="uk-text-small uk-text-primary">
		<?php echo gtext("Credito utilizzabile");?>: <b><?php echo setPriceReverse(PromozioniModel::gNumeroEuroRimasti($couponAttivo["id_p"]));?> €</b>
		</div>
	</div>
	<div class="uk-margin-remove-top"><?php echo setPriceReverse(getPrezzoScontatoN(true,1,false,true,true) - getPrezzoScontatoN(true,1,false,true,false));?> €</div>
</div>
<?php } ?>

<div class="uk-grid-small uk-flex-middle uk-grid" uk-grid="">
	<div class="uk-width-expand <?php echo $classeLabelTotaleOrdine;?> uk-first-column"><?php echo gtext($scrittaFinaleTotale);?></div>
	<div class="uk-text-lead uk-text-bolder"><span class="totale_ordine"><?php echo getTotal();?></span> €</div>
</div>

<?php if ($haCouponAttivo) { ?><span id="ha-coupon-attivo"></span><?php } ?>
