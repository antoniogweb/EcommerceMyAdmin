<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<li class="<?php echo tm($tm, array("ordiniacquistoricezioni"));?> treeview help_ordini_acquisto">
	<a href="#">
		<i class="fa fa-truck"></i>
		<span><?php echo gtext("Ricezioni"); ?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/ordiniacquistoricezioni/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi ricezione"); ?></a></li>
		<li <?php if ($this->controller == "ordiniacquistoricezioni") { ?>class="active"<?php } ?>><a href="<?php echo $this->baseUrl."/ordiniacquistoricezioni/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista ricezioni"); ?></a></li>
	</ul>
</li>
