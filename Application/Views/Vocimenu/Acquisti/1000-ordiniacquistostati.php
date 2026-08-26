<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<li class="<?php echo tm($tm, "ordiniacquistostati");?> treeview help_ordini_acquisto_stati">
	<a href="#">
		<i class="fa fa-check-square-o"></i>
		<span><?php echo gtextPlain("Stati ordine acquisto"); ?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/ordiniacquistostati/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtextPlain("Aggiungi stato"); ?></a></li>
		<li><a href="<?php echo $this->baseUrl."/ordiniacquistostati/main/1";?>"><i class="fa fa-list"></i> <?php echo gtextPlain("Lista stati"); ?></a></li>
	</ul>
</li>
