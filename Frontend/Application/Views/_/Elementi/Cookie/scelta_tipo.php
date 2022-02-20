<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (PagesModel::$currentTipoPagina == "COOKIE")
	include(tpf("Elementi/Cookie/link_pagina_info_privacy.php"));
?>

<div style="bottom:0px !important;" class="segnalazione_cookies_ext uk-background-secondary segnalazione_cookies_ext_pag_cookies" id="segnalazione_cookies_ext">
	<div id="segnalazione_cookies">
		<?php echo gtext("Seleziona le tue preferenze sui cookie.");?>
		<form action="<?php echo Domain::$name."/accept-cookies?".v("var_query_string_no_cookie");?>" method="GET">
			<?php echo Html_Form::hidden(v("var_query_string_no_cookie"),"Y");?>
			<?php echo Html_Form::hidden("redirect",RegusersModel::$redirect);?>
			<div class="uk-margin">
				<?php echo Html_Form::checkbox("accetto",1,1,null,null,"disabled readonly");?> <span class="uk-margin-small-left uk-margin-small-right"><?php echo gtext("Cookie tecnici");?></span>
				<?php
				$valoreCookieTerzi = isset($_COOKIE["ok_cookie_terzi"]) ? 1 : 0;
				
				echo Html_Form::checkbox("all_cookie",$valoreCookieTerzi,1);?> <span class="uk-margin-small-left"><?php echo gtext("Cookie terze parti");?></span>
			</div>
			<button type="submit" class="uk-button uk-button-secondary"><span uk-icon="check"></span> <?php echo gtext("Salva le preferenze");?></button>
		</form>
	</div>
</div>
