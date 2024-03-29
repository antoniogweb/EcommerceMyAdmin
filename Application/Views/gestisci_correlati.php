<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<form select2="" class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/".$this->action."/$id_page".$this->viewStatus;?>' method='POST' enctype="multipart/form-data">

	<?php echo Html_Form::select("id_corr","",$listaProdotti,"","","yes");?>
	
	<?php if ($mostra_tendina_tipologia) { ?>
	<?php echo Html_Form::select("id_tipologia_correlato","",$listaTipologieCorrelati,"","","yes");?>
	<?php } ?>
	
	<button class="submit_file btn btn-primary btn-sm make_spinner" type="submit" name="insertAction" value="Aggiungi"><i class="fa fa-save"></i> <?php echo gtext("Aggiungi");?></button>
	<input type="hidden" name="insertAction" value="Aggiungi" />
</form>
<br />
