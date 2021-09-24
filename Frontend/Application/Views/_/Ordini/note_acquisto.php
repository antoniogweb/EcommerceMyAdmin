<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-margin">
	<h2 class="uk-margin-bottom uk-text-emphasis uk-text-large"><?php echo gtext("Note d'acquisto")?></h2>
	
	<div class="blocco_checkout">
		<?php echo Html_Form::textarea("note",$values["note"],"uk-textarea",null,"placeholder='".gtext("Scrivi qui una eventuale nota al tuo ordine..")."'");?>
	</div>
</div>
