<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($useEditor) { ?>
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
		$('.editor_textarea').tinymce(tiny_editor_config);
		//$(".display_none").css({ 'display' : 'none' });
	});
	
$(document).ready(function() {

	
});
</script>
<?php } ?>

<?php if (!showreport()) { ?>
<section class="content-header">
	<h1><?php if (!showreport()) { ?>Gestione<?php } else { ?>Visualizzazione<?php } ?> <?php echo $tabella;?>: <?php echo $titoloRecord;?></h1>
</section>
<?php } ?>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<?php if (!nobuttons()) { ?>
			<!-- show the top menù -->
			<div class='mainMenu'>
				<?php echo $menu;?>
			</div>
			<?php } ?>
				
			<?php include($this->viewPath("steps"));?>
			<?php if (!showreport()) { ?>
			<div class="box">
				<div class="box-header with-border main">
					<?php echo $notice;?>
					
					<!-- show the table -->
					<div class='scaffold_form'>
					<?php } ?>
						<?php
						$path = ROOT."/Application/Views/".ucfirst($this->controller)."/".$this->action."_scaffold_main.php";
						
						if (file_exists($path))
						{
							include($path);
						}
						else
						{
							echo $main;
						}
						?>
					<?php if (!showreport()) { ?>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</section>
