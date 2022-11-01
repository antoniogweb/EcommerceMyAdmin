<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (count(OrdiniModel::$pagamenti) > 1) {
	if (!isset($htmlIcona))
		$htmlIcona = "";
?>
<div class="uk-container">
	<div id="payment" class="">
		<h2 class="<?php echo v("classi_titoli_checkout");?>">
			<?php echo $htmlIcona;?><?php echo gtext("Metodo di pagamento");?>
		</h2>
		<ul class="uk-list payment_methods modalita_pagamento class_pagamento">
			<?php foreach (OrdiniModel::$pagamenti as $codPag => $descPag) {
				if (file_exists(tpf("Elementi/Pagamenti/$codPag.php")))
					include(tpf("Elementi/Pagamenti/$codPag.php"));
				else
					include(tpf("Elementi/Pagamenti/pagamento_generico.php"));
			} ?>
		</ul>
	</div>
</div>
<?php } else { ?>
<div class="uk-container uk-margin class_pagamento">
	<?php foreach (OrdiniModel::$pagamenti as $codPag => $descPag) {
		echo Html_Form::hidden("pagamento",$codPag,$codPag);
	} ?>
</div>
<?php } ?>
