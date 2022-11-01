<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!$islogged && ImpostazioniModel::$valori["mailchimp_api_key"] && ImpostazioniModel::$valori["mailchimp_list_id"]) { ?>
<div class="newsletter_checkbox"><?php echo Html_Form::checkbox("newsletter",$values['newsletter'],"Y");?> <?php echo gtext("Voglio essere iscritto alla newsletter per conoscere le promozioni e le novità del negozio");?></div> 
<?php } ?>
