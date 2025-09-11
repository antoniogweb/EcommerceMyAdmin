<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_notifiche_utenti")) { ?>
<li class="<?php if ($attiva == "notifiche") { ?>uk-active<?php } ?> uk-position-relative">
	<span class="uk-position-center-right uk-badge" style="margin-top: 5px;"><?php echo RegusersnotificheModel::numero();?></span>
	<a href="<?php echo $this->baseUrl."/user-notifications/";?>" title="<?php echo gtext("Notifiche", false);?>"><?php echo gtext("Notifiche");?></a>
</li>
<?php } ?>