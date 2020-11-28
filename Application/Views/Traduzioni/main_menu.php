<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php echo $menu; ?>

<div id="box_form_import_traduzioni" class="box collapse">
	<div class="box-header with-border main">
		<form class="list_filter_form list_filter_form_top" action="<?php echo $this->baseUrl."/traduzioni/carica"?>" method="POST" enctype="multipart/form-data">
			<span class="btn btn-default fileinput-button-custom">
				<i class="fa fa-file"></i>
				<span>Seleziona il file</span>
				<!-- The file input field used as target for the file upload widget -->
				<?php echo Html_Form::fileUpload("file", "", "form-control");?>
			</span>
			
			<button style="display:none;" class="btn btn-warning upload_traduzioni"><i class="fa fa-upload"></i> Carica</button>
		</form>
	</div>
</div>
