<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<li class="<?php if ($attiva == "dashboard") { ?>uk-active<?php } ?>">
	<a href="<?php echo $this->baseUrl."/area-riservata";?>" title="<?php echo gtext("Area riservata", false);?>"><?php echo gtext("Area riservata");?></a>
</li>
