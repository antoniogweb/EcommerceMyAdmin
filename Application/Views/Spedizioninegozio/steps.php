<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") {
	$spedizione = SpedizioninegozioModel::g()->left(array("spedizioniere"))->whereId((int)$id)->first();
	$ordini = SpedizioninegozioModel::g()->getOrdini((int)$id);
?>

<div class="info-box">
	<span class="info-box-icon bg-default"><i class="fa fa-truck"></i></span>
	<div class="info-box-content">
	<span class="info-box-text"><b><?php echo gtext("ID");?>:</b> <?php echo $spedizione["spedizioni_negozio"]["id_spedizione_negozio"];?> </span>
	<span class="info-box-text"><b><?php echo gtext("Ordine");?>:</b> <?php echo implode(",",$ordini);?> </span>
<!-- 	<span class="info-box-number"><?php echo gtext("ID");?>: <?php echo $spedizione["spedizioni_negozio"]["id_spedizione_negozio"];?></span> -->
</div>

</div>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
	<li <?php echo $posizioni['righe'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/righe/$id".$this->viewStatus;?>"><?php echo gtext("Righe ordine");?></a></li>
</ul>

<div style="clear:left;"></div>
<?php } ?>
