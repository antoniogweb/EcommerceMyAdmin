<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtext("Questo sito utilizza cookie per migliorare la tua esperienza di navigazione.");?><br /><?php echo gtext("Cliccando su ACCETTO o continuando a navigare ne consenti l'utilizzo.");?>

<?php if (isset($tipiPagina["COOKIE"])) { ?>
<a class="" href="<?php echo $this->baseUrl."/".getUrlAlias($tipiPagina["COOKIE"]);?>"><?php echo gtext("Ulteriori informazioni");?></a>
<?php } ?></p>

<div class="uk-margin <?php if (v("stile_popup_cookie") != "cookie_stile_modale") { ?>uk-container uk-container-small<?php } ?> <?php if (!User::$isPhone) { ?>uk-flex uk-flex-between<?php } ?>">
	<a class="uk-margin-top uk-width-1-1 uk-width-2-5@s ok_cookies cookie_accetta uk-button uk-button-primary" title="<?php echo gtext("accetto", false);?>" href="#">
		<span uk-icon="icon: check"></span>
		<?php echo gtext("Accetta");?>
	</a>
	<?php if (isset($tipiPagina["COOKIE"])) { ?>
	<a style="" class="cookie_personalizza uk-margin-top uk-width-1-1 uk-width-2-5@s uk-button uk-button-default" title="<?php echo gtext("personalizza", false);?>" href="<?php echo $this->baseUrl."/".getUrlAlias($tipiPagina["COOKIE"])."?".v("var_query_string_no_cookie");?>">
		<span uk-icon="icon: cog"></span>
		<?php echo gtext("Personalizza");?>
	</a>
	<?php } ?>
</div>
