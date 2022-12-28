<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
	<?php if ($this->action == "variabili" || $record["variabili_gestibili"]) { ?>
	<li <?php echo $posizioni['variabili'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/variabili/$id".$this->viewStatus;?>"><?php echo gtext("Opzioni gestionale");?></a></li>
	<?php } ?>
</ul>

<?php } else { ?>

<?php } ?>

<div style="clear:left;"></div>
