<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!$islogged) { ?>
<div class="">
	<div class="uk-margin">
		<div class="uk-text-small">
			<?php echo gtext("Hai già un account?");?> <a class="showlogin show_form_login_checkout" href="#"><?php echo gtext("Clicca qui per accedere");?></a><br />
			<?php echo gtext("Altrimenti continua pure inserendo i tuoi dati.");?>
		</div>
	</div>
	
	<div id="login" style="display:none;">
		<?php
		$noLoginNotice = $noLoginRegistrati = true;
		$action = $this->baseUrl."/regusers/login?redirect=/checkout";
		RegusersModel::$redirectQueryString = "redirect=checkout";
		include(tpf("/Regusers/login_form.php"));?>
		<br />
	</div>
</div>
<?php } ?>
