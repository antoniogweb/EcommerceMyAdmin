<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtextPlain("Dettagli");?></a></li>
	<li <?php echo $posizioni['invii'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/invii/$id".$this->viewStatus;?>"><?php echo gtextPlain("Elenco invii");?></a></li>
</ul>

<?php } ?>

<div style="clear:left;"></div>
