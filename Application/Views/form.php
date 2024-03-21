<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($useEditor) { ?>
	<?php if ($useEditorVisuale) { ?>
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
		});
		</script>
	<?php } else { ?>
		<?php include($this->viewPath("editor"));?>
		
		<script type="text/javascript">
		$().ready(function() {
			$('.editor_textarea').ace({ theme: 'dreamweaver', lang: 'ruby' })
		});
		</script>
	<?php } ?>
<?php } ?>

<?php if (!showreport()) { ?>
<section class="content-header">
	<h1><?php if (!showreport()) { ?><?php echo gtext("Gestione");?><?php } else { ?><?php echo gtext("Visualizzazione");?><?php } ?> <?php echo gtext($tabella);?>: <?php echo $titoloRecord;?></h1>
</section>
<?php } ?>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<?php include($this->viewPath("form_menu"));?>
				
			<?php include($this->viewPath("steps"));?>
			<?php if (!showreport()) { ?>
			<div class="box">
				<div class="box-header with-border main">
					<?php $flash = flash("notice");?>
					<?php echo $flash;?>
					<?php if (!$flash) echo $notice;?>
					
					<!-- show the table -->
					<div class='scaffold_form'>
					<?php } ?>
						<?php
						$applicationPath = $this->application ? "Apps/".ucfirst($this->application)."/" : "";
						
						$path = ROOT."/Application/".$applicationPath."Views/".ucfirst($this->controller)."/".$this->action."_scaffold_main.php";
						
						if (file_exists($path))
							include($path);
						else
							echo $main;
						?>
					<?php if (!showreport()) { ?>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</section>
