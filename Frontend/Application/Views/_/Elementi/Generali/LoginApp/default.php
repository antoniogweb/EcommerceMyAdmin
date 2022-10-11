<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (isset($elencoAppLogin) && count($elencoAppLogin) > 0 && v("abilita_login_tramite_app")) { ?>
<div class="uk-margin-large-top">
	<?php foreach ($elencoAppLogin as $lApp) { ?>
		<div class="uk-margin">
			<a class="uk-button uk-button-default uk-light" <?php if ($lApp["colore_background_in_esadecimale"]) { ?>style="color:#FFF;background-color:<?php echo $lApp["colore_background_in_esadecimale"];?>"<?php } ?> href="<?php echo $this->baseUrl."/regusers/loginapp/".$lApp["codice"]."?csrf_code=$csrf_code";?>">
				<?php
				if ($lApp["html_icona"])
					echo htmlentitydecode($lApp["html_icona"]);
				?>
				<?php echo gtext("login con")." ".$lApp["titolo"];?>
			</a>
		</div>
	<?php } ?>
</div>
<?php } ?>
