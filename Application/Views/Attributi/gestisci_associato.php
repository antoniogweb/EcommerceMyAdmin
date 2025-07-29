<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "valori") { ?>
	<?php if (!$aggiuntaLibera) { ?><p><a class="iframe btn btn-success help_aggiungi" href="<?php echo $this->baseUrl."/attributivalori/form/insert";?>?partial=Y&nobuttons=Y&id_a=<?php echo $id;?>">Aggiungi valore</a></p>
	<?php } else { ?>
	<div>
		<form class="form-inline ajax_submit" role="form" action='<?php echo $this->baseUrl."/".$this->applicationUrl."/attributivalori/form/insert/0?id_a=$id";?>' method='POST'>
			<?php echo Html_Form::input("titolo","","form-control auto","titolo",'autocomplete="off" placeholder="'.gtext("Valore").'"');?>
			<input type="hidden" name="insertAction" value="insertAction" />
			<input class="submit_file btn btn-primary" type="submit" name="insertAction" value="<?php echo gtext("Aggiungi");?>">
		</form>
		<br />
	</div>
	<?php } ?>
<?php } ?>
