<script src="<?php echo $this->baseUrl?>/Public/Js/cheef-jquery-ace/ace/ace.js"></script>
<script src="<?php echo $this->baseUrl?>/Public/Js/cheef-jquery-ace/ace/theme-dreamweaver.js"></script>
<script src="<?php echo $this->baseUrl?>/Public/Js/cheef-jquery-ace/ace/mode-ruby.js"></script>
<script src="<?php echo $this->baseUrl?>/Public/Js/cheef-jquery-ace/jquery-ace.min.js"></script>

<script type="text/javascript">
$().ready(function() {
	$('textarea').ace({ theme: 'dreamweaver', lang: 'ruby' })
});
</script>

<form class="formClass form_class_tipo_contenuto" method="POST" action="<?php echo $this->baseUrl."/".$this->controller."/form/$type/$id".$this->viewStatus;?>">
	<?php echo $form["titolo"];?>
	
	<?php echo $form["tipo"];?>
	
	<?php echo $form["section"];?>
	
	<?php echo $form["campi"];?>
	
	<?php echo $form["descrizione"];?>
	
	<?php if ($type === "update") { ?>
	<input class="varchar_input form-control" type="hidden" value="<?php echo $id;?>" name="id_ct">
	<?php } ?>
	
	<div class="submit_entry">
		<span class="submit_entry_Salva">
			<button id="<?php echo $type;?>Action" class="btn btn-success make_spinner" name="<?php echo $type;?>Action" type="submit"><i class="fa fa-save"></i> <?php echo gtext("Salva"); ?></button>
			<input type="hidden" value="Salva" name="<?php echo $type;?>Action">
		</span>
	</div>
</form>
