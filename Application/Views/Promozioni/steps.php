<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>">Dettagli</a></li>
	<?php if (isset($record["tipo_sconto"]) && $record["tipo_sconto"] == "PERCENTUALE") { ?>
	<li <?php echo $posizioni['categorie'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/categorie/$id".$this->viewStatus;?>">Categorie</a></li>
	<li <?php echo $posizioni['pagine'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/pagine/$id".$this->viewStatus;?>">Prodotti</a></li>
	<?php } else { ?>
		<?php if (isset($record["id_r"]) && $record["id_r"]) { ?>
		<li <?php echo $posizioni['invii'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/invii/$id".$this->viewStatus;?>"><?php echo gtext("Mail con dedica");?></a></li>
		<?php } ?>
	<?php } ?>
</ul>

<?php } else { ?>

<?php } ?>

<div style="clear:left;"></div>
