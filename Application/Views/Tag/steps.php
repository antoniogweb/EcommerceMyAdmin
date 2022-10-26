<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
	<li <?php echo $posizioni['meta'];?>><a class="help_meta" href="<?php echo $this->baseUrl."/".$this->controller."/meta/update/$id".$this->viewStatus;?>"><?php echo gtext("Meta");?></a></li>
</ul>

<?php } else { ?>

<?php } ?>

<div style="clear:left;"></div>
