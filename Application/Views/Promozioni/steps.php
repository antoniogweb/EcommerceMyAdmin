<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>">Dettagli</a></li>
	<li <?php echo $posizioni['categorie'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/categorie/$id".$this->viewStatus;?>">Categorie</a></li>
	<li <?php echo $posizioni['pagine'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/pagine/$id".$this->viewStatus;?>">Prodotti</a></li>
</ul>

<?php } else { ?>

<?php } ?>

<div style="clear:left;"></div>
