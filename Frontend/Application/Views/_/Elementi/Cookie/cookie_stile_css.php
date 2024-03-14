<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!isset($_COOKIE["ok_cookie"])) { ?>
<script>
$ = jQuery;

var myTimeOut;

$(document).ready(function(){

	myTimeOut = setTimeout(function(){ 
	
		$(".segnalazione_cookies_ext").animate({bottom: "0px"});
	
	}, 2000);
	
});
</script>
<script src="<?php echo $this->baseUrlSrc."/admin/Frontend/Public/Js/Minified/"?>cookies.min.js"></script>

<div class="box_esterno_cookies <?php echo v("classe_ext_cookies")?>" id="segnalazione_cookies_ext">
	<div id="segnalazione_cookies">
		<?php if (v("attiva_x_chiudi_banner_cookie")) { ?><div class="uk-position-top-right uk-padding-small"><a title="<?php echo gtext("Chiudendo il banner non attiverai i cookie di profilazione. I cookie tecnici resteranno attivi.");?>" class="ok_cookie" href="<?php echo $this->baseUrl."/accept-cookies?".v("var_query_string_no_cookie")."=Y";?>" uk-icon="icon: close"></a></div><?php } ?>
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
