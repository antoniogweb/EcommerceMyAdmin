<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_standard_cms_menu") && v("blog_attivo")) { ?>
<li class="<?php echo tm($tm, array("blog", "blogcat"));?> treeview help_blog">
	<a href="#">
		<i class="fa fa-rss"></i>
		<span><?php echo gtextPlain("Blog");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/blog/main";?>"><i class="fa fa-list"></i> <?php echo gtextPlain("Lista");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/blog/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtextPlain("Aggiungi");?></a></li>
		<li class="dropdown-header"><?php echo gtextPlain("Categorie");?></li>
		<li class="<?php echo tm($tm, array("blogcat"));?>"><a href="<?php echo $this->baseUrl."/blogcat/main/1";?>"><i class="fa fa-list"></i> <?php echo gtextPlain("Lista categorie");?></a></li>
	</ul>
</li>
<?php } ?>
