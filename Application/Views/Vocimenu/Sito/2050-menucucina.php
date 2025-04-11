<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_standard_cms_menu") && v("mostra_menu_cucina")) { ?>
<li class="<?php echo tm($tm, array("menucucina", "menucucinacat"));?> treeview">
	<a href="#">
		<i class="fa fa-cutlery"></i>
		<span><?php echo gtext("Menù cucina");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/menucucina/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/menucucina/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
		<li class="dropdown-header"><?php echo gtext("Categorie");?></li>
		<li class="<?php echo tm($tm, array("menucucinacat"));?>"><a href="<?php echo $this->baseUrl."/menucucinacat/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista categorie");?></a></li>
	</ul>
</li>
<?php } ?>
