<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action === "classisconto") { ?>
	<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->controller."/classisconto/$id".$this->viewStatus;?>' method='POST'>
	
		<?php echo Html_Form::select("id_classe","",$listaClassi,"form-control",null,"yes");?>
		
		<input class="submit_file btn btn-primary" type="submit" name="insertAction" value="Aggiungi">
		
	</form>
<?php } ?>

<?php if ($this->action === "contenuti") { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/contenuti/form/insert";?>?partial=Y&nobuttons=N&id_c=<?php echo $id;?>">Aggiungi fascia</a></p>

<?php } ?>

<?php if ($this->action === "caratteristiche") { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/caratteristiche/main";?>?partial=Y&nobuttons=N&id_c=<?php echo $id;?>"><i class="fa fa-plus-square-o"></i> <?php echo gtext("Aggiungi filtro");?></a></p>

<?php } ?>
