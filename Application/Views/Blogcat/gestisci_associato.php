<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action === "classisconto") { ?>
<br />
<div class="panel panel-default">
	<div class="panel-heading">
		Aggiungi una classe di sconto
	</div>
	<div class="panel-body">
		<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->controller."/classisconto/$id".$this->viewStatus;?>' method='POST'>
		
			<?php echo Html_Form::select("id_classe","",$listaClassi,"form-control",null,"yes");?>
			
			<input class="submit_file btn btn-primary" type="submit" name="insertAction" value="Aggiungi">
			
		</form>
	</div>
</div>
<?php } ?>

<?php if ($this->action === "contenuti") { ?>

<p><a class="iframe btn btn-success pull-right" href="<?php echo $this->baseUrl."/contenuti/form/insert";?>?partial=Y&nobuttons=N&id_c=<?php echo $id;?>">Aggiungi contenuto</a></p>

<?php } ?>
