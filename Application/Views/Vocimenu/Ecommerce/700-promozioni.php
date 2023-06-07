<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("ecommerce_attivo")) { ?>
<li class="<?php echo tm($tm, array("promozioni"));?> treeview">
	<a href="#">
		<i class="fa fa-gift"></i>
		<span><?php echo gtext("Promo coupon");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/promozioni/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi promozione");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/promozioni/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
	</ul>
</li>
<?php } ?>
