<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("piattaforma_di_demo")) { ?>
<div class="uk-text-center uk-alert-danger uk-margin-remove" uk-alert>
	<?php echo gtext("Attenzione, questa è una piattaforma di demo.");?>
	<button class="uk-alert-close" type="button" uk-close></button>
</div>
<?php } ?>
