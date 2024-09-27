<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "contesti") { ?>

<form select2="" class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/".$this->action."/$id".$this->viewStatus;?>' method='POST' enctype="multipart/form-data">

	<?php echo Html_Form::select("id_page","",$elencoPagine,"","","yes");?>

	<button class="submit_file btn btn-primary btn-sm make_spinner" type="submit" name="insertAction" value="Aggiungi"><i class="fa fa-save"></i> <?php echo gtext("Aggiungi");?></button>
	<input type="hidden" name="insertAction" value="Aggiungi" />
</form>
<br />

<?php } ?>
