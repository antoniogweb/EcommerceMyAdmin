<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_prodotti_piu_venduti")) { ?>
<li class="<?php echo tm($tm, "righe");?> treeview">
	<a href="#">
		<i class="fa fa-bar-chart"></i>
		<span><?php echo gtext("Prodotti più venduti")?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/righe/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
	</ul>
</li>
<?php } ?>
