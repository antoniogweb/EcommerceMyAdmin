<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<li class="<?php echo tm($tm, "ordiniacquisto");?> treeview help_fornitori">
	<a href="#">
		<i class="fa fa-book"></i>
		<span><?php echo gtext("Ordini acquisto"); ?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/ordiniacquisto/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi ordine"); ?></a></li>
		<li><a href="<?php echo $this->baseUrl."/ordiniacquisto/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista ordini"); ?></a></li>
	</ul>
</li>
