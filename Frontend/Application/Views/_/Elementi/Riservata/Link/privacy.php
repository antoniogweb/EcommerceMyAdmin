<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<li class=" <?php if ($attiva == "privacy") { ?>uk-active<?php } ?>">
	<a href="<?php echo $this->baseUrl."/riservata/privacy";?>" title="<?php echo gtext("Gestione della privacy", false);?>"><?php echo gtext("Gestione della privacy");?></a>
</li>
