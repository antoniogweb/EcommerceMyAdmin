<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($id) { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
	<?php if (v("permetti_ordini_offline") && OrdiniModel::tipoOrdine((int)$id) != "W") { ?>
	<li <?php echo $posizioni['righe'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/righe/$id".$this->viewStatus;?>"><?php echo gtext("Righe ordine");?></a></li>
	<?php } ?>
</ul>

<div style="clear:left;"></div>
<?php } ?>
