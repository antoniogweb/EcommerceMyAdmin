<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "righe") {
	$queryListaRegalo = $id_lista_regalo ? "&id_lista_reg_filt=$id_lista_regalo&id_lista_regalo_ordine=".$id_lista_regalo : "";
?>
	<?php if (ControllersModel::checkAccessoAlController(array("combinazioni"))) { ?>
		<?php if (OrdiniModel::g()->isDeletable($id)) { ?>
			<div>
				<?php
				if (v("attiva_righe_generiche_in_ordine_offline")) { 
					$idProdottoGenerico = ProdottiModel::getIdProdottoGenerico();
					
					if ($idProdottoGenerico) {
						foreach ($tipologie as $t) {
							if (!RighetipologieModel::checkInserimentoTipologiaInOrdine($id, $t["id_riga_tipologia"]))
								continue;
						?>
						<a style="margin-left:5px;" id-c="<?php echo $idProdottoGenerico;?>" id-ordine="<?php echo $id;?>" id-riga-tipologia="<?php echo (int)$t["id_riga_tipologia"];?>" class="aggiunti_riga_tipologia make_spinner pull-right <?php echo $t["classe"];?>" href="<?php echo $this->baseUrl."/combinazioni/main";?>?partial=Y<?php if (!partial()) { ?>&nobuttons=Y<?php } ?>&id_ordine=<?php echo $id;?>"><i class="fa fa-plus"></i> <?php echo $t["titolo_breve"];?></a>
						<?php } ?>
					<?php } ?>
				<?php } ?>
				
				<?php if (true) { ?>
				<form class="form-inline form_inserisci_articolo" id-ordine="<?php echo $id;?>" role="form" action='#' method='POST' enctype="multipart/form-data">

					<span select2="/prodotti/main/1?esporta_json&formato_json=select2">
						<?php echo Html_Form::select("id_page","",array("0" => gtext("Seleziona articolo")),"select_articolo_ordine","","yes", "style='min-width:400px;'");?>
					</span>
					
					<span select2="">
						<?php echo Html_Form::select("id_c","",array("0" => gtext("Seleziona variante")),"form-control select_combinazione_ordine","","yes", "style='min-width:200px;'");?>
					</span>
					
					<?php include($this->viewPath("gestisci_associato_pulsante_righe"));?>
				</form>
				<?php } else { ?>
<!-- 				<p><a class="<?php if (!partial()) { ?>iframe<?php } ?> btn btn-success" href="<?php echo $this->baseUrl."/combinazioni/main";?>?partial=Y<?php if (!partial()) { ?>&nobuttons=Y<?php } ?>&id_ordine=<?php echo $id;?><?php echo $queryListaRegalo;?>"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi articoli")?></a></p> -->
				<?php } ?>
			</div>
			<br />
		<?php } else { ?>
		<div class="callout callout-info"><?php echo gtext("Le righe dell'ordine non sono modificabili in quanto l'ordine non è più ad uno dei seguenti stati");?>: <b><?php echo StatiordineModel::getTitoliStati(v("stati_ordine_editabile_ed_eliminabile"));?></b></div>
		<?php } ?>
	<?php } ?>
<?php } ?>
