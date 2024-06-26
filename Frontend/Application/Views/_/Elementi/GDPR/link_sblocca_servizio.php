<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<a class="ajlink uk-width-1-1 uk-button uk-button-primary" title="<?php echo gtext("accetto", false);?>" href="<?php echo Url::getRoot()."accept-cookies?".v("var_query_string_no_cookie")."=Y".(VariabiliModel::$usatiCookieTerzi ? "&all_cookie=Y" : "");?>">
	<?php echo gtext("Accetta la privacy e sblocca")." ".$servizioBloccato;?>
</a>
