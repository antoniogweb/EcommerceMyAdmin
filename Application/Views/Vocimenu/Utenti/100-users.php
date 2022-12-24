<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<li class="<?php echo tm($tm, array("users","groups"));?> treeview">
	<a href="#">
		<i class="fa fa-user-circle-o"></i>
		<span><?php echo gtext("Amministratori");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/users/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi utente");?></a></li>
		<li class="<?php echo tm($tm, array("users"));?>"><a href="<?php echo $this->baseUrl."/users/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista utenti");?></a></li>
		<?php if (v("attiva_gruppi_admin")) { ?>
		<li class="dropdown-header">Gruppi</li>
		<li class="<?php echo tm($tm, array("groups"));?>"><a href="<?php echo $this->baseUrl."/groups/main/1";?>"><i class="fa fa-group"></i> Lista gruppi</a></li>
		<?php } ?>
	</ul>
</li>
