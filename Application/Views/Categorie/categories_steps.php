<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>">Dettagli</a></li>
	<?php if (v("fasce_in_categorie")) { ?>
		<li <?php echo $posizioni['contenuti'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/contenuti/$id".$this->viewStatus;?>">Fasce</a></li>
	<?php } ?>
	<li <?php echo $posizioni['meta'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/meta/$id".$this->viewStatus;?>">Meta</a></li>
	<?php if (v("ecommerce_attivo")) { ?>
		<li <?php echo $posizioni['classisconto'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/classisconto/$id".$this->viewStatus;?>">Classi sconto applicate</a></li>
	<?php } ?>
	<!--   <li <?php echo $posizioni['gruppi'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/gruppi/$id".$this->viewStatus;?>">Accessibilità</a></li> -->
</ul>


<?php } else { ?>



<?php } ?>

<div style="clear:left;"></div>
