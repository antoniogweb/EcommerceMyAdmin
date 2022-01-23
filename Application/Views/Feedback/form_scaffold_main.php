<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<script type="text/javascript" src="<?php echo $this->baseUrl?>/Public/Js/tiny_mce/jquery.tinymce.js"></script>

<script type="text/javascript">
$().ready(function() {
	$('textarea').tinymce(tiny_editor_config);
});
</script>

<form class="formClass form_class_contenuto" method="POST" action="<?php echo $this->baseUrl."/".$this->controller."/form/$type/$id".$this->viewStatus;?>">
	<div class='row'>
		<div class='col-md-6'>
			<?php echo $form["autore"];?>
			
			<?php echo $form["data_feedback"];?>
			
			<?php echo $form["testo"];?>
			
			<?php echo $form["attivo"];?>
			
			<?php echo $form["voto"];?>
		</div>
	</div>
	
	<div class="submit_entry">
		<span class="submit_entry_Salva">
			<button id="<?php echo $type;?>Action" class="btn btn-success" name="<?php echo $type;?>Action" type="submit">Salva</button>
			<input type="hidden" value="Salva" name="<?php echo $type;?>Action">
		</span>
	</div>
</form>
