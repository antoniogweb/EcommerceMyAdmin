<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("ecommerce_attivo")) { ?>
<li class="<?php echo tm($tm, "cart");?> treeview">
	<a href="#">
		<i class="fa fa-shopping-cart"></i>
		<span><?php echo gtext("Carrelli abbandonati")?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/cart/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
	</ul>
</li>
<?php } ?>
