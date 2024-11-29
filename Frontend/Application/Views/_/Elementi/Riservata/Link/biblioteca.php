<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_biblioteca_documenti")) { ?>
<li class="<?php if ($attiva == "documenti") { ?>uk-active<?php } ?>">
	<a href="<?php echo $this->baseUrl."/biblioteca-documenti/";?>" title="<?php echo gtext("Biblioteca documenti", false);?>"><?php echo gtext("Biblioteca documenti");?></a>
</li>
<?php } ?>
