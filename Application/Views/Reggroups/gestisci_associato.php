<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "tipi") { ?>

<div class="callout callout-info">
	<?php echo gtext("In questa sezione si possono aggiungere tipologie di contenuti e di documenti ad un gruppo di utenti.") ?>
	<br /><?php echo gtext("Tutti i contenuti o i documenti della tipologia aggiunta saranno accessibili agli utenti del gruppo in questione.");?>
	<br /><b><?php echo gtext("Se non viene aggiunta nessuna tipologia di contenuti o documenti a nessun gruppo di utenti, tutti i contenuti e tutti i documenti del sito saranno accessibili a tutti.") ?></b>
</div>

<p>
	<a class="iframe btn btn-info" href="<?php echo $this->baseUrl."/tipicontenuto/main";?>?partial=Y&nobuttons=Y&tipo=GENERICO&id_group=<?php echo $id;?>&nofiltri=Y"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi tipologia contenuto");?></a>
	
	<a class="iframe btn btn-primary" href="<?php echo $this->baseUrl."/tipidocumento/main";?>?partial=Y&nobuttons=Y&id_group=<?php echo $id;?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi tipologia documento");?></a>
</p>

<?php } ?>
