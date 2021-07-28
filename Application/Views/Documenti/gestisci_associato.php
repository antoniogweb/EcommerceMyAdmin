<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "gruppi") { ?>
	<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->controller."/gruppi/$id".$this->viewStatus;?>' method='POST' enctype="multipart/form-data">
	
		<?php echo Html_Form::select("id_group","",$listaGruppi,null,"combobox","yes");?>
		
		<input class="submit_file btn btn-primary btn-sm" type="submit" name="insertAction" value="Aggiungi">
		
	</form>
	<br />
<?php } ?>

<?php if ($this->action == "lingue") { ?>
	<?php if (count($listaLingue) > 0) { ?>
	<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->controller."/lingue/$id".$this->viewStatus;?>' method='POST'>
	
		<?php echo Html_Form::select("id_lingua","",$listaLingue,"form-control",null,"yes");?>
		
		<input class="submit_file btn btn-success btn-sm" type="submit" name="includi" value="<?php echo gtext("Includi lingua")?>">
		<input class="submit_file btn btn-warning btn-sm" type="submit" name="escludi" value="<?php echo gtext("Escludi lingua")?>">
		
	</form>
	<br />
	<?php } ?>
<?php } ?>
