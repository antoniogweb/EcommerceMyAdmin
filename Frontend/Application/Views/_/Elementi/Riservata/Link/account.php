<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<li class="<?php if ($attiva == "account") { ?>uk-active<?php } ?>">
	<a href="<?php echo $this->baseUrl."/modifica-account";?>" title="<?php echo gtext("I miei dati", false);?>"><?php echo gtext("I miei dati");?></a>
</li>
