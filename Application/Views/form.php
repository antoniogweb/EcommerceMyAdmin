<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($useEditor) { ?>
	<?php if ($useEditorVisuale) { ?>
		<script type="text/javascript" src="<?php echo $this->baseUrl?>/Public/Js/tiny_mce/jquery.tinymce.js"></script>

		<script type="text/javascript">
		$().ready(function() {
			$('.editor_textarea').tinymce(tiny_editor_config);
		});
		</script>
	<?php } else { ?>
		<?php include($this->viewPath("editor"));?>
		
		<script type="text/javascript">
		$().ready(function() {
			$('.editor_textarea').editorCode();
		});
		</script>
	<?php } ?>
<?php } ?>

<?php if (!showreport()) { ?>
<section class="content-header">
	<h1><?php if (!showreport()) { ?><?php echo gtextPlain("Gestione");?><?php } else { ?><?php echo gtextPlain("Visualizzazione");?><?php } ?> <?php echo gtextPlain($tabella);?>: <?php echo $titoloRecord;?></h1>
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
