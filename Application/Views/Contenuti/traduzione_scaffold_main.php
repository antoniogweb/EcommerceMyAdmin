<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
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
		$('[name="descrizione"]').tinymce(tiny_editor_config);
		//$(".display_none").css({ 'display' : 'none' });
	});
</script>

<?php echo $main;?>
