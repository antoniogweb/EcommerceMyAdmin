<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_gestione_feed")) { ?>
<li class="<?php echo tm($tm, "feed");?> treeview">
	<a href="#">
		<i class="fa fa-rss"></i>
		<span><?php echo gtext("Gestione feed")?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/feed/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
	</ul>
</li>
<?php } ?>
