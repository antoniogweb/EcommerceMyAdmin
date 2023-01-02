<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="payment_method_box">
	<?php echo Html_Form::radio("pagamento",$values["pagamento"],$codPag,null,"none");?> <span class="uk-margin-small-left"><?php echo $descPag;?></span>
	
	<?php $immaginePagamento = OrdiniModel::getImmaginePagamento($codPag);
		if ($immaginePagamento) { 
	?>
	<span class="uk-margin-left"><img src="<?php echo $this->baseUrlSrc."/thumb/pagamento/$immaginePagamento";?>" alt="<?php echo altUrlencode($descPag);?>" /></span>
	<?php } ?>
	<div class="payment_box payment_method_bacs uk-margin-small-top" style="display: block;">
		<div class="uk-text-small uk-text-muted">
			<?php echo htmlentitydecode(pfield(OrdiniModel::$pagamentiFull[$codPag],"descrizione"));?>
		</div>
	</div>
</div>
