<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php $idCookie = PagineModel::gTipoPagina("COOKIE"); ?>

<div style="max-height:200px;overflow:auto;" class="uk-margin <?php if (v("stile_popup_cookie") == "cookie_stile_css") { ?>uk-container<?php } ?> uk-text-justify">
	<?php echo gtext("Questo sito utilizza cookie tecnici e funzionali per migliorare la tua esperienza di navigazione.");?> 
	<?php if (VariabiliModel::$usatiCookieTerzi) { ?>
	<?php echo gtext("Con il tuo consenso vorremmo attivare cookie con finalità di analisi e di marketing, allo scopo di migliorare la tua esperienza di navigazione e di mostrarti prodotti e servizi di tuo interesse.");?> 
		<?php if ($idCookie) { ?>
		<?php echo gtext("Puoi modificare le tue impostazioni in qualsiasi momento nella pagina delle"); ?>
		<a target="_blank" href="<?php echo $this->baseUrl."/".getUrlAlias($idCookie);?>"><?php echo gtext("condizioni sui cookie."); ?></a>
		<?php } else { ?>
		<?php echo gtext("Puoi modificare le tue impostazioni in qualsiasi momento nella pagina delle condizioni sui cookie."); ?>
		<?php } ?>
	<?php echo gtext("Nella stessa pagina troverai informazioni sul responsabile della gestione dei tuoi dati, il trattamento dei dati personali e le finalità di tale trattamento.")?>
	<?php } else { ?>
		<?php if ($idCookie) { ?>
		<?php echo gtext("Nella")?> 
		<a target="_blank" href="<?php echo $this->baseUrl."/".getUrlAlias($idCookie);?>">
		<?php echo gtext("pagina delle condizioni sui cookie")?>
		</a>
		<?php echo gtext("troverai informazioni sul responsabile della gestione dei tuoi dati, il trattamento dei dati personali e le finalità di tale trattamento.")?>
		<?php } else { ?>
		<?php echo gtext("Nella pagina delle condizioni sui cookie troverai informazioni sul responsabile della gestione dei tuoi dati, il trattamento dei dati personali e le finalità di tale trattamento.")?>
		<?php } ?>
	<?php } ?>
</div>
