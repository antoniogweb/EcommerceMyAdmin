<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($tipo == "TESTO") { ?>
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

<form class="formClass" method="POST" action="<?php echo $this->baseUrl."/".$this->controller."/form/$type/$id".$this->viewStatus;?>" enctype="multipart/form-data">
	<?php if ($tipo == "TESTO") { ?>
		<?php echo $form["valore"];?>
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
				<?php echo $form["target_link"];?>
			</div>
			<?php } ?>
			
			<div class='col-sm-6'>
				<?php echo $form["attributi"];?>
			</div>
		</div>
	<?php } ?>
	
	<?php if ($type === "update") { ?>
	<input class="varchar_input form-control" type="hidden" value="<?php echo $id;?>" name="id_n">
	<?php } ?>
	
	<div class="submit_entry">
		<span class="submit_entry_Salva">
			<button id="<?php echo $type;?>Action" class="btn btn-success" name="<?php echo $type;?>Action" type="submit">Salva</button>
			<input type="hidden" value="Salva" name="<?php echo $type;?>Action">
		</span>
	</div>
</form>
