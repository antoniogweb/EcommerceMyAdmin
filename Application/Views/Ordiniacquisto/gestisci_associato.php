<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "righe") { ?>
	<?php if (ControllersModel::checkAccessoAlController(array("magazzinoarticoli"))) { ?>
		<?php if (OrdiniacquistoModel::g()->isBozza($id)) { ?>
			<p>
				<a class="iframe btn btn-success" href="<?php echo $this->baseUrl.$this->applicationUrl."/magazzinoarticoli/main";?>?partial=Y&nobuttons=Y&id_ordine_acquisto=<?php echo $id;?>"><?php echo gtext("Aggiungi articolo")?></a>
			</p>
		<?php } else { ?>
			<div class="callout callout-info"><?php echo gtext("Le righe dell'ordine non sono modificabili in quanto l'ordine non è più in stato di Bozza");?></b></div>
		<?php } ?>
	<?php } ?>
<?php } ?>
