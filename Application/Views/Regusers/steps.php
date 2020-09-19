<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>">Dettagli</a></li>
	<li <?php echo $posizioni['spedizioni'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/spedizioni/$id".$this->viewStatus;?>">Indirizzi di spedizione</a></li>
	<?php if (v("ecommerce_attivo")) { ?>
	<li <?php echo $posizioni['ordini'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/ordini/$id".$this->viewStatus;?>">Ordini</a></li>
	<?php } ?>
	<?php if (v("attiva_gruppi_utenti")) { ?>
	<li <?php echo $posizioni['gruppi'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/gruppi/$id".$this->viewStatus;?>">Gruppi</a></li>
	<?php } ?>
</ul>

<?php } else { ?>

<?php } ?>

<div style="clear:left;"></div>
