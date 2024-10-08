<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<ul class="timeline">
<?php foreach ($messaggi as $m) {
	$ruolo = $m["ruolo"] == "user" ? UsersModel::getName($m["id_admin"]) : "Assistente";
?>
<li>
	<?php if ($m["ruolo"] != "user") { ?>
		<i class="fa fa-user bg-<?php if ($m["risultato_richiesta"]) { ?>gray<?php } else { ?>red<?php } ?>"></i>
		<div class="timeline-item">
			<span class="time"><i class="fa fa-clock-o"></i> <?php echo date("d-m-Y H:i", strtotime($m["data_creazione"]));?></span>
			<h3 class="timeline-header bg-<?php if ($m["risultato_richiesta"]) { ?>gray<?php } else { ?>red<?php } ?>"><b><?php echo $ruolo;?></b> <span style="cursor:pointer;" class="badge copia_testo_chat"><i class="fa fa-copy"></i> copia</span></h3>
			<div class="timeline-body">
				<?php echo htmlentitydecode(nl2br(attivaModuli($m["messaggio"])));?>
			</div>
			<div style="display:none;" class="risposta_assistente">
				<?php echo htmlentitydecode(nl2br($m["messaggio"]));?>
			</div>
		</div>
	<?php } else { ?>
		<i class="fa fa-user bg-aqua"></i>
		<div class="timeline-item">
			<span class="time"><i class="fa fa-clock-o"></i> <?php echo date("d-m-Y H:i", strtotime($m["data_creazione"]));?></span>
			<h3 class="timeline-header bg-aqua"><b><?php echo $ruolo;?></b></h3>
			<div class="timeline-body">
				<?php echo nl2br($m["messaggio"]);?>
			</div>
		</div>
	<?php } ?>
</li>
<?php } ?>
<li>
	<i class="fa fa-user bg-aqua"></i>
	<div class="timeline-item">
		<div class="timeline-body">
			<?php echo Html_Form::textarea("messaggio", AirichiestemessaggiModel::g()->getMessaggioDefault($id), "form-control testo_nuovo_messaggio_ai", null, "rows='4' placeholder='".gtext("Scrivi qui la tua richiesta..")."'");?>

			<button id-richiesta="<?php echo $id;?>" style="margin-top:10px;" class="btn btn-success btn-block invia_nuovo_messaggio_ai">
				<i class="fa fa-send"></i>
				<?php echo gtext("Invia");?>
			</button>
		</div>
	</div>
</li>
</ul>
