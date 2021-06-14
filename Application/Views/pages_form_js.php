<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrlSrc;?>/Public/Js/clockpicker-gh-pages/dist/bootstrap-clockpicker.min.css">
<script type="text/javascript" src="<?php echo $this->baseUrlSrc;?>/Public/Js/clockpicker-gh-pages/dist/bootstrap-clockpicker.min.js"></script>

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

<?php if (strcmp($use_editor,"Y") === 0) { ?>
<script type="text/javascript" src="<?php echo $this->baseUrlSrc?>/Public/Js/tiny_mce/jquery.tinymce.js"></script>
<?php } else { ?>
<script src="<?php echo $this->baseUrlSrc?>/Public/Js/cheef-jquery-ace/ace/ace.js"></script>
<script src="<?php echo $this->baseUrlSrc?>/Public/Js/cheef-jquery-ace/ace/theme-dreamweaver.js"></script>
<script src="<?php echo $this->baseUrlSrc?>/Public/Js/cheef-jquery-ace/ace/mode-ruby.js"></script>
<script src="<?php echo $this->baseUrlSrc?>/Public/Js/cheef-jquery-ace/jquery-ace.min.js"></script>
<?php } ?>

<script type="text/javascript">

function updateForm()
{
	if ($(".in_promozione option:selected").length > 0)
	{
		var in_promozione = $(".in_promozione option:selected").attr("value");
		
		if (in_promozione == "Y")
		{
			$(".class_promozione").css("display","block");
		}
		else
		{
			$(".class_promozione").css("display","none");
		}
	}
}

$().ready(function() {
<?php if (strcmp($use_editor,"Y") === 0) { ?>
	$('textarea.dettagli').tinymce(tiny_editor_config);
	<?php } else { ?>
		$('textarea.dettagli').ace({ theme: 'dreamweaver', lang: 'ruby' })
	<?php } ?>
});

function mostraEditor()
{
	$('input[name="use_editor"]').val("Y");
}

function nascondiEditor()
{
	$('input[name="use_editor"]').val("N");
}

$(document).ready(function() {
	
	$( ".data_field" ).datepicker( {
		dateFormat: 'dd-mm-yy'
	} );
	
	updateForm();
	
	$(".in_promozione").change(function(){
		
		updateForm();
	
	});
	
	show_preview();
	
	$(".cancella_immagine").click(function(e){
	
		$("input[name='immagine']").val("");
		
		show_preview();
		
		e.preventDefault();
	
	});
	
	show_preview2();
	
	$(".cancella_immagine_2").click(function(e){
	
		$("input[name='immagine_2']").val("");
		
		show_preview2();
		
		e.preventDefault();
	
	});
	
	$("select[name='use_editor']").change(function(){
	
		reloadPage();
		
	});
	
	$(".clockpicker").clockpicker({
		"donetext" : "Seleziona"
	});
	
});

//funzione per importare le immagini dei lavori
function show_preview()
{
	var immagine = $("input[name='immagine']").val();
	
	if (immagine != "")
	{
		$(".preview_image").html("<img src='<?php echo $this->baseUrl.'/thumb/mainimage/';?>" + immagine + "' />");
		$(".cancella_immagine_box").css("display", "block");
		$(".scarica_immagine_box").css("display", "block");
		$(".scarica_immagine").attr("href","<?php echo Domain::$name."/images/contents/";?>"+immagine);
	}
	else
	{
		$(".preview_image").html("<p>Non è stata caricata alcuna immagine</p>");
		$(".cancella_immagine_box").css("display", "none");
		$(".scarica_immagine_box").css("display", "none");
	}
}

//funzione per importare le immagini dei lavori
function show_preview2()
{
	var immagine = $("input[name='immagine_2']").val();
	
	if (immagine != "")
	{
		$(".preview_image_2").html("<img src='<?php echo $this->baseUrl.'/thumb/mainimage/';?>" + immagine + "' />");
		$(".cancella_immagine_box_2").css("display", "block");
		$(".scarica_immagine_box_2").css("display", "block");
		$(".scarica_immagine_2").attr("href","<?php echo Domain::$name."/images/contents/";?>"+immagine);
	}
	else
	{
		$(".preview_image_2").html("<p>Non è stata caricata alcuna immagine</p>");
		$(".cancella_immagine_box_2").css("display", "none");
		$(".scarica_immagine_box_2").css("display", "none");
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
			'id_page':'<?php echo $id_page; ?>',
			'is_main':'1'
		},
		done: function (e, data) {
			$("input[name='immagine']").val(data.result);
			show_preview();
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
			var fileExt = fileExt.toLowerCase();
			
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

$(function () {
    'use strict';
	$('#userfile_2').fileupload({
		url: url,
		autoUpload: true,
		formData: {
			'token':'<?php echo $token;?>',
			'id_page':'<?php echo $id_page; ?>',
			'is_main':'1'
		},
		done: function (e, data) {
			$("input[name='immagine_2']").val(data.result);
			show_preview2();
		},
		change: function (e, data) {
			
			$(".alert-fileupload-2").html("");
			
			$('#progress-2').css("display","block");
			
			$('#progress-2 .progress-bar').css(
				'width',
				0 + '%'
			);
		},
		processdone: function (e, data) {
			
			var fileName = data.files[0].name;
			
			var fileExt = fileName.split('.').pop();
			var fileExt = fileExt.toLowerCase();
			
			if (allowed.indexOf(fileExt) == -1)
			{
				$(".alert-fileupload-2").append("<div class='alert alert-danger'>Attenzione, il file <b>" + fileName + "</b> non può essere caricato perché la sua estensione non è ammessa");
			}
		},
		progressall: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
			$('#progress-2 .progress-bar').css(
				'width',
				progress + '%'
			);
		}
	}).prop('disabled', !$.support.fileInput)
		.parent().addClass($.support.fileInput ? undefined : 'disabled');
});

</script>
