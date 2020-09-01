<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<script type="text/javascript" src="<?php echo $this->baseUrl?>/Public/Js/tiny_mce/jquery.tinymce.js"></script>

<script type="text/javascript">

function ajaxfilemanager(field_name, url, type, win) {
	var ajaxfilemanagerurl = "<?php echo $this->baseUrl."/upload/main/1/1/1/1/0/0/1/0/1/0/1?base=";?>";

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
	$('textarea.dettagli').tinymce(tiny_editor_config);
});

</script>

<?php if (!isset($_GET["partial"])) { ?>
<section class="content-header">
	<h1>Gestione testi</h1>
</section>
<?php } ?>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border main">

					<?php if (!isset($_GET["partial"])) { ?>
					<!-- show the top menù -->
					<div class='mainMenu'>
						<?php echo $menu;?>
					</div>
					<?php } ?>

					<?php echo $notice;?>

					<!-- show the table -->
					<div class='scaffold_form'>
						<?php echo $main;?>
					</div>
                </div>
			</div>
		</div>
	</div>
</section>