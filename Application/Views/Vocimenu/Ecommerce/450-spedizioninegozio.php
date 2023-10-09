<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("ecommerce_attivo") && v("attiva_gestione_spedizioni")) { ?>
<li class="<?php echo tm($tm, array("spedizioninegozio","spedizioninegozioinvii"));?> treeview">
	<a href="#">
		<i class="fa fa-truck"></i>
		<span><?php echo gtext("Spedizioni");?></span>
	</a>
	<ul class="treeview-menu">
		<li class="dropdown-header"><?php echo gtext("Spedizioni");?></li>
		<li class="<?php echo tm($tm, array("spedizioninegozio"));?>"><a href="<?php echo $this->baseUrl."/spedizioninegozio/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista spedizioni");?></a></li>
		<li class="dropdown-header"><?php echo gtext("Borderò");?></li>
		<li class="<?php echo tm($tm, array("spedizioninegozioinvii"));?>"><a href="<?php echo $this->baseUrl."/spedizioninegozioinvii/main";?>"><i class="fa fa-book"></i> <?php echo gtext("Lista borderò");?></a></li>
	</ul>
</li>
<?php } ?>
