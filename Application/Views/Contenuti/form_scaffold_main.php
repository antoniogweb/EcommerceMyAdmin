<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (isset($form["descrizione"])) { ?>
<script type="text/javascript" src="<?php echo $this->baseUrl?>/Public/Js/tiny_mce/jquery.tinymce.js"></script>

<script type="text/javascript">

function ajaxfilemanager(field_name, url, type, win) {
		var ajaxfilemanagerurl = "<?php echo $this->baseUrl."/upload/main/1/1/1/1/0/0/1/0/1/0/1?base=";?>";
// 		switch (type) {
// 			case "image":
// 			ajaxfilemanagerurl = "<?php echo $this->baseUrl."/upload/main/1/1/1/1/0/0/0/0/1/0/1?base=";?>";
// 			break;
// 		}
		var fileBrowserWindow = new Array();
		fileBrowserWindow["file"] = ajaxfilemanagerurl;
		fileBrowserWindow["title"] = "Ajax File Manager";
		fileBrowserWindow["width"] = "782";
		fileBrowserWindow["height"] = "440";
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
		$('textarea').tinymce(tiny_editor_config);
		//$(".display_none").css({ 'display' : 'none' });
	});
	
$(document).ready(function() {

	
});
</script>
<?php } ?>

<form class="formClass form_class_contenuto" method="POST" action="<?php echo $this->baseUrl."/".$this->controller."/form/$type/$id".$this->viewStatus;?>" enctype="multipart/form-data">
	<div class='row'>
		<div class='col-md-6'>
			<?php echo $form["titolo"];?>
			
			<?php if (isset($form["immagine_1"])) { ?>
			<?php echo $form["immagine_1"];?>
			<?php } ?>
			
			<?php if (isset($form["descrizione"])) { ?>
			<?php echo $form["descrizione"];?>
			<?php } ?>
			
			<?php if (isset($form["coordinate"])) { ?>
			<?php echo $form["coordinate"];?>
			<?php } ?>
		</div>
		
		<div class='col-md-6'>
			<?php if (isset($form["id_tipo"])) { ?>
			<?php echo $form["id_tipo"];?>
			<?php } ?>
			
			<?php echo $form["lingua"];?>
			
			<?php echo $form["attivo"];?>
			
			<?php if (isset($form["link_id_page"])) { ?>
			<?php echo $form["link_id_page"];?>
			<?php } ?>
			
			<?php if (isset($form["link_id_c"])) { ?>
			<?php echo $form["link_id_c"];?>
			<?php } ?>
			
			<?php if (isset($form["link_id_marchio"])) { ?>
			<?php echo $form["link_id_marchio"];?>
			<?php } ?>
			
			<?php if (isset($form["link_id_tag"])) { ?>
			<?php echo $form["link_id_tag"];?>
			<?php } ?>
		</div>
	</div>
	
	<?php if ($type === "update") { ?>
	<input class="varchar_input form-control" type="hidden" value="<?php echo $id;?>" name="id_ct">
	<?php } ?>
	
	<div class="submit_entry">
		<span class="submit_entry_Salva">
			<button id="<?php echo $type;?>Action" class="btn btn-success" name="<?php echo $type;?>Action" type="submit">Salva</button>
			<input type="hidden" value="Salva" name="<?php echo $type;?>Action">
		</span>
	</div>
</form>
