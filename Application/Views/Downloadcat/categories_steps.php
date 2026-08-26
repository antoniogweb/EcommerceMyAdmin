<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
  <li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtextPlain("Dettagli");?></a></li>
  <?php if (v("attiva_accessibilita_categorie")) { ?>
	<li <?php echo $posizioni['gruppi'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/gruppi/$id".$this->viewStatus;?>"><?php echo gtextPlain("Accessibilità");?></a></li>
	<?php } ?>
</ul>


<?php } else { ?>



<?php } ?>

<div style="clear:left;"></div>
