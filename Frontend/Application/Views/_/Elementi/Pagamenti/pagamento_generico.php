<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<li class="payment_method_box">
	<label><?php echo Html_Form::radio("pagamento",$values["pagamento"],$codPag,null,"none");?> <span class="uk-margin-left"><?php echo $descPag;?></span></label>
	
	<div class="payment_box payment_method_bacs uk-margin" style="display: block;">
		<div class="uk-text-small uk-text-muted">
			<?php echo htmlentitydecode(pfield(OrdiniModel::$pagamentiFull[$codPag],"descrizione"));?>
		</div>
	</div>
</li>
