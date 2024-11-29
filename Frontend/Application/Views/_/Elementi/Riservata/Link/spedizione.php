<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_spedizione_area_riservata")) { ?>
<li class="<?php if ($attiva == "indirizzi") { ?>uk-active<?php } ?>">
	<a href="<?php echo $this->baseUrl."/riservata/indirizzi";?>" title="<?php echo gtext("Spedizione", false);?>"><?php echo gtext("Spedizione");?></a>
</li>
<?php } ?>
