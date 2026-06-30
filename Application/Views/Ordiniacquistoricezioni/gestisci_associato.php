<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "righe") { ?>
	<?php if (ControllersModel::checkAccessoAlController(array("ordiniacquisto", "ordiniacquistorighe"))) { ?>
		<?php if (OrdiniacquistoricezioniModel::g()->editabile($id)) { ?>
			<a class="iframe pull-right btn btn-sm btn-default" href="<?php echo $this->baseUrl."/magazzinoarticoli/main/1?id_ordine_acquisto_ricezione=$id&partial=Y&nobuttons=Y";?>"><i class="fa fa-plus"></i> <?php echo gtext("Articolo non in ordine");?></a>
			
			<form class="form-inline form_inserisci_riga" id-ordine="<?php echo $id;?>" role="form" action='#' method='POST' enctype="multipart/form-data">
				<?php include($this->viewPath("gestisci_associato_pulsante_righe"));?>
			</form>
			<br />
		<?php } else { ?>
			<div class="callout callout-info"><?php echo gtext("Le righe della ricezione non sono modificabili in quanto la ricezione è stata chiusa");?></b></div>
		<?php } ?>
	<?php } ?>
<?php } ?>
