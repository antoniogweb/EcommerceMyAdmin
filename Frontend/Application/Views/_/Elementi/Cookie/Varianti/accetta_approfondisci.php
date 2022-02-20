<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php include(tpf("Elementi/Cookie/testo_popup_cookies.php"));?>

<div class="<?php if (v("stile_popup_cookie") != "cookie_stile_modale") { ?>uk-container uk-container-small<?php } ?> <?php if (!User::$isPhone) { ?>uk-flex uk-flex-between<?php } ?>">
	<a class="<?php echo v("cookies_confirm_button");?>" title="<?php echo gtext("accetto", false);?>" href="#">
		<span uk-icon="icon: check"></span>
		<?php echo gtext("Accetta");?>
	</a>
	<?php if (isset($tipiPagina["COOKIE"])) { ?>
	<a style="" class="<?php echo v("cookies_preferenze_button");?>" title="<?php echo gtext("personalizza", false);?>" href="<?php echo $this->baseUrl."/".getUrlAlias($tipiPagina["COOKIE"])."?".v("var_query_string_no_cookie").PagesModel::getRedirectQuery("&");?>">
		<span uk-icon="icon: cog"></span>
		<?php echo gtext("Preferenze");?>
	</a>
	<?php } ?>
</div>
