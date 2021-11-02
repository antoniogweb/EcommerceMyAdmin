<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="uk-margin">
	<a class="uk-margin uk-button uk-button-primary uk-width-1-3@m" title="<?php echo gtext("accetto", false);?>" href="<?php echo Domain::$name."/accept-cookies?all_cookie&".v("var_query_string_no_cookie");?>"><?php echo gtext("Accetta tutti i cookie");?></a>
	<a style="float:right;" class="cookie_personalizza uk-button uk-button-default uk-width-1-3@m" title="<?php echo gtext("accetto", false);?>" href="<?php echo Domain::$name."/accept-cookies?".v("var_query_string_no_cookie");?>"><?php echo gtext("Accetta solo i cookie tecnici");?></a>
</div>
