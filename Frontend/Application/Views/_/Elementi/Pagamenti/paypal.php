<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<li class="payment_method_box">
	<?php echo Html_Form::radio("pagamento",$values["pagamento"],$codPag,null,"none");?> <span class="uk-margin-small-left"><?php echo $descPag;?></span>
	
	<span class="uk-margin-small-left uk-text-meta"><a href="https://www.paypal.com/it/webapps/mpp/paypal-popup" class="about_paypal" onclick="javascript:window.open('https://www.paypal.com/it/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700'); return false; __gaTracker('send', 'event', 'outbound-article', 'https://www.paypal.com/it/webapps/mpp/paypal-popup', 'What is PayPal?');" title="What is PayPal?"><?php echo gtext("Cos'è PayPal?"); ?></a></span>
	
	<?php $immaginePagamento = OrdiniModel::getImmaginePagamento($codPag);
		if ($immaginePagamento) { 
	?>
	<span class="uk-margin-left"><img src="<?php echo $this->baseUrlSrc."/thumb/pagamento/$immaginePagamento";?>" alt="<?php echo altUrlencode($descPag);?>" /></span>
	<?php } ?>
	<div class="payment_box payment_method_paypal uk-margin" >
		<div class="uk-text-small uk-text-muted"><?php echo pfield(OrdiniModel::$pagamentiFull[$codPag],"descrizione");?></div>
	</div>
</li> 
