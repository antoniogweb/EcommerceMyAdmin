<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_standard_cms_menu") && v("download_attivi")) { ?>
<li class="<?php echo tm($tm, array("download", "downloadcat"));?> treeview">
	<a href="#">
		<i class="fa fa-download"></i>
		<span><?php echo gtext("Downloads");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/download/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/download/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
		<?php if (v("attiva_categorie_download")) { ?>
		<li class="dropdown-header">Categorie</li>
		<li><a href="<?php echo $this->baseUrl."/downloadcat/main/1";?>"><i class="fa fa-list"></i> Lista categorie</a></li>
		<?php } ?>
	</ul>
</li>
<?php } ?>
