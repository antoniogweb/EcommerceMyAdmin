<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="uk-container uk-container-small">
	<p class="<?php echo v("alert_success_class");?>"><?php echo gtext("Il tuo account è stato verificato e attivato!");?></p>
	<?php include(tpf("/Elementi/Registrazione/vai_al_login.php")); ?>
</div>
