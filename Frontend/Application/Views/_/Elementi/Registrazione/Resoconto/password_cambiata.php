<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="uk-container uk-container-small">
	<p class="<?php echo v("alert_success_class");?>"><?php echo gtext("La password è stata correttamente impostata");?>.</p>
	<?php include(tpf("/Elementi/Registrazione/vai_al_login.php")); ?>
</div>
