<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (isset($_COOKIE["ok_cookie"])) { ?>
<div id="modaleMaps" uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-body">
			<h3><?php echo gtext("Google Maps è stato bloccato per le tue impostazioni sulla privacy")?></h3>
            <p class="uk-text-small"><?php echo gtext("Se carichi il contenuto bloccato, le tue preferenze sulla privacy verranno aggiornate.");?> <?php echo gtext("Se non hai accettato di attivare i cookie di terze parti con finalità di analisi e di marketing, tale scelta verrà modificata nel momento in cui approverai di sbloccare l'utilizzo della mappa di Google.");?></p>
            
            <p class="uk-text-small">
				<?php include(tpf("Elementi/GDPR/generico_cookie.php"));?>
            </p>
        </div>
        <div class="uk-modal-footer uk-text-right">
			<a class="ajlink uk-width-1-1 uk-button uk-button-primary" title="<?php echo gtext("accetto", false);?>" href="<?php echo Url::getRoot()."accept-cookies?".v("var_query_string_no_cookie")."=Y".(VariabiliModel::$usatiCookieTerzi ? "&all_cookie=Y" : "");?>">
				<?php echo gtext("Accetta la privacy e sblocca la mappa");?>
			</a>
        </div>
    </div>
</div>

<script>
setTimeout(function(){
	UIkit.modal("#modaleMaps").show();
}, 1000);
</script>
<?php } ?>
