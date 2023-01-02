<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="<?php if (v("stile_popup_cookie") != "cookie_stile_modale") { ?>uk-container<?php } ?> <?php if (!User::$isPhone) { ?>uk-flex uk-flex-between<?php } ?>">
	<a class="<?php echo v("cookies_confirm_button");?>" title="<?php echo gtext("accetto", false);?>" href="<?php echo $this->baseUrl."/accept-cookies?".v("var_query_string_no_cookie")."=Y".(VariabiliModel::$usatiCookieTerzi ? "&all_cookie=Y" : "");?>">
		<span uk-icon="icon: check"></span>
		<?php if (VariabiliModel::$usatiCookieTerzi) { ?>
		<?php echo gtext("Accetta");?>
		<?php } else { ?>
		<?php echo gtext("Chiudi");?>
		<?php } ?>
	</a>
	<?php if (VariabiliModel::$usatiCookieTerzi) { ?>
	<a style="" class="preferenze_cookies <?php echo v("cookies_preferenze_button");?>" title="<?php echo gtext("personalizza", false);?>" href="#">
		<span uk-icon="icon: cog"></span>
		<?php echo gtext("Preferenze");?>
	</a>
	<?php } ?>
</div>
