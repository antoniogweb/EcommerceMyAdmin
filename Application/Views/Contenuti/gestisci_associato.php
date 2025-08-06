<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "gruppi") { ?>
	<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->controller."/gruppi/$id".$this->viewStatus;?>' method='POST' enctype="multipart/form-data">
	
		<?php echo Html_Form::select("id_group","",$listaGruppi,null,"combobox","yes");?>
		
		<input class="submit_file btn btn-primary btn-sm" type="submit" name="insertAction" value="Aggiungi">
		
	</form>
<?php } ?>
<?php if ($this->action == "figli") { ?>
	<p>
		<a class="btn btn-info" href="<?php echo $this->baseUrl."/contenuti/form/insert/0?id_fascia=$id&partial=Y&tipo=GENERICO&id_tipo=".$this->viewArgs["id_tipo_figlio"]."&id_tipo_figlio=".$this->viewArgs["id_tipo_figlio"];?>"><i class="fa fa-plus"></i> <?php echo gtext("Nuovo elemento");?></a>
	</p>
<?php } ?>
