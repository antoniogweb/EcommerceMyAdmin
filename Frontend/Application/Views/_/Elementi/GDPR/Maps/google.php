<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (isset($_COOKIE["ok_cookie"])) { ?>
<div id="modaleMaps" uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-body">
			<?php
			$servizioBloccato = "Google Maps";
			include(tpf("Elementi/GDPR/servizio_generico_bloccato.php"));?>
        </div>
        <div class="uk-modal-footer uk-text-right">
			<?php include(tpf("Elementi/GDPR/link_sblocca_servizio.php"));?>
        </div>
    </div>
</div>

<script>
setTimeout(function(){
	UIkit.modal("#modaleMaps").show();
}, 1000);
</script>
<?php } ?>
