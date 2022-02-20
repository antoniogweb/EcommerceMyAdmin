<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!isset($_COOKIE["ok_cookie"])) { ?>
<script>
$ = jQuery;

var myTimeOut;

$(document).ready(function(){

	myTimeOut = setTimeout(function(){ 
	
		$(".segnalazione_cookies_ext").animate({bottom: "0px"});
	
	}, 1000);
});
</script>

<div class="segnalazione_cookies_ext uk-background-secondary uk-light" id="segnalazione_cookies_ext">
	<div id="segnalazione_cookies">
		<?php include(tpf("Elementi/Cookie/Varianti/".v("stile_check_cookie").".php")); ?>
	</div>
</div>
<?php } ?>
