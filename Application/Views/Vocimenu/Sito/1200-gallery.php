<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_standard_cms_menu") && v("mostra_gallery")) { ?>
<li class="<?php echo tm($tm, array("gallery","gallerycat"));?> treeview">
	<a href="#">
		<i class="fa fa-picture-o"></i>
		<span>Gallery</span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/gallery/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/gallery/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
		<?php if (v("attiva_categoria_in_gallery")) { ?>
		<li class="dropdown-header">Categorie</li>
		<li class="<?php echo tm($tm, array("gallerycat"));?>"><a href="<?php echo $this->baseUrl."/gallerycat/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista categorie");?></a></li>
		<?php } ?>
	</ul>
</li>
<?php } ?>
