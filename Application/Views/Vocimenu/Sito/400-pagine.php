<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_standard_cms_menu")) { ?>
<li class="<?php echo tm($tm, "pagine");?> help_pagine">
	<a href="<?php echo $this->baseUrl."/pagine/main";?>">
		<i class="fa fa-folder-open"></i> <span><?php echo gtext("Pagine");?></span>
	</a>
</li>
<?php } ?>
