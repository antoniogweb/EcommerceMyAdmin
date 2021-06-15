<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<script src="<?php echo $this->baseUrlSrc;?>/Public/Js/dropzone-5.7.0/dist/min/dropzone.min.js"></script>
<link rel="stylesheet" href="<?php echo $this->baseUrlSrc;?>/Public/Js/dropzone-5.7.0/dist/min/dropzone.min.css">

<script>
$(document).ready(function() {
	Dropzone.autoDiscover = false;
	
	<?php if (isset($caricaZip)) { ?>
	Dropzone.prototype.defaultOptions.dictDefaultMessage = "<?php echo sanitizeJs(gtext("Trascina qui i file compressi (formato ZIP) che desideri caricare"))."<br /><i>(".sanitizeJs(gtext("ogni file verrà decompresso e i file al loro interno verranno salvati singolarmente")).")</i>";?>";
	<?php } else { ?>
	Dropzone.prototype.defaultOptions.dictDefaultMessage = "<?php echo sanitizeJs(gtext("Trascina qui i file che desideri caricare"));?>";
	<?php } ?>
	
	var myDropzone = new Dropzone(".dropzone", {
		paramName: "filename",
		url: "<?php echo $uploadUrl;?>",
		init: function () {
			this.on("success", function (file, responseText) {
				if ($.trim(responseText.result) == "OK")
				{
					myDropzone.removeFile(file);
				}
				else
				{
					$(".dropbox_alert").append("<div class='alert alert-danger'>" + responseText.errore + "</div>");
					aggAlteIfr();
				}
			});
			
			this.on("queuecomplete", function (file, responseText) {
				if (myDropzone.files.length == 0)
				{
					if (window.parent)
						window.parent.closeModal();
				}
			});
			
		}
	});
	
// 	myDropzone.on("complete", function(file, responseText) {
// 		console.log(responseText);
// // 		myDropzone.removeFile(file);
// 	});
});
</script>

<section class="content-header">
	<?php if (!isset($pageTitle)) { ?>
	<h1>Gestione <?php echo $tabella;?>: <?php echo isset($caricaZip) ? gtext("carica uno o più file compressi") : gtext("carica molti elementi");?></h1>
	<?php } else { ?>
	<h1><?php echo $pageTitle;?></h1>
	<?php } ?>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- show the top menù -->
			<div class='mainMenu' style="height:30px;">
				
			</div>

			<?php
			$type = "insert";
			include($this->viewPath("steps"));?>
			
			<div class="box">
				<div class="box-header with-border main">
					<form action="#" class="dropzone">
						<div class="fallback">
							<input name="file" type="file" multiple />
						</div>
					</form>
				</div>
			</div>
			
			<div class="dropbox_alert"></div>
		</div>
	</div>
</section>
