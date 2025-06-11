<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_gestione_traduttori")) { ?>
<li class="<?php echo tm($tm, array("traduttori","opzioni"));?> treeview">
	<a href="#">
		<i class="fa fa-magic"></i>
		<span><?php echo gtext("Traduttori automatici");?></span>
	</a>
	<ul class="treeview-menu">
		<li class="<?php echo tm($tm, array("traduttori"));?>"><a href="<?php echo $this->baseUrl."/traduttori/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Traduttori automatici");?></a></li>
		<?php if (ControllersModel::checkAccessoAlController(array("opzioni"))) { ?>
		<li class="<?php echo tm($tm, array("opzioni"));?>"><a href="<?php echo $this->baseUrl."/opzioni/main?codice=FRASI_DA_NON_TRADURRE";?>"><i class="fa fa-group"></i> <?php echo gtext("Termini da non tradurre");?></a></li>
		<?php } ?>
	</ul>
</li>
<?php } ?>
