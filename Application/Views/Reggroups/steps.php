<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
	<?php if (v("attiva_reggroups_tipi")) { ?>
	<li <?php echo $posizioni['tipi'];?>><a class="help_ordini" href="<?php echo $this->baseUrl."/".$this->controller."/tipi/$id".$this->viewStatus;?>"><?php echo gtext("Accesso contenuti");?></a></li>
	<?php } ?>
</ul>

<?php } else { ?>

<?php } ?>

<div style="clear:left;"></div>
