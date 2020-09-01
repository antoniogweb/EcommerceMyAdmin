<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "categorie") { ?>

<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->controller."/categorie/$id".$this->viewStatus;?>' method='POST'>

	<?php echo Html_Form::select("id_c","",$listaCategorie,null,"combobox","yes");?>
	
	<input class="submit_file btn btn-primary btn-sm" type="submit" name="insertAction" value="Aggiungi">
	
</form>

<?php } ?>

<?php if ($this->action == "pagine") { ?>

<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->controller."/pagine/$id".$this->viewStatus;?>' method='POST'>

	<?php echo Html_Form::select("id_page","",$listaProdotti,null,"combobox","yes");?>
	
	<input class="submit_file btn btn-primary btn-sm" type="submit" name="insertAction" value="Aggiungi">
	
</form>

<style>
.form-inline .form-control {
    display: inline-block;
    width: 600px !important;
    vertical-align: middle;
}
</style>

<?php } ?>
