<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_calendario_chiusure")) { ?>
<li class="<?php echo tm($tm, "calendariochiusure");?> treeview">
	<a href="#">
		<i class="fa fa-calendar"></i>
		<span><?php echo gtext("Calendario chiusure")?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/calendariochiusure/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Elenco giorni")?></a></li>
	</ul>
</li>
<?php } ?>