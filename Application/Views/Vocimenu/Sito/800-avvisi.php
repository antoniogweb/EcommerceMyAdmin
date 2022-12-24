<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_standard_cms_menu") && v("mostra_avvisi")) { ?>
<li class="<?php echo tm($tm, "avvisi");?> treeview help_avvisi">
	<a href="#">
		<i class="fa fa-bullhorn"></i>
		<span>Avvisi</span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/avvisi/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/avvisi/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
	</ul>
</li>
<?php } ?>
