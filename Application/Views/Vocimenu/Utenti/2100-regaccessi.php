<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_sezione_accessi_utenti")) { ?>
	<li class="<?php echo tm($tm, "regaccessi");?>"><a href="<?php echo $this->baseUrl."/regaccessi/main/1";?>"><i class="fa fa-sign-in"></i> <span><?php echo gtext("Accessi utenti");?></span></a></li>
<?php } ?>