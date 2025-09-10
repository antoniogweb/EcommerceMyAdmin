<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_notifiche_utenti")) { ?>
<li class="<?php if ($attiva == "notifiche") { ?>uk-active<?php } ?>">
	<a href="<?php echo $this->baseUrl."/user-notifications/";?>" title="<?php echo gtext("Notifiche", false);?>"><?php echo gtext("Notifiche");?></a>
</li>
<?php } ?>