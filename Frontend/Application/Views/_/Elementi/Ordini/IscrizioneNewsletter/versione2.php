<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!$islogged && IntegrazioninewsletterModel::integrazioneAttiva()) { ?>
<div class="newsletter_checkbox uk-text-small uk-width-1-1 uk-margin-bottom">
	<div class="uk-flex uk-flex-top">
		<div>
			<?php echo Html_Form::checkbox("newsletter",$values['newsletter'],"Y");?>
		</div>
		<div class="uk-margin-left uk-text-small">
			<?php echo gtext("Voglio essere iscritto alla newsletter per conoscere le promozioni e le novità del negozio");?>
		</div>
	</div>
</div>
<?php } ?>
