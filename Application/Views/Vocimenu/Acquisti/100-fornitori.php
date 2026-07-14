<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<li class="<?php echo tm($tm, array("fornitori","magazzinoarticolilistini"));?> treeview help_fornitori">
	<a href="#">
		<i class="fa fa-industry"></i>
		<span><?php echo gtext("Fornitori"); ?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/fornitori/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi fornitore"); ?></a></li>
		<li <?php if ($this->controller == "fornitori") { ?>class="active"<?php } ?>><a href="<?php echo $this->baseUrl."/fornitori/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista fornitori"); ?></a></li>
		<li <?php if ($this->controller == "magazzinoarticolilistini") { ?>class="active"<?php } ?>><a href="<?php echo $this->baseUrl."/magazzinoarticolilistini/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Listini"); ?></a></li>
	</ul>
</li>
