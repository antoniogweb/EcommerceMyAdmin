<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<li class="<?php echo tm($tm, array("prodotti","attributi","personalizzazioni"));?> treeview">
	<a href="#">
		<i class="fa fa-briefcase"></i>
		<span><?php echo gtext("Prodotti");?></span>
	</a>
	<ul class="treeview-menu">
		<li class="dropdown-header"><?php echo gtext("Prodotti");?></li>
		<li><a href="<?php echo $this->baseUrl."/".v("url_elenco_prodotti")."/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi prodotto</a></li>
		<li class="icon_list <?php if ($this->controller === "prodotti") { ?>active<?php } ?>"><a href="<?php echo $this->baseUrl."/".v("url_elenco_prodotti")."/main/1";?>"><i class="fa fa-list"></i> Lista prodotti</a></li>
		
		<?php if (v("combinazioni_in_prodotti")) { ?>
		<li class="dropdown-header">Varianti prodotto</li>
		<li class="<?php echo tm($tm, array("attributi"));?>"><a href="<?php echo $this->baseUrl."/attributi/main/1";?>"><i class="fa fa-cogs"></i> Lista varianti</a></li>
		<?php } ?>
		
		<?php if (v("attiva_personalizzazioni")) { ?>
		<li class="dropdown-header">Personalizzazioni</li>
		<li class="<?php echo tm($tm, array("personalizzazioni"));?>"><a href="<?php echo $this->baseUrl."/personalizzazioni/main/1";?>"><i class="fa fa-cogs"></i> Lista personalizzazioni</a></li>
		<?php } ?>
	</ul>
</li>
<?php if (v("caratteristiche_in_prodotti")) { ?>
<li class="<?php echo tm($tm, array("caratteristiche","tipologiecaratteristiche"));?> treeview">
	<a href="#">
		<i class="fa fa-filter"></i>
		<span>Caratteristiche</span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/caratteristiche/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi caratteristica</a></li>
		<li class="<?php echo tm($tm, array("caratteristiche"));?>"><a href="<?php echo $this->baseUrl."/caratteristiche/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista caratteristiche");?></a></li>
		
		<?php if (v("attiva_tipologie_caratteristiche")) { ?>
		<li class="dropdown-header">Tipologie</li>
		<li class="<?php echo tm($tm, array("tipologiecaratteristiche"));?>"><a href="<?php echo $this->baseUrl."/tipologiecaratteristiche/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Tipologie");?></a></li>
		<?php } ?>
	</ul>
</li>
<?php } ?>
<?php if (v("ecommerce_attivo")) { ?>
<li class="<?php echo tm($tm, array("combinazioni"));?> treeview">
	<a href="#">
		<i class="fa fa-archive"></i>
		<span>Magazzino</span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/combinazioni/main/1";?>"><fa class="fa fa-list"></fa> Elenco codici</a></li>
	</ul>
</li>
<?php } ?>
