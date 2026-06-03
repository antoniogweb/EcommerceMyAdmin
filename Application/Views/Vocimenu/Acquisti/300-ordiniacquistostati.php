<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<li class="<?php echo tm($tm, "ordiniacquistostati");?> treeview help_fornitori">
	<a href="#">
		<i class="fa fa-check-square-o"></i>
		<span><?php echo gtext("Stati ordine acquisto"); ?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/ordiniacquistostati/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi stato"); ?></a></li>
		<li><a href="<?php echo $this->baseUrl."/ordiniacquistostati/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista stati"); ?></a></li>
	</ul>
</li>
