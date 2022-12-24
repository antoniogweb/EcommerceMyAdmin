<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_gestione_spedizionieri")) { ?>
<li class="<?php echo tm($tm, "spedizionieri");?> treeview">
	<a href="#">
		<i class="fa fa-truck"></i>
		<span><?php echo gtext("Spedizionieri");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/spedizionieri/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/spedizionieri/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
	</ul>
</li>
<?php } ?>
