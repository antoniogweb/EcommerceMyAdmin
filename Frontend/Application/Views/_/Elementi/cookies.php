<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!isset($_COOKIE["ok_cookie"])) { ?>
<script>
$ = jQuery;

$(document).ready(function(){

	setTimeout(function(){ 
	
		$("#segnalazione_cookies_ext").animate({bottom: "0px"});
	
	}, 2000);
	
	$(".ok_cookies").click(function(e){
	
		e.preventDefault();
		
		$("#segnalazione_cookies_ext").animate({bottom: "-150px"});
		
		$.ajax({
			type: "GET",
			url: baseUrl + "/home/settacookie",
			async: true,
			cache:false,
			dataType: "html",
			success: function(content){}
		});
		
	});
	
});
</script>

<div id="segnalazione_cookies_ext">
	<div id="segnalazione_cookies">
		<?php echo gtext("Questo sito utilizza cookie per migliorare la tua esperienza di navigazione. Cliccando su OK o continuando a navigare ne consenti l'utilizzo.");?>
		
		<?php if (isset($tipiPagina["COOKIE"])) { ?>
		<b><a href="<?php echo $this->baseUrl."/".getUrlAlias($tipiPagina["COOKIE"]);?>"><?php echo gtext("Ulteriori informazioni");?></a></b>
		<?php } ?>
		.
		<a class="ok_cookies" title="<?php echo gtext("accetto", false);?>" href="#">OK</a>
	</div>
</div>
<?php } ?>
