<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("ecommerce_attivo") && v("attiva_classi_sconto")) { ?>
<li class="<?php echo tm($tm, array("classisconto"));?> treeview">
	<a href="#">
		<i class="fa fa-eur"></i>
		<span>Classi sconto</span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/classisconto/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi")?></a></li>
		<li><a href="<?php echo $this->baseUrl."/classisconto/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
	</ul>
</li>
<?php } ?>
