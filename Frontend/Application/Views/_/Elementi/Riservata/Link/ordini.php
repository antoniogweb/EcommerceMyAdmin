<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_ordini_in_ecommerce")) { ?>
<li class="<?php if ($attiva == "ordini") { ?>uk-active<?php } ?>">
	<a href="<?php echo $this->baseUrl."/ordini-effettuati";?>" title="<?php echo gtext("Ordini effettuati", false);?>"><?php echo gtext("Ordini effettuati");?></a>
</li>
<?php } ?>