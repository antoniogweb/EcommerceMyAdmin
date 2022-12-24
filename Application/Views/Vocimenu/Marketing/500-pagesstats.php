<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("pannello_statistiche_attivo")) { ?>
<li class="<?php echo tm($tm, "pagesstats");?> treeview">
	<a href="#">
		<i class="fa fa-signal"></i>
		<span><?php echo gtext("Statistiche")?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/pagesstats/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Visualizzazioni");?></a></li>
	</ul>
</li>
<?php } ?>
