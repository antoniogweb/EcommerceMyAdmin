<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("ecommerce_attivo") && v("attiva_gestione_pagamenti")) { ?>
<li class="<?php echo tm($tm, "pagamenti");?> treeview">
	<a href="#">
		<i class="fa fa-credit-card"></i>
		<span><?php echo gtext("Metodi di pagamento")?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/pagamenti/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
	</ul>
</li>
<?php } ?>
