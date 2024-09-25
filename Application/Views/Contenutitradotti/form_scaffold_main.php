<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($editor_visuale) { ?>
<script type="text/javascript" src="<?php echo $this->baseUrlSrc?>/Public/Js/tiny_mce/jquery.tinymce.js"></script>
<?php } else { ?>
<script src="<?php echo $this->baseUrlSrc?>/Public/Js/cheef-jquery-ace/ace/ace.js"></script>
<script src="<?php echo $this->baseUrlSrc?>/Public/Js/cheef-jquery-ace/ace/theme-dreamweaver.js"></script>
<script src="<?php echo $this->baseUrlSrc?>/Public/Js/cheef-jquery-ace/ace/mode-ruby.js"></script>
<script src="<?php echo $this->baseUrlSrc?>/Public/Js/cheef-jquery-ace/jquery-ace.min.js"></script>
<?php } ?>

<script type="text/javascript">

function updateForm()
{
	if ($(".in_promozione option:selected").length > 0)
	{
		var in_promozione = $(".in_promozione option:selected").attr("value");
		
		if (in_promozione == "Y")
		{
			$(".class_promozione").css("display","block");
		}
		else
		{
			$(".class_promozione").css("display","none");
		}
	}
}
$().ready(function() {
	<?php if ($editor_visuale) { ?>
	$('.dettagli').tinymce(tiny_editor_config);
	<?php } else { ?>
		$('.dettagli').ace({ theme: 'dreamweaver', lang: 'ruby' })
	<?php } ?>
	
	$("select[name='editor_visuale']").change(function(){
		reloadPage();
	});
});
</script>
<div class='row'>
	<form class="formClass" method="POST" action="<?php echo $this->baseUrl."/".$this->controller."/form/$type/$id".$this->viewStatus;?>" enctype="multipart/form-data">
		<div class='col-md-8'>
			<?php if (isset($form["title"])) { ?>
			<?php echo $form["title"];?>
			<?php } ?>
			
			<?php if (isset($form["titolo"])) { ?>
			<?php echo $form["titolo"];?>
			<?php } ?>
			
			<?php if (isset($form["alias"])) { ?>
			<?php echo $form["alias"];?>
			<?php } ?>
			
			<?php if (isset($form["sottotitolo"])) { ?>
			<?php echo $form["sottotitolo"];?>
			<?php } ?>
			
			<?php if (isset($form["editor_visuale"])) { ?>
			<?php echo $form["editor_visuale"];?>
			<?php } ?>
			
			<?php if (isset($form["description"])) { ?>
			<?php echo $form["description"];?>
			<?php } ?>
			
			<?php if (isset($form["descrizione"])) { ?>
			<?php echo $form["descrizione"];?>
			<?php } ?>
			
			<?php echo (v("attiva_descrizione_2_in_prodotti") && isset($form["descrizione_2"])) ? $form["descrizione_2"] : ""; ?>
			
			<?php echo (v("attiva_descrizione_3_in_prodotti") && isset($form["descrizione_3"])) ? $form["descrizione_3"] : ""; ?>
			
			<?php echo (v("attiva_descrizione_4_in_prodotti") && isset($form["descrizione_4"])) ? $form["descrizione_4"] : ""; ?>
			
			<?php if (isset($form["istruzioni_pagamento"])) { ?>
			<?php echo $form["istruzioni_pagamento"];?>
			<?php } ?>

			<?php if (isset($form["url"])) { ?>
			<?php echo $form["url"];?>
			<?php } ?>
			
			<?php if (isset($form["testo_link"])) { ?>
			<?php echo $form["testo_link"];?>
			<?php } ?>
			
			<?php include($this->viewPath("pages_campi_aggiuntivi"));?>
			
			<?php if ($type === "update") { ?>
			<input class="varchar_input form-control" type="hidden" value="<?php echo $id;?>" name="id_ct">
			<?php } ?>
			
			<?php include($this->viewPath("form_submit_button"));?>
		</div>
		<?php if (isset($form["meta_title"]) && isset($form["keywords"]) && isset($form["meta_description"])) { ?>
		<div class='col-md-4'>
			<div class="panel panel-info">
				<div class="panel-heading">
					Meta
				</div>
				<div class="panel-body">
					<?php echo $form["meta_title"];?>
					
					<?php echo $form["keywords"];?>
					
					<?php echo $form["meta_description"];?>
				</div>
			</div>
		</div>
		<?php } ?>
	</form>
</div>
