<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>">Dettagli</a></li>
	<?php if (v("attiva_gruppi_documenti")) { ?>
	<li <?php echo $posizioni['gruppi'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/gruppi/$id".$this->viewStatus;?>">Accessi</a></li>
	<?php } ?>
	<?php $traduz = ContenutitradottiModel::getTraduzioni("id_doc", $id);?>
	<?php foreach ($traduz as $tr) { ?>
	<li <?php if (isset($id_ct) && (int)$tr["id_ct"] === (int)$id_ct) { ?>class="active"<?php } ?>><a href="<?php echo $this->baseUrl."/documenti/traduzione/$id/".$tr["id_ct"].$this->viewStatus;?>"><?php echo strtoupper($tr["lingua"]);?></a></li>
	<?php } ?>
</ul>

<?php } else { ?>

<?php } ?>

<div style="clear:left;"></div>
