<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
	<li <?php echo $posizioni['meta'];?>><a class="help_meta" href="<?php echo $this->baseUrl."/".$this->controller."/meta/update/$id".$this->viewStatus;?>"><?php echo gtext("Meta");?></a></li>
	<?php if (v("immagini_in_tag") && ControllersModel::checkAccessoAlController(array("immaginiarchivi"))) { ?>
	<li <?php echo $posizioni['immagini'];?>><a class="help_immagini" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/immagini/$id".$this->viewStatus;?>"><?php echo gtext("Immagini");?></a></li>
	<?php } ?>
</ul>

<?php } else { ?>

<?php } ?>

<div style="clear:left;"></div>
