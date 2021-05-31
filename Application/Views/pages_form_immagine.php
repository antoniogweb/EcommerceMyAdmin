<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (!isset($labelBlocco))
	$labelBlocco = "Immagine";
if (!isset($numeroImmagine))
	$numeroImmagine = "";

$stringImmagine = $stringImmagine2 = "";

if ($numeroImmagine)
{
	$stringImmagine = "_".$numeroImmagine;
	$stringImmagine2 = "-".$numeroImmagine;
}
?>
<div class="panel-heading">
	<?php echo gtext($labelBlocco);?>
</div>
<div class="panel-body image_panel">
	<div class="preview_image<?php echo $stringImmagine;?>"></div>
	<?php echo $form["immagine".$stringImmagine];?>
	<div class="cancella_immagine_box<?php echo $stringImmagine;?>">
		<a title="cancella immagine" class="cancella_immagine<?php echo $stringImmagine;?>" href="#"><span class="glyphicon glyphicon-remove"></span></a>
	</div>
	<div class="scarica_immagine_box<?php echo $stringImmagine;?>">
		<a target="_blank" title="scarica immagine" class="scarica_immagine<?php echo $stringImmagine;?>" href="#"><span class="glyphicon glyphicon-download"></span></a>
	</div>
	<span class="btn btn-success fileinput-button">
		<i class="fa fa-plus"></i>
		<span>SELEZIONA IMMAGINE</span>
		<!-- The file input field used as target for the file upload widget -->
		<input id="userfile<?php echo $stringImmagine;?>" type="file" name="Filedata">
	</span>
	<div style="display:none;margin-top:10px;" id="progress<?php echo $stringImmagine2;?>" class="progress">
		<div class="progress-bar progress-bar-success"></div>
	</div>
	<div class="alert-fileupload<?php echo $stringImmagine2;?>"></div>
</div>