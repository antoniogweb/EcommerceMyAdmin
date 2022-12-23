<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("ecommerce_attivo") && v("attiva_gestione_stati_ordine")) { ?>
<li class="<?php echo tm($tm, "statiordine");?> treeview">
	<a href="#">
		<i class="fa fa-check-square-o"></i>
		<span><?php echo gtext("Stati ordine")?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/statiordine/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
	</ul>
</li>
<?php } ?>
