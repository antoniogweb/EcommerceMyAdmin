<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php $redirect = trim(RegusersModel::getRedirect(),"/");?>

<?php if (v("attiva_gestiobe_ticket") && $redirect == "ticket") { ?>
<div class="uk-alert-primary uk-alert"><?php echo gtext("Per poter richiedere assistenza, completa i tuoi dati tramite il form sottostante.");?></div>
<?php } ?>
