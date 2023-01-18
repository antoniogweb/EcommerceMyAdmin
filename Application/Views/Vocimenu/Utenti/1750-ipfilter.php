<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_check_ip")) { ?>
<li class="<?php echo tm($tm, "ipfilter");?> treeview">
	<a href="#">
		<i class="fa fa-unlock"></i>
		<span><?php echo gtext("Filtri IP");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/ipfilter/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/ipfilter/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
	</ul>
</li>
<?php } ?>
