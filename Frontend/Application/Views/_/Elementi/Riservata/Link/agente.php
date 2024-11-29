<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_agenti") && User::$isAgente) { ?>
<li class="<?php if ($attiva == "promozioni") { ?>uk-active<?php } ?>">
	<a href="<?php echo $this->baseUrl."/promozioni/elenco/";?>" title="<?php echo gtext("Codici coupon", false);?>"><?php echo gtext("Codici coupon");?></a>
</li>
<li class="<?php if ($attiva == "ordinicollegati") { ?>uk-active<?php } ?>">
	<a href="<?php echo $this->baseUrl."/ordini-collegati/";?>" title="<?php echo gtext("Ordini collegati", false);?>"><?php echo gtext("Ordini collegati");?></a>
</li>
<?php } ?>
