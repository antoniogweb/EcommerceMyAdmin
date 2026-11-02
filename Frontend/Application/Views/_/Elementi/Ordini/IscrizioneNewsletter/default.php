<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!$islogged && IntegrazioninewsletterModel::integrazioneAttiva()) { ?>
<div class="newsletter_checkbox"><?php echo Html_Form::checkbox("newsletter",$values['newsletter'],"Y",null,"newsletter_checkout");?> <label for="newsletter_checkout"><?php echo gtextPlain("Voglio essere iscritto alla newsletter per conoscere le promozioni e le novità del negozio");?></label></div> 
<?php } ?>
