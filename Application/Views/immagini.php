<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php include($this->viewPath("uploader_scripts"));?>

<script>

function aggiornaOrdinamento()
{
	var id_img = "";
	var order = "";
	
	$(".box_thumb").each(function(){
	
		var id_img = $(this).attr("data-id");
	
		order += id_img + ",";
	
	});
	
	var post_data = "order="+order+"&ordinaPagine=Y";
	
	$.ajax({
		type: "POST",
		data: post_data,
		url: "<?php echo $this->baseUrl.'/immaginiarchivi/ordina/';?>",
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
		url: "<?php echo $this->baseUrl.'/immaginiarchivi/view/'.$id."/".$contesto;?>",
		async: false,
		cache:false,
		success: function(html){
			$("#thumb_box_right").empty();
			$("#thumb_box_right").append(html);
		}
	});
}

var url = '<?php echo $this->baseUrl."/immaginiarchivi/upload";?>';

$(function () {
	'use strict';
	$('#immagine').fileupload({
		url: url,
		autoUpload: true,
		formData: {
			'csrf':'<?php echo User::$csrfToken;?>',
			'id':'<?php echo $id; ?>',
			'contesto':'<?php echo $contesto;?>'
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
	<h1>Gestione <?php echo $tabella;?>: <?php echo $titoloRecord; ?></h1>
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
			
			<?php include($this->viewPath("categories_steps"));?>
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
										<input id="immagine" type="file" name="immagine" multiple>
									</span>
									<div style="display:none;margin-top:10px;" id="progress" class="progress">
										<div class="progress-bar progress-bar-success"></div>
									</div>
									<div class="alert-fileupload"></div>
									
									<input type="hidden" name="id" value="<?php echo $id; ?>">
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
