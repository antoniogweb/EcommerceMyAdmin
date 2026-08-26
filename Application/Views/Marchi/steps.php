<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtextPlain("Dettagli");?></a></li>
	<li <?php echo $posizioni['meta'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/meta/update/$id".$this->viewStatus;?>"><?php echo gtextPlain("Meta");?></a></li>
	<?php if (v("immagini_in_marchi") && ControllersModel::checkAccessoAlController(array("immaginiarchivi"))) { ?>
	<li <?php echo $posizioni['immagini'];?>><a class="help_immagini" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/immagini/$id".$this->viewStatus;?>"><?php echo gtextPlain("Immagini");?></a></li>
	<?php } ?>
	<?php if (v("attiva_documenti_in_marchi")) { ?>
	<li <?php echo $posizioni['documenti'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/documenti/$id".$this->viewStatus;?>"><?php echo gtextPlain("Documenti");?></a></li>
	<?php } ?>
</ul>

<?php } else { ?>

<?php } ?>

<div style="clear:left;"></div>
