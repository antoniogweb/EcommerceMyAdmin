<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_richieste_ai")) { ?>
<li class="<?php echo tm($tm, array("airichieste","aimodelli"));?> treeview">
	<a href="#">
		<i class="fa  fa-commenting-o"></i>
		<span><?php echo gtext("Assistente virtuale IA")?></span>
	</a>
	<ul class="treeview-menu">
		<li class="dropdown-header"><?php echo gtext("Richieste");?></li>
		<li class="<?php echo tm($tm, "airichieste");?>"><a href="<?php echo $this->baseUrl."/airichieste/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Richieste AI")?></a></li>
		<li class="dropdown-header"><?php echo gtext("Modelli");?></li>
		<li class="<?php echo tm($tm, "aimodelli");?>"><a href="<?php echo $this->baseUrl."/aimodelli/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Configurazione modelli")?></a></li>
	</ul>
</li>
<?php } ?>
