<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (v("attiva_gestiobe_ticket") && RegusersModel::getRedirect() == "ticket") { ?>
<div class="uk-alert-primary uk-alert"><?php echo gtext("Per poter richiedere assistenza tramite il sito web, esegui il login oppurte registrati.");?></div>
<?php } ?>

