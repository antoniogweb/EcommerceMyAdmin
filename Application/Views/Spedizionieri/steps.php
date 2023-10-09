<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
	<?php if (v("attiva_gestione_spedizioni")) { ?>
	<li <?php echo $posizioni['lettere'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/lettere/$id".$this->viewStatus;?>"><?php echo gtext("Template lettere di vettura");?></a></li>
	<?php } ?>
</ul>

<div style="clear:left;"></div>
<?php } ?>
