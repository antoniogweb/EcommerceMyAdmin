<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtextPlain("Dettagli");?></a></li>
	<?php if (v("fasce_in_categorie")) { ?>
		<li <?php echo $posizioni['contenuti'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/contenuti/$id".$this->viewStatus;?>"><?php echo gtextPlain("Fasce");?></a></li>
	<?php } ?>
	<li <?php echo $posizioni['meta'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/meta/$id".$this->viewStatus;?>"><?php echo gtextPlain("Meta");?></a></li>
	<?php if (v("attiva_gruppi_utenti")) { ?>
	<li <?php echo $posizioni['gruppi'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/gruppi/$id".$this->viewStatus;?>"><?php echo gtextPlain("Gruppi");?></a></li>
	<?php } ?>
</ul>

<?php } ?>

<div style="clear:left;"></div>
