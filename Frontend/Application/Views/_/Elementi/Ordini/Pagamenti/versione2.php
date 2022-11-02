<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (count(OrdiniModel::$pagamenti) > 1) {
	if (!isset($htmlIcona))
		$htmlIcona = "";
?>
<div class="uk-container blocco_checkout">
	<div id="payment" class="">
		<h2 class="<?php echo v("classi_titoli_checkout");?>">
			<?php echo $htmlIcona;?><?php echo gtext("Metodo di pagamento");?>
		</h2>
		<div class="payment_methods modalita_pagamento class_pagamento">
			<?php foreach (OrdiniModel::$pagamenti as $codPag => $descPag) { ?>
				<div class="radio_pagamento radio_pagamento_select uk-padding-small uk-padding-remove-top uk-padding-remove-bottom <?php if ($values["pagamento"] == $codPag) { ?>spedizione_selezionata<?php } ?>"><?php include(tpf("Elementi/Pagamenti/pagamento_generico.php")); ?></div>
			<?php } ?>
		</div>
	</div>
</div>
<?php } else { ?>
<div class="uk-container uk-margin class_pagamento">
	<?php foreach (OrdiniModel::$pagamenti as $codPag => $descPag) {
		echo Html_Form::hidden("pagamento",$codPag,$codPag);
	} ?>
</div>
<?php } ?>
