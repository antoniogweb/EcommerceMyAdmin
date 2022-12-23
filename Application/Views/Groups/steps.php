<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
	<?php if (v("attiva_gruppi_admin")) { ?>
	<li <?php echo $posizioni['controllers'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/controllers/$id".$this->viewStatus;?>"><?php echo gtext("Sezioni");?></a></li>
	<?php } ?>
</ul>

<?php } else { ?>

<?php } ?>

<div style="clear:left;"></div>
