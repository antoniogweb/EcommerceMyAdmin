<?php if (!defined('EG')) die('Direct access not allowed!'); ?>


<script type="text/javascript" src="<?php echo $this->baseUrl?>/Public/Js/tiny_mce/jquery.tinymce.js"></script>


<script type="text/javascript">
function ajaxfilemanager(field_name, url, type, win) {
	var ajaxfilemanagerurl = "<?php echo $this->baseUrl."/upload/main/1/1/1/1/0/0/1/0/1/0/1?base=";?>";

	var fileBrowserWindow = new Array();
	fileBrowserWindow["file"] = ajaxfilemanagerurl;
	fileBrowserWindow["title"] = "Ajax File Manager";
	fileBrowserWindow["width"] = "100%";
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
});
</script>
