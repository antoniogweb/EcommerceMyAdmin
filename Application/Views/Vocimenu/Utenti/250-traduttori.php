<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_gestione_traduttori")) { ?>
<li class="<?php echo tm($tm, array("traduttori","opzioni","traduzionicorrezioni"));?> treeview">
	<a href="#">
		<i class="fa fa-magic"></i>
		<span><?php echo gtext("Traduttori automatici");?></span>
	</a>
	<ul class="treeview-menu">
		<li class="<?php echo tm($tm, array("traduttori"));?>"><a href="<?php echo $this->baseUrl."/traduttori/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Traduttori automatici");?></a></li>
		<?php if (OpzioniModel::isCodiceGestibile("FRASI_DA_NON_TRADURRE") && ControllersModel::checkAccessoAlController(array("opzioni"))) { ?>
		<li class="<?php echo tm($tm, array("opzioni"));?>"><a href="<?php echo $this->baseUrl."/opzioni/main?codice=FRASI_DA_NON_TRADURRE";?>"><i class="fa fa-ban"></i> <?php echo gtext("Termini da non tradurre");?></a></li>
		<?php } ?>
		<li class="<?php echo tm($tm, array("traduzionicorrezioni"));?>"><a href="<?php echo $this->baseUrl."/traduzionicorrezioni/main/1";?>"><i class="fa fa-eraser"></i> <?php echo gtext("Correzioni");?></a></li>
		<?php if (v("attiva_cron_web")) { ?>
		<li><a class="ajlink" href="<?php echo Domain::$publicUrl."/cron/traduci?".v("token_comandi_cron_web")."&azione=traduci&limit=30";?>"><i class="fa fa-refresh"></i> <?php echo gtext("Lancia traduzione modifiche");?></a></li>
		<?php } ?>
	</ul>
</li>
<?php } ?>
