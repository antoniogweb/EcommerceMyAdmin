<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<li class="payment_method_box">
	<?php echo Html_Form::radio("pagamento",$values["pagamento"],$codPag,null,"none");?> <span><?php echo gtext($descPag);?></span>
	
	<div class="payment_box payment_method_bacs" style="display: block;">
	</div>
</li>
