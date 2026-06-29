<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "righe") { ?>
	<?php if (ControllersModel::checkAccessoAlController(array("ordiniacquisto", "ordiniacquistorighe"))) { ?>
		<form class="form-inline form_inserisci_riga" id-ordine="<?php echo $id;?>" role="form" action='#' method='POST' enctype="multipart/form-data">
			<?php include($this->viewPath("gestisci_associato_pulsante_righe"));?>
		</form>
	<?php } ?>
<?php } ?>
