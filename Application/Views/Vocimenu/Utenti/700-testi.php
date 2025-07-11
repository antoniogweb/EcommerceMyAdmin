<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("mostra_gestione_testi") || v("attiva_elementi_tema") || (count(Tema::getElencoTemi()) > 1 && v("permetti_cambio_tema"))) { ?>
<li class="<?php echo tm($tm, array("testi","elementitema","temi"));?> treeview">
	<a href="#">
		<i class="fa fa-pencil"></i>
		<span><?php echo gtext("Temi"); ?></span>
	</a>
	<ul class="treeview-menu">
		<?php if (v("mostra_gestione_testi")) { ?>
		<li class="dropdown-header"><?php echo gtext("Contenuti");?></li>
		<li class="<?php echo tm($tm, "testi");?>"><a href="<?php echo $this->baseUrl."/testi/main/1";?>"><i class="fa fa-text-width"></i> <span><?php echo gtext("Contenuti tema");?></span></a></li>
		<?php } ?>
		
		<?php if (v("attiva_elementi_tema")) { ?>
		<li class="dropdown-header"><?php echo gtext("Layout");?></li>
		<li class="<?php echo tm($tm, "elementitema");?>"><a href="<?php echo $this->baseUrl."/elementitema/main/1";?>"><i class="fa fa-map"></i> <span><?php echo gtext("Layout blocchi tema");?></span></a></li>
		<?php } ?>
		
		<?php if (count(Tema::getElencoTemi()) > 1 && v("permetti_cambio_tema") && ControllersModel::checkAccessoAlController(array("impostazioni"))) { ?>
		<li class="dropdown-header"><?php echo gtext("Stile");?></li>
		<li class="<?php echo tm($tm, "temi");?>"><a href="<?php echo $this->baseUrl."/impostazioni/tema/1";?>"><i class="fa fa-eye"></i> <span><?php echo gtext("Cambia stile");?></span></a></li>
		<?php } ?>
	</ul>
</li>
<?php } ?>
