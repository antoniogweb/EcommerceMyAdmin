<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!isset($_COOKIE["ok_cookie"])) { ?>
<script>
$ = jQuery;

var myTimeOut;

$(document).ready(function(){

	myTimeOut = setTimeout(function(){ 
		
		UIkit.modal("#modale_cookie").show();
	
	}, 1000);
	
});
</script>
<script src="<?php echo $this->baseUrlSrc."/admin/Frontend/Public/Js/"?>cookies.js"></script>

<div id="modale_cookie" class="uk-flex-top" uk-modal>
    <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">
		<?php include(tpf("Elementi/Cookie/chiudi_banner.php"));?>
		<?php include(tpf("Elementi/Cookie/testo_popup_cookies.php"));?>
		
		<div class="accetta_approfondisci">
			<?php include(tpf("Elementi/Cookie/Varianti/".v("stile_check_cookie").".php")); ?>
		</div>
		<div class="form_scelta uk-hidden">
			<?php include(tpf("Elementi/Cookie/scelta_tipo_form.php"));?>
		</div>
    </div>
</div>
<?php } ?>
