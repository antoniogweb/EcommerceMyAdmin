<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<li class="payment_method_box">
	<?php echo Html_Form::radio("pagamento",$values["pagamento"],$codPag,null,"none");?> <span><?php echo $descPag;?> <a href="https://www.paypal.com/it/webapps/mpp/paypal-popup" class="about_paypal" onclick="javascript:window.open('https://www.paypal.com/it/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700'); return false; __gaTracker('send', 'event', 'outbound-article', 'https://www.paypal.com/it/webapps/mpp/paypal-popup', 'What is PayPal?');" title="What is PayPal?"><?php echo gtext("Cos'è PayPal?"); ?></a></span>
	<div class="payment_box payment_method_paypal uk-margin" >
		<div class="uk-text-small uk-text-muted"><?php echo pfield(OrdiniModel::$pagamentiFull[$codPag],"descrizione");?></div>
	</div>
</li> 
