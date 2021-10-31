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

<div class="segnalazione_cookies_ext" id="segnalazione_cookies_ext">
	<div id="segnalazione_cookies">
		<?php echo gtext("Questo sito utilizza cookie per migliorare la tua esperienza di navigazione. Cliccando su OK o continuando a navigare ne consenti l'utilizzo.");?>
		
		<?php if (isset($tipiPagina["COOKIE"])) { ?>
		<b><a class="uk-text-bold" href="<?php echo $this->baseUrl."/".getUrlAlias($tipiPagina["COOKIE"]);?>"><?php echo gtext("Ulteriori informazioni");?></a></b>
		<?php } ?>
		
		<a class="ok_cookies" title="<?php echo gtext("accetto", false);?>" href="#">OK</a>
	</div>
</div>
<?php } ?>
