<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (IntegrazioniModel::getElencoIntegrazioni($this->controller)) { ?>
<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/vedi/$id".$this->viewStatus;?>">Dettagli</a></li>
	<li <?php echo $posizioni['integrazioni'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/integrazioni/$id".$this->viewStatus;?>">Invii a piattaforme esterne</a></li>
</ul>

<div style="clear:left;"></div>
<?php } ?>
