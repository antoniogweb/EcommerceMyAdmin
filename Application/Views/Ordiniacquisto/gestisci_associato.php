<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "righe") { ?>
	<?php if (ControllersModel::checkAccessoAlController(array("magazzinoarticoli"))) { ?>
		<?php if (OrdiniacquistoModel::g()->isBozza($id)) { ?>
			<div>
				<?php foreach ($tipologie as $t) {
					if (!OrdiniacquistorighetipologieModel::checkInserimentoTipologiaInOrdine($id, $t["id_ordine_acquisto_riga_tipologia"]))
						continue;
				?>
				<a style="margin-left:5px;" id-ordine="<?php echo $id;?>" id-riga-tipologia="<?php echo (int)$t["id_ordine_acquisto_riga_tipologia"];?>" class="aggiungi_riga_tipologia_ordine_acquisto make_spinner pull-right <?php echo $t["classe"];?>" href="<?php echo $this->baseUrl."/ordiniacquistorighe/form/insert/0";?>"><i class="fa fa-plus"></i> <?php echo $t["titolo"];?></a>
				<?php } ?>
				
				<a style="margin-left:5px;" id-ordine="<?php echo $id;?>" id-riga-tipologia="0" class="aggiungi_riga_tipologia_ordine_acquisto make_spinner pull-right btn btn-sm btn-default" href="<?php echo $this->baseUrl."/ordiniacquistorighe/form/insert/0";?>"><i class="fa fa-plus"></i> <?php echo gtext("Riga libera");?></a>
				<form class="form-inline form_inserisci_articolo" id-ordine="<?php echo $id;?>" role="form" action='#' method='POST' enctype="multipart/form-data">
					<?php include($this->viewPath("gestisci_associato_pulsante_righe"));?>
				</form>
			</div>
			<br />
		<?php } else { ?>
			<div class="callout callout-info"><?php echo gtext("Le righe dell'ordine non sono modificabili in quanto l'ordine non è più in stato di Bozza oppure ha delle ricezioni collegate");?></b></div>
		<?php } ?>
	<?php } ?>
<?php } ?>
