<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<!-- carico i file JS di jquery file upload-->
<link rel="stylesheet" href="<?php echo $this->baseUrlSrc?>/Public/Js/jQuery-File-Upload-9.18.0/css/jquery.fileupload.css">
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script type="text/javascript" src="<?php echo $this->baseUrlSrc?>/Public/Js/jQuery-File-Upload-9.18.0/js/vendor/jquery.ui.widget.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script type="text/javascript" src="<?php echo $this->baseUrlSrc?>/Public/Js/jQuery-File-Upload-9.18.0/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script type="text/javascript" src="<?php echo $this->baseUrlSrc?>/Public/Js/jQuery-File-Upload-9.18.0/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script type="text/javascript" src="<?php echo $this->baseUrlSrc?>/Public/Js/jQuery-File-Upload-9.18.0/js/jquery.fileupload-process.js"></script>

<script type="text/javascript" src="<?php echo $this->baseUrlSrc?>/Public/Js/jQuery-File-Upload-9.18.0/js/jquery.fileupload-validate.js"></script>

<script>

function aggiornaOrdinamento()
{
	var id_img = "";
	var order = "";
	
	$(".box_thumb").each(function(){
	
		var id_img = $(this).attr("data-id");
	
		order += id_img + ",";
	
	});
	
	var post_data = "order="+order+"&ordinaImmagini=Y";
	
	$.ajax({
		type: "POST",
		data: post_data,
		url: "<?php echo $this->baseUrl.'/immagini/ordina/';?>",
		async: false,
		cache:false,
		success: function(html){
			
		}
	});
}

$(function() {
	$( "#thumb_box_right" ).sortable({
		items: ".box_thumb",
		stop: function( event, ui ) {
			aggiornaOrdinamento();
		}
	});
	
	$("body").on("click", ".a_moveup,.a_movedown,.a_del,.a_rotate_o", function(e){
// 				$(className).unbind();
		var link_url = $(this).attr("href");
		$.ajaxQueue({
			url: link_url,
			async: false,
			cache:false,
			success: function(html){
				
				import_thumb();
			}
		});
		
		return false;
	});
	
// 	import_thumb();
});

//funzione per importare le immagini dei lavori
function import_thumb()
{
	$.ajaxQueue({
		url: "<?php echo $this->baseUrl.'/immagini/view/'.$id_page."/".(int)$this->viewArgs["id_cmb"];?>",
		async: false,
		cache:false,
		success: function(html){

			$("#thumb_box_right").empty();
			$("#thumb_box_right").append(html);
		}
	});
}
	
var allowed = [ "jpg","jpeg","png","gif" ];

var url = '<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/move";?>';

$(function () {
	'use strict';
	$('#userfile').fileupload({
		url: url,
		autoUpload: true,
		formData: {
			'token':'<?php echo $token;?>',
			'id_page':'<?php echo $id_page; ?>',
			'id_cmb':<?php echo (int)$this->viewArgs["id_cmb"];?>
		},
		done: function (e, data) {
			if (data.result.result != "OK")
				$(".alert-fileupload").append("<div class='alert alert-danger'>" + data.result.error + "</div>");
			
			import_thumb();
		},
		change: function (e, data) {
			
			$(".alert-fileupload").html("");
			
			$('#progress').css("display","block");
			
			$('#progress .progress-bar').css(
				'width',
				0 + '%'
			);
		},
		processdone: function (e, data) {
			
		},
		progressall: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
			$('#progress .progress-bar').css(
				'width',
				progress + '%'
			);
		}
	}).prop('disabled', !$.support.fileInput)
		.parent().addClass($.support.fileInput ? undefined : 'disabled');
});

$(document).ready(function(){

	import_thumb();

});

</script>

<section class="content-header">
	<?php if (!isset($pageTitle)) { ?>
	<h1>Gestione <?php echo $tabella;?>: <?php echo $titoloPagina; ?></h1>
	<?php } else { ?>
	<h1><?php echo $pageTitle;?></h1>
	<?php } ?>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<?php if (!nobuttons()) { ?>
			<!-- show the top menù -->
			<div class='mainMenu'>
				<?php echo $menu;?>
			</div>
			
			<?php include($this->viewPath("steps"));?>
			<?php } ?>
			
			<div class="box">
				<div class="box-header with-border main">
					

					<div id="form_images" class="form_images">

						<div class="images_preview">
							<div>
								<form action='' method='POST' enctype="multipart/form-data">
									<span class="btn btn-success fileinput-button">
										<i class="fa fa-plus-circle"></i>
										<span><?php echo gtext("SELEZIONA LE IMMAGINI");?></span>
										<!-- The file input field used as target for the file upload widget -->
										<input id="userfile" type="file" name="Filedata" multiple>
									</span>
									<div style="display:none;margin-top:10px;" id="progress" class="progress">
										<div class="progress-bar progress-bar-success"></div>
									</div>
									<div class="alert-fileupload"></div>
									
									<input type="hidden" name="id_page" value="<?php echo $id_page; ?>">
								</form>
							</div>
						</div>
					</div>
                </div>
			</div>
			
			<div id="thumb_box_right">

							</div>
		</div>
		
		
	</div>
</section>
