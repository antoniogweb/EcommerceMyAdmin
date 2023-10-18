<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_standard_cms_menu") && v("mostra_approfondimenti")) { ?>
<li class="<?php echo tm($tm, array("approfondimenti"));?> treeview help_approfondimenti">
	<a href="#">
		<i class="fa fa-book"></i>
		<span><?php echo gtext("Approfondimenti");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/approfondimenti/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/approfondimenti/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
	</ul>
</li>
<?php } ?>
