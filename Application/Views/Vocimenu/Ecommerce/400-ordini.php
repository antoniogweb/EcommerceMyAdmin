<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("ecommerce_attivo")) { ?>
<li class="<?php echo tm($tm, array("ordini", "fatture"));?> treeview">
	<a href="#">
		<i class="fa fa-book"></i>
		<span><?php echo gtext("Ordini");?></span>
	</a>
	<ul class="treeview-menu">
		<li class="<?php echo tm($tm, array("ordini"));?>"><a href="<?php echo $this->baseUrl."/".v("url_elenco_ordini")."/1";?>"><i class="fa fa-list"></i> Lista ordini</a></li>
		<?php if (v("fatture_attive")) { ?>
		<li class="<?php echo tm($tm, array("fatture"));?>"><a href="<?php echo $this->baseUrl."/fatture/main/1";?>"><i class="fa fa-list"></i> Lista fatture</a></li>
		<?php } ?>
	</ul>
</li>
<?php } ?>
