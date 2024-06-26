<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<h3><?php echo $servizioBloccato." ".gtext("è stato bloccato per le tue impostazioni sulla privacy")?></h3>
<p class="uk-text-small"><?php echo gtext("Se attivi $servizioBloccato, le tue preferenze sulla privacy verranno aggiornate.");?> <?php echo gtext("Se non hai accettato di attivare i cookie di terze parti con finalità di analisi e di marketing, tale scelta verrà modificata nel momento in cui approverai di sbloccare l'utilizzo di")." ".$servizioBloccato;?></p>

<p class="uk-text-small">
	<?php include(tpf("Elementi/GDPR/generico_cookie.php"));?>
</p>
