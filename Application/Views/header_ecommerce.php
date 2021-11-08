<?php if (!defined('EG')) die('Direct access not allowed!');
include(ROOT."/Application/Views/header.php");
?>
	
	<?php if (!partial()) { ?>
<!-- Left side column. contains the logo and sidebar -->
	<aside class="main-sidebar">
		<!-- sidebar: style can be found in sidebar.less -->
		<section class="sidebar">
		<?php if (User::$logged and strcmp($this->action,'logout') !== 0) { ?>
		<ul class="sidebar-menu">
			<li class="header">MENÙ GENSTIONE ECOMMERCE</li>
			<li class="<?php echo tm($tm, "categorie");?> treeview help_categorie">
				<a href="#">
					<i class="fa fa-bookmark"></i>
					<span>Categorie</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/categorie/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi categoria</a></li>
					<li><a href="<?php echo $this->baseUrl."/categorie/main/1";?>"><i class="fa fa-list"></i> Lista categorie</a></li>
				</ul>
			</li>
			<li class="<?php echo tm($tm, array("prodotti","attributi","personalizzazioni"));?> treeview">
				<a href="#">
					<i class="fa fa-briefcase"></i>
					<span>Prodotti</span>
				</a>
				<ul class="treeview-menu">
					<li class="dropdown-header">Prodotti</li>
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
			<li class="<?php echo tm($tm, array("regusers","ruoli","tipiazienda","reggroups"));?> treeview">
				<a href="#">
					<i class="fa fa-users"></i>
					<span>Clienti</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/".v("url_elenco_clienti")."/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi cliente</a></li>
					<li <?php if ($this->controller === "regusers" && $this->action == "main") { ?>class="active"<?php } ?>><a href="<?php echo $this->baseUrl."/".v("url_elenco_clienti")."/main/1";?>"><i class="fa fa-list"></i> Lista clienti</a></li>
					<?php if (v("attiva_ruoli")) { ?>
					<li class="dropdown-header">Ruoli</li>
					<li class="<?php echo tm($tm, array("ruoli"));?>"><a href="<?php echo $this->baseUrl."/ruoli/main/1";?>"><i class="fa fa-list"></i> Lista ruoli</a></li>
					<?php } ?>
					<?php if (v("attiva_tipi_azienda")) { ?>
					<li class="dropdown-header">Tipi azienda</li>
					<li class="<?php echo tm($tm, array("tipiazienda"));?>"><a href="<?php echo $this->baseUrl."/tipiazienda/main/1";?>"><i class="fa fa-list"></i> Lista tipi aziende</a></li>
					<?php } ?>
					<?php if (v("attiva_gruppi")) { ?>
					<li class="dropdown-header">Gruppi</li>
					<li class="<?php echo tm($tm, array("reggroups"));?>"><a href="<?php echo $this->baseUrl."/reggroups/main/1";?>"><i class="fa fa-users"></i> Lista gruppi</a></li>
					<?php } ?>
				</ul>
			</li>
			<?php if (v("ecommerce_attivo")) { ?>
			<li class="<?php echo tm($tm, array("ordini", "fatture"));?> treeview">
				<a href="#">
					<i class="fa fa-book"></i>
					<span>Ordini</span>
				</a>
				<ul class="treeview-menu">
					<li class="<?php echo tm($tm, array("ordini"));?>"><a href="<?php echo $this->baseUrl."/".v("url_elenco_ordini")."/1";?>"><i class="fa fa-list"></i> Lista ordini</a></li>
					<?php if (v("fatture_attive")) { ?>
					<li class="<?php echo tm($tm, array("fatture"));?>"><a href="<?php echo $this->baseUrl."/fatture/main/1";?>"><i class="fa fa-list"></i> Lista fatture</a></li>
					<?php } ?>
				</ul>
			</li>
			<?php } ?>
			<?php if (v("usa_marchi")) { ?>
			<li class="<?php echo tm($tm, array("marchi"));?> treeview help_marchi">
				<a href="#">
					<i class="fa fa-font-awesome"></i>
					<span><?php echo gtext("famiglie",true,"ucfirst");?></span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/marchi/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
					<li><a href="<?php echo $this->baseUrl."/marchi/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
				</ul>
			</li>
			<?php } ?>
			<?php if (v("usa_tag")) { ?>
			<li class="<?php echo tm($tm, array("tag"));?> treeview help_tag">
				<a href="#">
					<i class="fa fa-tags"></i>
					<span><?php echo gtext("Tag / Linee",true,"ucfirst");?></span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/tag/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
					<li><a href="<?php echo $this->baseUrl."/tag/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
				</ul>
			</li>
			<?php } ?>
			<?php if (v("ecommerce_attivo")) { ?>
			<li class="<?php echo tm($tm, array("promozioni"));?> treeview">
				<a href="#">
					<i class="fa fa-gift"></i>
					<span>Promozioni</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/promozioni/form/insert/0/1/tutti/$token";?>"><i class="fa fa-plus-circle"></i> Aggiungi promozione</a></li>
					<li><a href="<?php echo $this->baseUrl."/promozioni/main/1/tutti/$token";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
				</ul>
			</li>
			<?php if (v("attiva_classi_sconto")) { ?>
			<li class="<?php echo tm($tm, array("classisconto"));?> treeview">
				<a href="#">
					<i class="fa fa-eur"></i>
					<span>Classi sconto</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/classisconto/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi")?></a></li>
					<li><a href="<?php echo $this->baseUrl."/classisconto/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
				</ul>
			</li>
			<?php } ?>
			<li class="<?php echo tm($tm, array("corrieri"));?> treeview">
				<a href="#">
					<i class="fa fa-truck"></i>
					<span>Corrieri</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/corrieri/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi")?></a></li>
					<li><a href="<?php echo $this->baseUrl."/corrieri/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
				</ul>
			</li>
			<li class="<?php echo tm($tm, array("iva"));?> treeview">
				<a href="#">
					<i class="fa fa-folder"></i>
					<span><?php echo gtext("Aliquote iva")?></span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/iva/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi")?></a></li>
					<li><a href="<?php echo $this->baseUrl."/iva/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
				</ul>
			</li>
			<?php if (v("attiva_gestione_pagamenti")) { ?>
			<li class="<?php echo tm($tm, "pagamenti");?> treeview">
				<a href="#">
					<i class="fa fa-credit-card"></i>
					<span><?php echo gtext("Metodi di pagamento")?></span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/pagamenti/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
				</ul>
			</li>
			<?php } ?>
			<li class="<?php echo tm($tm, "nazioni");?> treeview">
				<a href="#">
					<i class="fa fa-globe"></i>
					<span>Nazioni</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/nazioni/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi")?></a></li>
					<li><a href="<?php echo $this->baseUrl."/nazioni/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
				</ul>
			</li>
			<?php } ?>
			<?php if (defined("APPS")) {
				foreach (APPS as $app)
				{
					$path = ROOT."/Application/Apps/".ucfirst($app)."/Menu/ecommerce.php";
					
					if (file_exists($path))
						include($path);
				}
			} ?>
		</ul>
		<?php } ?>
	</section>
	<!-- /.sidebar -->
	</aside>
	<?php } ?>
      
      <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper <?php if (showreport()) { ?>content-wrapper-report <?php } ?><?php if (partial()) { ?> content_basso <?php } ?>" >
      
		<?php echo $alertFatture;?>

	
