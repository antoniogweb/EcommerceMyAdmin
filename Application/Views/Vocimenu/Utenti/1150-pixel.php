<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_gestione_pixel")) { ?>
<li class="<?php echo tm($tm, "pixel");?> treeview">
	<a href="#">
		<i class="fa fa-dot-circle-o"></i>
		<span><?php echo gtext("Gestione pixel")?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/pixel/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
	</ul>
</li>
<?php } ?>
