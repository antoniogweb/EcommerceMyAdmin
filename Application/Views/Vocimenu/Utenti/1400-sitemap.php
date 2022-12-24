<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("permetti_gestione_sitemap")) { ?>
<li class="<?php echo tm($tm, "sitemap");?> treeview">
	<a href="#">
		<i class="fa fa-map-o"></i>
		<span><?php echo gtext("Gestione sitemap")?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/sitemap/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
	</ul>
</li>
<?php } ?>
