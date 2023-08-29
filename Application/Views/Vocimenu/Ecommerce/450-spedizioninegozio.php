<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("ecommerce_attivo") && v("attiva_gestione_spedizioni")) { ?>
<li class="<?php echo tm($tm, array("spedizioninegozio"));?> treeview">
	<a href="#">
		<i class="fa fa-truck"></i>
		<span><?php echo gtext("Spedizioni");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/spedizioninegozio/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
	</ul>
</li>
<?php } ?>
