<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action === "paginecorrelate") { ?>

	<?php foreach ($tabSezioni as $section => $titleSection) {
			if ($this->viewArgs["pcorr_sec"] != $section)
				continue;
	?>
		<p>
			<a class="btn btn-primary iframe pull-right" href="<?php echo $this->baseUrl."/$section/main?partial=Y"?>"><i class="fa fa-edit"></i> <?php echo gtext("Gestione");?> <?php echo gtext($titleSection);?></a>

			<a class="btn btn-success iframe" href="<?php echo $this->baseUrl."/$section/main?id_pcorr=$id_page&partial=Y&cl_on_sv=Y&pcorr_sec=$section";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a>
		</p>
	<?php } ?>

<?php } ?>

