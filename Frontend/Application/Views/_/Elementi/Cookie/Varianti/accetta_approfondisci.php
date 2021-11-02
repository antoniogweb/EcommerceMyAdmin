<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtext("Questo sito utilizza cookie per migliorare la tua esperienza di navigazione. Cliccando su ACCETTO o continuando a navigare ne consenti l'utilizzo.");?>

<?php if (isset($tipiPagina["COOKIE"])) { ?>
<a class="" href="<?php echo $this->baseUrl."/".getUrlAlias($tipiPagina["COOKIE"]);?>"><?php echo gtext("Ulteriori informazioni");?></a>
<?php } ?></p>

<div class="uk-margin uk-container <?php if (!User::$isPhone) { ?>uk-container-small uk-flex uk-flex-around<?php } ?>">
	<a class="uk-margin-top uk-width-1-1 uk-width-1-3@s ok_cookies cookie_accetta uk-button uk-button-primary" title="<?php echo gtext("accetto", false);?>" href="#">
		<span uk-icon="icon: check"></span>
		<?php echo gtext("Accetta");?>
	</a>
	<?php if (isset($tipiPagina["COOKIE"])) { ?>
	<a style="" class="cookie_personalizza uk-margin-top uk-width-1-1 uk-width-1-3@s uk-button uk-button-default" title="<?php echo gtext("personalizza", false);?>" href="<?php echo $this->baseUrl."/".getUrlAlias($tipiPagina["COOKIE"])."?".v("var_query_string_no_cookie");?>">
		<span uk-icon="icon: cog"></span>
		<?php echo gtext("Personalizza");?>
	</a>
	<?php } ?>
</div>
