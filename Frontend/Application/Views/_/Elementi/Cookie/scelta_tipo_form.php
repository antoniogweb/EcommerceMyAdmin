<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="uk-container uk-container-small uk-text-left">
	<div class="uk-margin"><b><?php echo gtext("Seleziona le tue preferenze sui cookie.");?></b></div>
	<form action="<?php echo Domain::$name."/accept-cookies";?>" method="GET">
		<?php echo Html_Form::hidden(v("var_query_string_no_cookie"),"Y");?>
		<?php echo Html_Form::hidden("redirect",RegusersModel::$redirect);?>
		<div class="uk-margin-top">
			<?php echo Html_Form::checkbox("accetto",1,1,null,null,"disabled readonly");?> <span class="uk-margin-small-left uk-margin-small-right"><?php echo gtext("Cookie tecnici");?></span>
			<br />
			<?php
			$valoreCookieTerzi = isset($_COOKIE["ok_cookie_terzi"]) ? 1 : 0;
			
			echo Html_Form::checkbox("all_cookie",$valoreCookieTerzi,1);?> <span class="uk-margin-small-left"><?php echo gtext("Cookie statistiche + marketing");?></span>
		</div>
		<div class="<?php if (!User::$isPhone) { ?>uk-flex uk-flex-between<?php } ?>">
			<button type="submit" class="submit_preferenze <?php echo v("cookies_save_pref")?>"><!--<span uk-icon="check"></span>--> <?php echo gtext("Approva selezionati");?></button>
			<?php if (!isset($_COOKIE["ok_cookie"]) && !VariabiliModel::checkToken("var_query_string_no_cookie")) { ?>
			<a class="<?php echo v("cookies_confirm_button");?>" title="<?php echo gtext("accetto", false);?>" href="">
				<?php echo gtext("Approva tutti");?>
			</a>
			<?php } ?>
		</div>
	</form>
</div>
