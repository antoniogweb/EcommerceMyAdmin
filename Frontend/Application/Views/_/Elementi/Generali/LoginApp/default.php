<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (isset($elencoAppLogin) && count($elencoAppLogin) > 0 && v("abilita_login_tramite_app")) {
	$redirectQ = RegusersModel::$redirectQueryString ? "&".ltrim(RegusersModel::$redirectQueryString,"?") : "";
	
	if (!isset($widthPulsante))
		$widthPulsante = "uk-width-1-2@s uk-width-1-1@m";
?>
<div class="uk-margin-large-top">
	<?php foreach ($elencoAppLogin as $lApp) { ?>
		<div class="uk-margin">
			<h4 class=""><?php echo $lApp["titolo"];?> <?php echo gtext("Connect");?></h4>
			<a class="<?php echo $widthPulsante;?> uk-button uk-button-default uk-light" <?php if ($lApp["colore_background_in_esadecimale"]) { ?>style="color:#FFF;background-color:<?php echo $lApp["colore_background_in_esadecimale"];?>"<?php } ?> href="<?php echo $this->baseUrl."/regusers/loginapp/".$lApp["codice"]."?csrf_code=$csrf_code".$redirectQ;?>">
				<?php
				if ($lApp["html_icona"])
					echo htmlentitydecode($lApp["html_icona"]);
				?>
				<?php echo gtext("accedi tramite")." ".$lApp["titolo"];?>
			</a>
		</div>
	<?php } ?>
</div>
<?php } ?>
