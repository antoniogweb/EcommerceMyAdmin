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
			<li class="<?php echo $tm["categorie"][0];?> treeview">
				<a href="#">
					<i class="fa fa-bookmark"></i>
					<span>Categorie</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/categorie/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi categoria</a></li>
					<li><a href="<?php echo $this->baseUrl."/categorie/main/1";?>"><i class="fa fa-list"></i> Lista categorie</a></li>
				</ul>
			</li>
			<li class="<?php echo $tm["prodotti"][0];?> treeview">
				<a href="#">
					<i class="fa fa-briefcase"></i>
					<span>Prodotti</span>
				</a>
				<ul class="treeview-menu">
					<li class="dropdown-header">Prodotti</li>
					<li><a href="<?php echo $this->baseUrl."/prodotti/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi prodotto</a></li>
					<li class="icon_list <?php if ($this->controller === "prodotti") { ?>active<?php } ?>"><a href="<?php echo $this->baseUrl."/prodotti/main/1";?>"><i class="fa fa-list"></i> Lista prodotti</a></li>
					
					<?php if (v("caratteristiche_in_prodotti")) { ?>
					<li class="dropdown-header">Caratteristiche</li>
					<li <?php if ($this->controller === "caratteristiche") { ?>class="active"<?php } ?>><a href="<?php echo $this->baseUrl."/caratteristiche/main/1";?>"><i class="fa fa-list"></i> Lista caratteristiche</a></li>
					<?php } ?>
					<?php if (v("combinazioni_in_prodotti")) { ?>
					<li class="dropdown-header">Varianti prodotto</li>
					<li <?php if ($this->controller === "attributi") { ?>class="active"<?php } ?>><a href="<?php echo $this->baseUrl."/attributi/main/1";?>"><i class="fa fa-cogs"></i> Lista varianti</a></li>
<!-- 					<li class="icon_list"><a href="<?php echo $this->baseUrl."/pages/esportaprodotti";?>"><i class="fa fa-table"></i> Scarica Excel</a></li> -->
					<?php } ?>
					
					<?php if (v("attiva_personalizzazioni")) { ?>
					<li class="dropdown-header">Personalizzazioni</li>
					<li <?php if ($this->controller === "personalizzazioni") { ?>class="active"<?php } ?>><a href="<?php echo $this->baseUrl."/personalizzazioni/main/1";?>"><i class="fa fa-cogs"></i> Lista personalizzazioni</a></li>
					<?php } ?>
				</ul>
			</li>
			<?php if (v("ecommerce_attivo")) { ?>
			<li class="<?php if ($this->controller === "combinazioni") { ?>active<?php } ?> treeview">
				<a href="#">
					<i class="fa fa-archive"></i>
					<span>Magazzino</span>
				</a>
				<ul class="treeview-menu">
					<li <?php if ($this->controller === "combinazioni") { ?>class="active"<?php } ?>><a href="<?php echo $this->baseUrl."/combinazioni/main/1";?>"><fa class="fa fa-list"></fa> Elenco codici</a></li>
				</ul>
			</li>
			<?php } ?>
			<li class="<?php echo $tm["clienti"][0];?> treeview">
				<a href="#">
					<i class="fa fa-users"></i>
					<span>Clienti</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/regusers/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi cliente</a></li>
					<li <?php if ($this->controller === "regusers" && $this->action == "main") { ?>class="active"<?php } ?>><a href="<?php echo $this->baseUrl."/regusers/main/1";?>"><i class="fa fa-list"></i> Lista clienti</a></li>
					<?php if (v("attiva_ruoli")) { ?>
					<li class="dropdown-header">Ruoli</li>
					<li <?php if ($this->controller === "ruoli") { ?>class="active"<?php } ?>><a href="<?php echo $this->baseUrl."/ruoli/main/1";?>"><i class="fa fa-list"></i> Lista ruoli</a></li>
					<?php } ?>
					<?php if (v("attiva_gruppi")) { ?>
					<li class="dropdown-header">Gruppi</li>
					<li <?php if ($this->controller === "reggroups") { ?>class="active"<?php } ?>><a href="<?php echo $this->baseUrl."/reggroups/main/1";?>"><i class="fa fa-users"></i> Lista gruppi</a></li>
					<?php } ?>
				</ul>
			</li>
			<?php if (v("ecommerce_attivo")) { ?>
			<li class="<?php echo $tm["ordini"][0];?> treeview">
				<a href="#">
					<i class="fa fa-book"></i>
					<span>Ordini</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/ordini/main/1";?>"><i class="fa fa-list"></i> Lista ordini</a></li>
					<?php if (v("fatture_attive")) { ?>
					<li><a href="<?php echo $this->baseUrl."/fatture/main/1";?>"><i class="fa fa-list"></i> Lista fatture</a></li>
					<?php } ?>
				</ul>
			</li>
			<?php } ?>
			<?php if (v("usa_marchi")) { ?>
			<li class="<?php echo $tm["marchi"][0];?> treeview">
				<a href="#">
					<i class="fa fa-folder-open"></i>
					<span><?php echo gtext("famiglie",true,"ucfirst");?></span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/marchi/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi <?php echo gtext("famiglia");?></a></li>
					<li><a href="<?php echo $this->baseUrl."/marchi/main";?>"><i class="fa fa-list"></i> Lista <?php echo gtext("famiglie");?></a></li>
				</ul>
			</li>
			<?php } ?>
			<?php if (v("usa_tag")) { ?>
			<li class="<?php echo $tm["tag"][0];?> treeview">
				<a href="#">
					<i class="fa fa-folder-open"></i>
					<span><?php echo gtext("Tag / Linee",true,"ucfirst");?></span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/tag/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi <?php echo gtext("Tag / Linee");?></a></li>
					<li><a href="<?php echo $this->baseUrl."/tag/main";?>"><i class="fa fa-list"></i> Lista <?php echo gtext("Tag / Linee");?></a></li>
				</ul>
			</li>
			<?php } ?>
			<?php if (v("ecommerce_attivo")) { ?>
			<li class="<?php echo $tm["promozioni"][0];?> treeview">
				<a href="#">
					<i class="fa fa-gift"></i>
					<span>Promozioni</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/promozioni/form/insert/0/1/tutti/$token";?>"><i class="fa fa-plus-circle"></i> Aggiungi promozione</a></li>
					<li><a href="<?php echo $this->baseUrl."/promozioni/main/1/tutti/$token";?>"><i class="fa fa-list"></i> Lista promozioni</a></li>
				</ul>
			</li>
			<li class="<?php echo $tm["classisconto"][0];?> treeview">
				<a href="#">
					<i class="fa fa-eur"></i>
					<span>Classi sconto</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/classisconto/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi classe</a></li>
					<li><a href="<?php echo $this->baseUrl."/classisconto/main";?>"><i class="fa fa-list"></i> Lista classi</a></li>
				</ul>
			</li>
			<li class="<?php echo $tm["corrieri"][0];?> treeview">
				<a href="#">
					<i class="fa fa-truck"></i>
					<span>Corrieri</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/corrieri/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi corriere</a></li>
					<li><a href="<?php echo $this->baseUrl."/corrieri/main";?>"><i class="fa fa-list"></i> Lista corrieri</a></li>
				</ul>
			</li>
			<li class="<?php echo $tm["iva"][0];?> treeview">
				<a href="#">
					<i class="fa fa-folder"></i>
					<span>Aliquote iva</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/iva/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi</a></li>
					<li><a href="<?php echo $this->baseUrl."/iva/main";?>"><i class="fa fa-list"></i> Lista</a></li>
				</ul>
			</li>
			<li class="<?php echo tm($tm, "nazioni");?> treeview">
				<a href="#">
					<i class="fa fa-globe"></i>
					<span>Nazioni</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/nazioni/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi</a></li>
					<li><a href="<?php echo $this->baseUrl."/nazioni/main";?>"><i class="fa fa-list"></i> Lista</a></li>
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

	
