<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_gestione_motori_ricerca")) { ?>
<li class="<?php echo tm($tm, "motoriricerca");?> treeview">
	<a href="#">
		<i class="fa fa-search"></i>
		<span><?php echo gtext("Motori di ricerca")?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/motoriricerca/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
	</ul>
</li>
<?php } ?>