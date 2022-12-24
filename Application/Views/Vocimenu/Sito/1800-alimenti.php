<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_standard_cms_menu") && v("mostra_alimenti")) { ?>
<li class="<?php echo tm($tm, array("alimenti", "alimenticat"));?> treeview">
	<a href="#">
		<i class="fa fa-shopping-basket"></i>
		<span><?php echo gtext("Alimenti");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/alimenti/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/alimenti/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
		<?php if (v("attiva_categorie_sedi")) { ?>
		<li class="dropdown-header"><?php echo gtext("Categorie");?></li>
		<li class="<?php echo tm($tm, array("alimenticat"));?>"><a href="<?php echo $this->baseUrl."/alimenticat/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista categorie");?></a></li>
		<?php } ?>
	</ul>
</li>
<?php } ?>
