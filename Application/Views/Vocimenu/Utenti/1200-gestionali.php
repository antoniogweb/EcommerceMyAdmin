<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_collegamento_gestionali")) { ?>
<li class="<?php echo tm($tm, "gestionali");?> treeview">
	<a href="#">
		<i class="fa fa-database"></i>
		<span><?php echo gtext("Integrazione gestionali")?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/gestionali/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
	</ul>
</li>
<?php } ?>
