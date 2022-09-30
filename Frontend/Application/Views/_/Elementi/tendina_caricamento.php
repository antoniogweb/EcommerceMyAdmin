<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_tendina_caricamento")) { ?>
<div style="z-index:9999;" id="tendina_caricamento" class="uk-hidden uk-position-fixed uk-position-small uk-position-cover uk-overlay uk-overlay-default uk-flex uk-flex-center uk-flex-middle">
	<div uk-spinner></div>
</div>
<?php } ?> 
