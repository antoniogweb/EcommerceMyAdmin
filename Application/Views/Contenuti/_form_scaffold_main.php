<script type="text/javascript" src="<?php echo $this->baseUrl?>/Public/Js/tiny_mce/jquery.tinymce.js"></script>

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

function ajaxfilemanager(field_name, url, type, win) {
	var ajaxfilemanagerurl = "<?php echo $this->baseUrl."/upload/main/1/1/1/1/0/0/1/0/1/0/1?base=";?>";

	var fileBrowserWindow = new Array();
	fileBrowserWindow["file"] = ajaxfilemanagerurl;
	fileBrowserWindow["title"] = "Ajax File Manager";
	fileBrowserWindow["width"] = "100%";
	fileBrowserWindow["height"] = "640";
	fileBrowserWindow["resizable "] = "yes";
	fileBrowserWindow["inline"] = "yes";
	fileBrowserWindow["close_previous"] = "no";
	tinyMCE.activeEditor.windowManager.open(fileBrowserWindow, {
		window : win,
		input : field_name
	});
	
	return false;
}
$().ready(function() {
	$('[name="descrizione"]').tinymce(tiny_editor_config);
});
</script>
<div class='row'>
	<form class="formClass" method="POST" action="<?php echo $this->baseUrl."/".$this->controller."/form/$type/$id".$this->viewStatus;?>" enctype="multipart/form-data">
		<div class='col-md-8'>
			<div class="panel panel-info">
				<div class="panel-heading">
					Contenuto
				</div>
				<div class="panel-body">
					<?php echo $form["titolo"];?>
					
					<div class='row'>
						<div class='col-md-6'>
							<?php echo $form["id_tipo"];?>
						</div>
						<div class='col-md-6'>
							<?php echo $form["lingua"];?>
						</div>
					</div>
					
					<div class='row'>
						<div class='col-md-6'>
							<?php echo $form["link_contenuto"];?>
						</div>
						<div class='col-md-6'>
							<?php echo $form["link_libero"];?>
						</div>
					</div>
					
					<?php echo $form["target"];?>
					
					<?php echo $form["descrizione"];?>
					
					<?php if ($type === "update") { ?>
					<input class="varchar_input form-control" type="hidden" value="<?php echo $id;?>" name="id_ct">
					<?php } ?>
					
					<div class="submit_entry">
						<span class="submit_entry_Salva">
							<button id="<?php echo $type;?>Action" class="btn btn-success" name="<?php echo $type;?>Action" type="submit">Salva</button>
							<input type="hidden" value="Salva" name="<?php echo $type;?>Action">
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class='col-md-4'>
			<div class="panel panel-info">
				<div class="panel-heading">
					Immagini
				</div>
				<div class="panel-body">
					<?php echo $form["immagine_1"];?>
					
					<?php echo $form["immagine_2"];?>
				</div>
			</div>
		</div>
	</form>
</div>
