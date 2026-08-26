<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("ecommerce_attivo") && v("attiva_spedizione")) { ?>
<li class="<?php echo tm($tm, array("corrieri"));?> treeview">
	<a href="#">
		<i class="fa fa-rocket"></i>
		<span><?php echo gtextPlain("Corrieri");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/corrieri/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtextPlain("Aggiungi")?></a></li>
		<li><a href="<?php echo $this->baseUrl."/corrieri/main";?>"><i class="fa fa-list"></i> <?php echo gtextPlain("Lista")?></a></li>
	</ul>
</li>
<?php } ?>
