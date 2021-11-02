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
		<?php include(tpf("Elementi/Cookie/Varianti/".v("stile_check_cookie").".php")); ?>
    </div>
</div>
<?php } ?>
