<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (SpedizioninegozioModel::getStato((int)$id) == "A") { ?>
<br />
<form select2="" class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/".$this->action."/$id".$this->viewStatus;?>' method='POST'>

	<?php echo Html_Form::select("codice","",$selectServizi,"form-control",null,"yes","placeholder='".gtext("Peso..")."'");?>
	
	<button class="submit_file btn btn-success btn-sm make_spinner" type="submit" name="insertAction" value="Aggiungi"><i class="fa fa-plus-square-o"></i> <?php echo gtext("Aggiungi servizio");?></button>
	<input type="hidden" name="insertAction" value="Aggiungi" />
	
</form>
<?php } ?>
