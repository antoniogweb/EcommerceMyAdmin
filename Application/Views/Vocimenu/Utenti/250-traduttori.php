<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_gestione_traduttori")) { ?>
<li class="<?php echo tm($tm, "traduttori");?>"><a href="<?php echo $this->baseUrl."/traduttori/main/1";?>"><i class="fa fa-magic"></i> <span><?php echo gtext("Traduttori automatici");?></span></a></li>
<?php } ?>
