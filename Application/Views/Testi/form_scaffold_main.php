<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($tipo == "TESTO") { ?>

<?php if ($editor_visuale) { ?>
<script type="text/javascript" src="<?php echo $this->baseUrlSrc?>/Public/Js/tiny_mce/jquery.tinymce.js"></script>
<?php } else { ?>
<?php include($this->viewPath("editor"));?>
<?php } ?>

<script type="text/javascript">
$().ready(function() {
	<?php if ($editor_visuale) { ?>
	$('textarea').tinymce(tiny_editor_config);
	<?php } else { ?>
		$('textarea').ace({ theme: 'dreamweaver', lang: 'ruby' })
	<?php } ?>
	
	$("select[name='editor_visuale']").change(function(){
		reloadPage();
	});
});
</script>
<?php } ?>

<form class="formClass" method="POST" action="<?php echo $this->baseUrl."/".$this->controller."/form/$type/$id".$this->viewStatus;?>" enctype="multipart/form-data">
	<?php if ($tipo == "TESTO") { ?>
		<?php echo isset($form["editor_visuale"]) ? $form["editor_visuale"] : "";?>
		
		<?php echo $form["valore"];?>
		
		<?php echo isset($form["tag_elemento"]) ? $form["tag_elemento"] : "";?>
		
		<?php echo isset($form["attributi"]) ? $form["attributi"] : "";?>
	<?php } ?>
	
	<?php if ($tipo == "IMMAGINE" || $tipo == "VIDEO") { ?>
		<div class='row'>
			<div class='col-sm-6'>
				<?php echo $form["immagine"];?>
			</div>
			<div class='col-sm-6'>
				<?php echo $form["immagine_2x"];?>
			</div>
		</div>
		
		<div class='row'>
			<div class='col-sm-4'>
				<?php echo $form["width"];?>
			</div>
			<div class='col-sm-4'>
				<?php echo $form["height"];?>
			</div>
			<div class='col-sm-4'>
				<?php echo $form["crop"];?>
			</div>
		</div>
	<?php } ?>
	
	<?php if ($tipo == "LINK" || $tipo == "IMMAGINE" || $tipo == "VIDEO") { ?>
		<div class='row'>
			<?php if ($tipo == "IMMAGINE" || $tipo == "VIDEO") { ?>
			<div class='col-sm-6'>
				<?php echo $form["alt"];?>
			</div>
			<?php } ?>
			
			<?php if ($tipo == "LINK" || $tipo == "IMMAGINE") { ?>
			<div class='col-sm-6'>
				<?php echo $form["testo_link"];?>
			</div>
			<?php } ?>
			
			<div class='col-sm-6'>
				<?php echo $form["url_link"];?>
			</div>
			<?php if ($tipo != "VIDEO") { ?>
			<div class='col-sm-6'>
				<?php echo $form["id_contenuto"];?>
			</div>
			<div class='col-sm-6'>
				<?php echo $form["id_categoria"];?>
			</div>
			<div class='col-sm-6'>
				<?php echo $form["link_id_documento"];?>
			</div>
			<div class='col-sm-6'>
				<?php echo $form["target_link"];?>
			</div>
			<?php } ?>
			
			<div class='col-sm-6'>
				<?php echo $form["attributi"];?>
			</div>
		</div>
	<?php } ?>
	
	<div class='row'>
		<div class='col-sm-6'>
			<?php echo isset($form["template"]) ? $form["template"] : "";?>
		</div>
	</div>
	
	
	<?php if ($type === "update") { ?>
	<input class="varchar_input form-control" type="hidden" value="<?php echo $id;?>" name="id_n">
	<?php } ?>
	
	<div class="submit_entry">
		<span class="submit_entry_Salva">
			<button id="<?php echo $type;?>Action" class="btn btn-success make_spinner" name="<?php echo $type;?>Action" type="submit"><i class="fa fa-save"></i> Salva</button>
			<input type="hidden" value="Salva" name="<?php echo $type;?>Action">
		</span>
	</div>
</form>
