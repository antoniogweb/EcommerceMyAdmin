<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (count($righeDaSpedireSelect) > 0 && SpedizioninegozioModel::getStato((int)$id) == "A") { ?>
<br />
<div class="callout callout-info">
	<i class="fa fa-exclamation-triangle"></i> <?php echo gtext("Se si aggiungono o tolgono righe alla spedizione, l'importo in contrassegno verrà ricalcolato, se presente. Si prega di verificare l'importo del contrassegno.")?>
</div>
<form select2="" class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/".$this->action."/$id".$this->viewStatus;?>' method='POST'>

	<?php echo Html_Form::select("id_r","",$righeDaSpedireSelect,"","","yes");?>
	
	<button class="submit_file btn btn-success btn-sm make_spinner" type="submit" name="insertAction" value="Aggiungi"><i class="fa fa-plus-square-o"></i> <?php echo gtext("Aggiungi alla spedizione");?></button>
	<input type="hidden" name="insertAction" value="Aggiungi" />
	
</form>
<?php } ?>
