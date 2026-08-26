<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_gestiobe_ticket")) { ?>
<li class="<?php echo tm($tm, array("ticket","tickettipologie"));?> treeview">
	<a href="#">
		<i class="fa fa-ticket"></i>
		<span><?php echo gtextPlain("Assistenza");?></span>
	</a>
	<ul class="treeview-menu">
		<li class="dropdown-header"><?php echo gtextPlain("Ticket");?></li>
		<li><a href="<?php echo $this->baseUrl."/ticket/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtextPlain("Aggiungi");?></a></li>
		<li class="icon_list"><a href="<?php echo $this->baseUrl."/ticket/main/1";?>"><i class="fa fa-list"></i> <?php echo gtextPlain("Lista");?></a></li>
		
		<li class="dropdown-header"><?php echo gtextPlain("Tipologie")?></li>
		<li class="<?php echo tm($tm, array("tickettipologie"));?>"><a href="<?php echo $this->baseUrl."/tickettipologie/main/1";?>"><i class="fa fa-cogs"></i> <?php echo gtextPlain("Tipologie");?></a></li>
	</ul>
</li>
<?php } ?>
