<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "righe") {
	$queryListaRegalo = $id_lista_regalo ? "&id_lista_reg_filt=$id_lista_regalo&id_lista_regalo_ordine=".$id_lista_regalo : "";
?>
	<?php if (ControllersModel::checkAccessoAlController(array("combinazioni"))) { ?>
		<?php if (OrdiniModel::g()->isDeletable($id)) { ?>
		<p><a class="<?php if (!partial()) { ?>iframe<?php } ?> btn btn-success" href="<?php echo $this->baseUrl."/combinazioni/main";?>?partial=Y<?php if (!partial()) { ?>&nobuttons=Y<?php } ?>&id_ordine=<?php echo $id;?><?php echo $queryListaRegalo;?>"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi articoli")?></a></p>
		<?php } else { ?>
		<div class="callout callout-info"><?php echo gtext("Le righe dell'ordine non sono modificabili in quanto l'ordine non è più allo stato");?> <b><?php echo OrdiniModel::$stati["pending"];?></b></div>
		<?php } ?>
	<?php } ?>
<?php } ?>
