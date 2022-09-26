<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (trim($promo["testo"])) { ?>
<b><?php echo gtext("Ecco la dedica che accompagna la Gift Card");?>:</b><br /><br />
<i><?php echo nl2br($promo["testo"]);?></i>
<?php } ?>
