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

<!-- <script type="text/javascript" src="<?php echo $this->baseUrlSrc?>/Public/Js/uploadify_3_2_1/jquery.uploadify.js?<?php echo date("U")?>"></script> -->

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
			<!-- show the top menù -->
			<div class='mainMenu'>
				<?php echo $menu;?>
			</div>

			<?php include($this->viewPath("steps"));?>
				
			<div class="box">
				<div class="box-header with-border main">
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
						});
						
						//funzione per importare le immagini dei lavori
						function import_thumb()
						{
							$.ajaxQueue({
								url: "<?php echo $this->baseUrl.'/immagini/view/'.$id_page;?>",
								async: false,
								cache:false,
								success: function(html){

									$("#thumb_box_right").empty();
									$("#thumb_box_right").append(html);

									partial_load(".a_moveup");
									partial_load(".a_movedown");
									partial_load(".a_del");
									partial_load(".a_rotate_o");
								}
							});

							function partial_load(className)
							{
								$(className).click(function() {
					// 				$(className).unbind();
									var link_url = $(this).attr("href");
									$.ajaxQueue({
										url: link_url,
										async: false,
										cache:false,
										success: function(html){
											
					// 						alert(html);
											import_thumb();
										}
									});
									return false;
								});
							}
						}
							
						var allowed = [ "jpg","jpeg","png","gif" ];
						
						var url = '<?php echo $this->baseUrl."/pages/move";?>';
						
						$(function () {
							'use strict';
							$('#userfile').fileupload({
								url: url,
								autoUpload: true,
								formData: {
									'token':'<?php echo $token;?>',
									'id_page':'<?php echo $id_page; ?>'
								},
								done: function (e, data) {
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
									
									var fileName = data.files[0].name;
									
									var fileExt = fileName.split('.').pop();
									
									if (allowed.indexOf(fileExt) == -1)
									{
										$(".alert-fileupload").append("<div class='alert alert-danger'>Attenzione, il file <b>" + fileName + "</b> non può essere caricato perché la sua estensione non è ammessa");
									}
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
						
// 						$(function() {
// 							$('#userfile').uploadify({
// 								'width'    : 180,
// 								'fileSizeLimit' : '<?php echo Parametri::$uploadifyMaxUploadSize;?>',
// 								'buttonText' : 'SELEZIONA I FILE',
// 								'formData'      : {'token':'<?php echo $token;?>', 'id_page':'<?php echo $id_page; ?>'},
// 								'swf'      : '<?php echo $this->baseUrl."/Public/Js/uploadify_3_2_1/";?>uploadify.swf',
// 								'uploader' : '<?php echo $this->baseUrl."/pages/move";?>',
// 								'onUploadStart' : function(file) {
// 									if (jQuery.inArray(file.name.split('.').pop().toLowerCase(), allowed) == -1)
// 									{
// 										alert("il file " + file.name + " non sarà caricato perché la sua estensione non è ammessa");
// 										$('#userfile').uploadify("cancel",file.id);
// 									}
// 								},
// 								'onUploadSuccess' : function(queueData) {
// 									import_thumb();
// 								}
// 							});
// 						});
						
						$(document).ready(function(){

							import_thumb();

						});

					</script>

					<div id="form_images" class="form_images">

						<div class="images_preview">
							<div class='EGuploadFileBox'>

								<form action='<?php echo $this->baseUrl?>/pages/form/immagini/<?php echo $id_page.$this->viewStatus;?>#form_images' method='POST' enctype="multipart/form-data">
									
									<span class="btn btn-success fileinput-button">
										<i class="fa fa-plus"></i>
										<span>SELEZIONA LE IMMAGINI</span>
										<!-- The file input field used as target for the file upload widget -->
										<input id="userfile" type="file" name="Filedata" multiple>
									</span>
									<div style="display:none;margin-top:10px;" id="progress" class="progress">
										<div class="progress-bar progress-bar-success"></div>
									</div>
									<div class="alert-fileupload"></div>
									
<!-- 									<input id="userfile" name="userfile" type="file"> -->
									
									<input type="hidden" name="id_page" value="<?php echo $id_page; ?>">
								</form>

							</div>

							<div id="thumb_box_right">

							</div>

						</div>

					</div>
                </div>
			</div>
		</div>
	</div>
</section>
