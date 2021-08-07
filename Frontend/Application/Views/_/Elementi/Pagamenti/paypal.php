<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<li class="payment_method_box">
	<?php echo Html_Form::radio("pagamento",$values["pagamento"],"paypal",null,"none");?> <span><?php echo gtext("Paypal / Carta di credito.");?> <a href="https://www.paypal.com/it/webapps/mpp/paypal-popup" class="about_paypal" onclick="javascript:window.open('https://www.paypal.com/it/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700'); return false; __gaTracker('send', 'event', 'outbound-article', 'https://www.paypal.com/it/webapps/mpp/paypal-popup', 'What is PayPal?');" title="What is PayPal?"><?php echo gtext("Cos'è PayPal?"); ?></a></span>
	<div class="payment_box payment_method_paypal uk-margin" >
		<div class="uk-text-small uk-text-muted"><?php echo testo("Paga con Paypal. Se non disponi di un account Paypal, selenzionando questa opzione, potrai pagare in sicurezza anche con la sola carta di credito.");?></div>
	</div>
</li> 
