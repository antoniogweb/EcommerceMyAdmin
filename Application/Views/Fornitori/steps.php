<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($id) {
	$ordine = OrdiniacquistoModel::g()->selectId((int)$id);
?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtextPlain("Dettagli");?></a></li>
	<li <?php echo $posizioni['contatti'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/contatti/$id".$this->viewStatus;?>"><?php echo gtextPlain("Contatti");?></a></li>
	<li <?php echo $posizioni['listino'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/listino/$id".$this->viewStatus;?>"><?php echo gtextPlain("Listino");?></a></li>
	<li <?php echo $posizioni['import'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/import/$id".$this->viewStatus;?>"><?php echo gtextPlain("Import");?></a></li>
</ul>

<div style="clear:left;"></div>
<?php } ?>
