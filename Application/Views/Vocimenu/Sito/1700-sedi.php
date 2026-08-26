<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_standard_cms_menu") && v("mostra_sedi")) { ?>
<li class="<?php echo tm($tm, array("sedi", "sedicat"));?> treeview">
	<a href="#">
		<i class="fa fa-thumb-tack"></i>
		<span><?php echo gtextPlain("Sedi");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/sedi/main";?>"><i class="fa fa-list"></i> <?php echo gtextPlain("Lista");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/sedi/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtextPlain("Aggiungi");?></a></li>
		<?php if (v("attiva_categorie_sedi")) { ?>
		<li class="dropdown-header"><?php echo gtextPlain("Categorie");?></li>
		<li class="<?php echo tm($tm, array("sedicat"));?>"><a href="<?php echo $this->baseUrl."/sedicat/main/1";?>"><i class="fa fa-list"></i> <?php echo gtextPlain("Lista categorie");?></a></li>
		<?php } ?>
	</ul>
</li>
<?php } ?>
