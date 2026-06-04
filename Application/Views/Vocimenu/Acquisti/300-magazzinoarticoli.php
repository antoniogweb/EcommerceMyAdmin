<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<li class="<?php echo tm($tm, array("magazzinoarticoli"));?> treeview">
	<a href="#">
		<i class="fa fa-archive"></i>
		<span><?php echo gtext("Articoli di magazzino");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/magazzinoarticoli/main/1";?>"><fa class="fa fa-list"></fa> <?php echo gtext("Elenco codici");?></a></li>
	</ul>
</li>
