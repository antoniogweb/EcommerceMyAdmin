<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_gestione_commessi")) { ?>
<li class="<?php echo tm($tm, "commessi");?> treeview">
	<a href="#">
		<i class="fa fa-users"></i>
		<span><?php echo gtextPlain("Gestione commessi")?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/commessi/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtextPlain("Aggiungi");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/commessi/main/1";?>"><i class="fa fa-list"></i> <?php echo gtextPlain("Lista");?></a></li>
	</ul>
</li>
<?php } ?>
