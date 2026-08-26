<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (v("attiva_gestiobe_ticket") && RegusersModel::getRedirect() == "ticket") { ?>
<div class="">
	<?php if (strcmp($this->action,"modify") !== 0) { ?>
	<h3><?php echo gtextPlain("Inserisci i tuoi dati e completa la registrazione.");?></h3>
	<?php echo gtextPlain("Una volta concluso il processo di registrazione, verrai automaticamente indirizzato alla sezione assistenza e potrai aprire un ticket per descrivere la tua problematica");?>
	<?php } else { ?>
	<h3><?php echo gtextPlain("Ccompleta la registrazione.");?></h3>
	<?php echo gtextPlain("Una volta completata la registrazione, verrai automaticamente indirizzato alla sezione assistenza e potrai aprire un ticket per descrivere la tua problematica");?>
	<?php } ?>
</div>
<?php } ?>
