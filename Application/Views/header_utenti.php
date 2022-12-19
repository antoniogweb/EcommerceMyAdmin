<?php if (!defined('EG')) die('Direct access not allowed!');
include(ROOT."/Application/Views/header.php");
?>
	
	<?php if (!partial()) { ?>
	<aside class="main-sidebar">
		<section class="sidebar">
		<?php if (User::$logged and strcmp($this->action,'logout') !== 0) {?>
		<ul class="sidebar-menu">
			<li class="header"><?php echo gtext("MENÙ GENSTIONE PREFERENZE");?></li>
			<li class="<?php echo tm($tm, "users");?> treeview">
				<a href="#">
					<i class="fa fa-user-circle-o"></i>
					<span><?php echo gtext("Amministratori");?></span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/users/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi utente");?></a></li>
					<li><a href="<?php echo $this->baseUrl."/users/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista utenti");?></a></li>
				</ul>
			</li>
			<li class="<?php echo tm($tm, "traduzioni");?>"><a href="<?php echo $this->baseUrl."/traduzioni/main/1";?>"><i class="fa fa-language"></i> <span><?php echo gtext("Traduzioni");?></span></a></li>
			<li class="<?php echo tm($tm, "variabili");?>"><a href="<?php echo $this->baseUrl."/impostazioni/form/update/1";?>"><i class="fa fa-cogs"></i> <span><?php echo gtext("Impostazioni");?></span></a></li>
			<?php if (v("mostra_tipi_fasce")) { ?>
			<li class="<?php echo tm($tm, "tipicontenuto");?> treeview">
				<a href="#">
					<i class="fa fa-code"></i>
					<span><?php echo gtext("Tipologie fasce");?></span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/tipicontenuto/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi tipo");?></a></li>
					<li><a href="<?php echo $this->baseUrl."/tipicontenuto/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista tipi");?></a></li>
				</ul>
			</li>
			<?php } ?>
			<?php if (v("mostra_tipi_documento")) { ?>
			<li class="<?php echo tm($tm, "tipidocumento");?> treeview">
				<a href="#">
					<i class="fa fa-list"></i>
					<span><?php echo gtext("Tipi documenti");?></span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/tipidocumento/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi tipo");?></a></li>
					<li><a href="<?php echo $this->baseUrl."/tipidocumento/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista tipi");?></a></li>
				</ul>
			</li>
			<?php } ?>
			<?php if (v("mostra_fasce_prezzo")) { ?>
			<li class="<?php echo tm($tm, "fasceprezzo");?> treeview">
				<a href="#">
					<i class="fa fa-money"></i>
					<span><?php echo gtext("Fasce prezzo");?></span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/fasceprezzo/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi fascia");?></a></li>
					<li><a href="<?php echo $this->baseUrl."/fasceprezzo/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista fasce");?></a></li>
				</ul>
			</li>
			<?php } ?>
			<?php if (v("mostra_gestione_testi") || v("attiva_elementi_tema") || (count(Tema::getElencoTemi()) > 1 && v("permetti_cambio_tema"))) { ?>
			<li class="<?php echo tm($tm, array("testi","elementitema","temi"));?> treeview">
				<a href="#">
					<i class="fa fa-pencil"></i>
					<span>Temi</span>
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
					
					<?php if (count(Tema::getElencoTemi()) > 1 && v("permetti_cambio_tema")) { ?>
					<li class="dropdown-header"><?php echo gtext("Stile");?></li>
					<li class="<?php echo tm($tm, "temi");?>"><a href="<?php echo $this->baseUrl."/impostazioni/tema/1";?>"><i class="fa fa-eye"></i> <span><?php echo gtext("Cambia stile");?></span></a></li>
					<?php } ?>
				</ul>
			</li>
			<?php } ?>
			<?php if (v("attiva_tutte_le_categorie")) { ?>
			<li class="<?php echo tm($tm, "categories");?>"><a href="<?php echo $this->baseUrl."/categories/main/1";?>"><i class="fa fa-folder-open"></i> <span><?php echo gtext("Sezioni sito");?></span></a></li>
			<?php } ?>
			<?php if (v("mostra_gestione_antispam")) { ?>
			<li class="<?php echo tm($tm, "captcha");?> treeview">
				<a href="#">
					<i class="fa fa-shield"></i>
					<span><?php echo gtext("Gestione antispam")?></span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/captcha/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
				</ul>
			</li>
			<?php } ?>
			<?php if (v("mostra_gestione_newsletter")) { ?>
			<li class="<?php echo tm($tm, "integrazioninewsletter");?> treeview">
				<a href="#">
					<i class="fa fa-share"></i>
					<span><?php echo gtext("Integrazione newsletter")?></span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/integrazioninewsletter/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
				</ul>
			</li>
			<?php } ?>
			<?php if (v("attiva_gestione_integrazioni")) { ?>
			<li class="<?php echo tm($tm, "integrazioni");?> treeview">
				<a href="#">
					<i class="fa fa-exchange"></i>
					<span><?php echo gtext("Integrazioni software esterni")?></span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/integrazioni/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
				</ul>
			</li>
			<?php } ?>
			<?php if (v("attiva_collegamento_gestionali")) { ?>
			<li class="<?php echo tm($tm, "gestionali");?> treeview">
				<a href="#">
					<i class="fa fa-database"></i>
					<span><?php echo gtext("Integrazione gestionali")?></span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/gestionali/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
				</ul>
			</li>
			<?php } ?>
			<?php if (v("abilita_login_tramite_app")) { ?>
			<li class="<?php echo tm($tm, "integrazionilogin");?> treeview">
				<a href="#">
					<i class="fa fa-sign-in"></i>
					<span><?php echo gtext("Login tramite APP")?></span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/integrazionilogin/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
				</ul>
			</li>
			<?php } ?>
			<?php if (v("permetti_gestione_sitemap")) { ?>
			<li class="<?php echo tm($tm, "sitemap");?> treeview">
				<a href="#">
					<i class="fa fa-map-o"></i>
					<span><?php echo gtext("Gestione sitemap")?></span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/sitemap/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
				</ul>
			</li>
			<?php } ?>
			<?php if (v("attiva_redirect")) { ?>
			<li class="<?php echo tm($tm, "redirect");?> treeview">
				<a href="#">
					<i class="fa fa-send-o"></i>
					<span><?php echo gtext("Gestione redirect")?></span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/redirect/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
					<li><a href="<?php echo $this->baseUrl."/redirect/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
				</ul>
			</li>
			<?php } ?>
			<?php if (defined("CACHE_FOLDER") || v("attiva_cache_immagini") || v("attiva_interfaccia_opcache")) { ?>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-bar-chart"></i>
					<span><?php echo gtext("Gestione cache")?></span>
				</a>
				<ul class="treeview-menu">
					<?php if (defined("CACHE_FOLDER")) { ?>
					<li><a class="svuota_cache" href="<?php echo $this->baseUrl."/impostazioni/svuotacache";?>"><i class="fa fa-trash"></i> <?php echo gtext("Svuota cache database");?></a></li>
					<?php } ?>
					<?php if (v("attiva_cache_immagini")) { ?>
					<li><a class="svuota_cache" href="<?php echo $this->baseUrl."/impostazioni/svuotacacheimmagini";?>"><i class="fa fa-trash"></i> <?php echo gtext("Svuota cache immagini");?></a></li>
					<?php } ?>
					<?php if (v("attiva_interfaccia_opcache")) { ?>
					<li><a class="iframe" href="<?php echo $this->baseUrl."/opcache/index";?>"><i class="fa fa-area-chart"></i> <span><?php echo gtext("Statistiche OPcache");?></span></a></li>
					<?php } ?>
				</ul>
			</li>
			<?php } ?>
		</ul>
		<?php } ?>
	</section>
	<!-- /.sidebar -->
	</aside>
	<?php } ?>
      
      <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper <?php if (showreport()) { ?>content-wrapper-report <?php } ?><?php if (partial()) { ?> content_basso <?php } ?>" >
      
		<?php echo $alertFatture;?>
