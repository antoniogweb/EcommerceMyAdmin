<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
	<?php if (v("riconoscimento_tipo_documento_automatico")) { ?>
	<li <?php echo $posizioni['estensioni'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/estensioni/$id".$this->viewStatus;?>"><?php echo gtext("Estensioni");?></a></li>
	<?php } ?>
</ul>

<?php } else { ?>

<?php } ?>

<div style="clear:left;"></div>
