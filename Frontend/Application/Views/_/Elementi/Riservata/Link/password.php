<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<li class="<?php if ($attiva == "password") { ?>uk-active<?php } ?>">
	<a href="<?php echo $this->baseUrl."/modifica-password";?>" title="<?php echo gtext("Modifica password", false);?>"><?php echo gtext("Modifica password");?></a>
</li>
