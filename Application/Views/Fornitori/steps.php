<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($id) {
	$ordine = OrdiniacquistoModel::g()->selectId((int)$id);
?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
	<li <?php echo $posizioni['listino'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/listino/$id".$this->viewStatus;?>"><?php echo gtext("Listino");?></a></li>
	<li <?php echo $posizioni['import'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/import/$id".$this->viewStatus;?>"><?php echo gtext("Import");?></a></li>
</ul>

<div style="clear:left;"></div>
<?php } ?>
