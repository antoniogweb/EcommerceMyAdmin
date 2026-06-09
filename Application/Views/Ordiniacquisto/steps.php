<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($id) {
	$ordine = OrdiniacquistoModel::g()->selectId((int)$id);
?>

<?php require_once(LIBRARY . "/Application/Views/Ordiniacquisto/top_steps.php");?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
	<li <?php echo $posizioni['righe'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/righe/$id".$this->viewStatus;?>"><?php echo gtext("Righe ordine");?></a></li>
	<li <?php echo $posizioni['inviipdf'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/inviipdf/$id".$this->viewStatus;?>"><?php echo gtext("Storico PDF");?></a></li>
</ul>

<div style="clear:left;"></div>
<?php } ?>
