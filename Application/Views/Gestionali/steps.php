<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
	<?php if (true) { ?>
	<li <?php echo $posizioni['opzioni'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/opzioni/$id".$this->viewStatus;?>"><?php echo gtext("Opzioni gestionale");?></a></li>
	<?php } ?>
</ul>

<?php } else { ?>

<?php } ?>

<div style="clear:left;"></div>
