<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_check_ip") || v("attiva_gestione_ipchecker")) { ?>
<li class="<?php echo tm($tm, array("ipfilter","ipchecker"));?> treeview">
	<a href="#">
		<i class="fa fa-unlock"></i>
		<span><?php echo gtext("Filtri IP");?></span>
	</a>
	<ul class="treeview-menu">
		<?php if (v("attiva_check_ip")) { ?>
		<li class="dropdown-header"><?php echo gtext("IP brute force form");?></li>
		<li><a href="<?php echo $this->baseUrl."/ipfilter/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
		<?php } ?>
		<?php if (v("attiva_gestione_ipchecker") && ControllersModel::checkAccessoAlController(array("ipchecker"))) { ?>
		<li class="dropdown-header"><?php echo gtext("IP Checker");?></li>
		<li><a href="<?php echo $this->baseUrl."/ipchecker/main";?>"><i class="fa fa-list"></i> <?php echo gtext("IP Checker");?></a></li>
		<?php } ?>
	</ul>
</li>
<?php } ?>
