<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="payment_method_box">
	<?php echo Html_Form::radio("pagamento",$values["pagamento"],$codPag,null,"none");?> <span class="uk-margin-small-left"><?php echo $descPag;?></span>
	
	<div class="payment_box payment_method_bacs uk-margin-small-top" style="display: block;">
		<div class="uk-text-small uk-text-muted">
			<?php echo htmlentitydecode(pfield(OrdiniModel::$pagamentiFull[$codPag],"descrizione"));?>
		</div>
	</div>
</div>
