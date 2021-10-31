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

<div id="modale_cookie" class="uk-flex-top" uk-modal>
    <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">
		<button class="uk-modal-close-default" type="button" uk-close></button>
		<p><?php echo gtext("Questo sito utilizza cookie per migliorare la tua esperienza di navigazione. Cliccando su ACCETTO o continuando a navigare ne consenti l'utilizzo.");?>
		
		<?php if (isset($tipiPagina["COOKIE"])) { ?>
		<a class="" href="<?php echo $this->baseUrl."/".getUrlAlias($tipiPagina["COOKIE"]);?>"><?php echo gtext("Ulteriori informazioni");?></a>
		<?php } ?></p>
		
		<div class="uk-margin"><a class="ok_cookies uk-button uk-button-secondary" title="<?php echo gtext("accetto", false);?>" href="#"><?php echo gtext("Accetto");?></a></div>
    </div>
</div>
<?php } ?>


