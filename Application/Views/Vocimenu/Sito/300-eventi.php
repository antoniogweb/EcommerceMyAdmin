<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_standard_cms_menu") && v("mostra_eventi")) { ?>
<li class="<?php echo tm($tm, array("eventi","eventicat"));?> treeview">
	<a href="#">
		<i class="fa fa-calendar-o"></i>
		<span><?php echo gtext("Eventi");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/eventi/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/eventi/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
		<li class="dropdown-header"><?php echo gtext("Categorie");?></li>
		<li class="<?php echo tm($tm, array("eventicat"));?>"><a href="<?php echo $this->baseUrl."/eventicat/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista categorie");?></a></li>
	</ul>
</li>
<?php } ?>
