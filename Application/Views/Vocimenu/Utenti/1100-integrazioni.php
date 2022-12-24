<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_gestione_integrazioni")) { ?>
<li class="<?php echo tm($tm, "integrazioni");?> treeview">
	<a href="#">
		<i class="fa fa-exchange"></i>
		<span><?php echo gtext("Integrazioni software esterni")?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/integrazioni/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
	</ul>
</li>
<?php } ?>
