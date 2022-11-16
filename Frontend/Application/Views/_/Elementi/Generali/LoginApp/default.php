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
			<div>
				<div class="<?php echo $widthPulsante;?> uk-button uk-button-default uk-light spinner uk-hidden" <?php if ($lApp["colore_background_in_esadecimale"]) { ?>style="border:1px solid <?php echo $lApp["colore_background_in_esadecimale"];?>!important; color:#FFF !important;background-color:<?php echo $lApp["colore_background_in_esadecimale"];?>!important;"<?php } ?> uk-spinner="ratio: .70"></div>
				<a class="<?php echo $widthPulsante;?> uk-button uk-button-default uk-light btn_submit_form" <?php if ($lApp["colore_background_in_esadecimale"]) { ?>style="border:1px solid <?php echo $lApp["colore_background_in_esadecimale"];?>!important; color:#FFF !important;;background-color:<?php echo $lApp["colore_background_in_esadecimale"];?>!important;"<?php } ?> href="<?php echo $this->baseUrl."/regusers/loginapp/".$lApp["codice"]."?csrf_code=$csrf_code".$redirectQ;?>">
					<?php
					if ($lApp["html_icona"])
						echo htmlentitydecode($lApp["html_icona"]);
					?>
					<?php echo gtext("accedi tramite")." ".$lApp["titolo"];?>
				</a>
			</div>
		</div>
	<?php } ?>
</div>
<?php } ?>
