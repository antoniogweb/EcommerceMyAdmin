<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_standard_cms_menu") && v("attiva_gestione_menu")) { ?>
<li class="<?php echo tm($tm, "menu1");?> treeview help_menu">
	<a href="#">
		<i class="fa fa-list"></i>
		<span><?php echo gtextPlain("Menù");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/menu/main?lingua=".Params::$defaultFrontEndLanguage;?>"><i class="fa fa-list"></i> <?php echo gtextPlain("Lista");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/menu/form/insert/0?lingua=".Params::$defaultFrontEndLanguage;?>"><i class="fa fa-plus-circle"></i> <?php echo gtextPlain("Aggiungi");?></a></li>
	</ul>
</li>
<?php } ?>
