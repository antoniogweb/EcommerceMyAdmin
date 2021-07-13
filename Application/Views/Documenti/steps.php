<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
	<?php if (v("attiva_gruppi_documenti")) { ?>
	<li <?php echo $posizioni['gruppi'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/gruppi/$id".$this->viewStatus;?>"><?php echo gtext("Accessi");?></a></li>
	<?php } ?>
	<?php $traduz = ContenutitradottiModel::getTraduzioni("id_doc", $id);?>
	<?php foreach ($traduz as $tr) { ?>
	<li <?php if (isset($id_ct) && (int)$tr["id_ct"] === (int)$id_ct) { ?>class="active"<?php } ?>><a href="<?php echo $this->baseUrl."/documenti/traduzione/$id/".$tr["id_ct"].$this->viewStatus;?>"><?php echo strtoupper($tr["lingua"]);?></a></li>
	<?php } ?>
</ul>

<?php } else { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/insert/0".$this->viewStatus;?>"><?php echo gtext("Carica singolo");?></a></li>
	<?php if (v("riconoscimento_tipo_documento_automatico")) { ?>
	<li <?php echo $posizioni['caricamolti'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/caricamolti/0".$this->viewStatus;?>"><?php echo gtext("Carica molti");?></a></li>
	<?php if (extension_loaded("zip") && v("permetti_upload_archivio")) { ?>
	<li <?php echo $posizioni['caricazip'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/caricazip/0".$this->viewStatus;?>"><?php echo gtext("Carica ZIP");?></a></li>
	<?php } ?>
	<?php } ?>
</ul>

<?php } ?>

<div style="clear:left;"></div>
