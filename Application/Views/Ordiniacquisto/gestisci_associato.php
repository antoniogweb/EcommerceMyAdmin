<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "righe") { ?>
	<?php if (ControllersModel::checkAccessoAlController(array("magazzinoarticoli"))) { ?>
		<?php if (OrdiniacquistoModel::g()->isBozza($id)) { ?>
			<div>
				<?php
				if (false && v("attiva_righe_generiche_in_ordine_offline")) { 
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
				
				<form class="form-inline form_inserisci_articolo" id-ordine="<?php echo $id;?>" role="form" action='#' method='POST' enctype="multipart/form-data">
					<?php include($this->viewPath("gestisci_associato_pulsante_righe"));?>
				</form>
			</div>
			<br />
		<?php } else { ?>
			<div class="callout callout-info"><?php echo gtext("Le righe dell'ordine non sono modificabili in quanto l'ordine non è più in stato di Bozza");?></b></div>
		<?php } ?>
	<?php } ?>
<?php } ?>
