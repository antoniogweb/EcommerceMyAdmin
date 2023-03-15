<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_gestione_motori_ricerca")) { ?>
<li class="<?php echo tm($tm, array("motoriricerca", "ricerchesinonimi", "ricerche"));?> treeview">
	<a href="#">
		<i class="fa fa-search"></i>
		<span><?php echo gtext("Motori di ricerca")?></span>
	</a>
	<ul class="treeview-menu">
		<li class="<?php echo tm($tm, array("motoriricerca"));?>"><a href="<?php echo $this->baseUrl."/motoriricerca/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
		<li class="<?php echo tm($tm, array("ricerchesinonimi"));?>"><a href="<?php echo $this->baseUrl."/ricerchesinonimi/main";?>"><i class="fa fa-book"></i> <?php echo gtext("Sinonimi")?></a></li>
		<li class="<?php echo tm($tm, array("ricerche"));?>"><a href="<?php echo $this->baseUrl."/ricerche/main";?>"><i class="fa fa-search"></i> <?php echo gtext("Ricerche effettuate")?></a></li>
	</ul>
</li>
<?php } ?>
