<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_gestiobe_ticket")) { ?>
<li class=" <?php if ($attiva == "ticket") { ?>uk-active<?php } ?>">
	<a href="<?php echo $this->baseUrl."/ticket/";?>" title="<?php echo gtext("Assistenza", false);?>"><?php echo gtext("Assistenza");?></a>
</li>
<?php } ?>
