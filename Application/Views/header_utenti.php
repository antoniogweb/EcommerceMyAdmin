<?php if (!defined('EG')) die('Direct access not allowed!');
include(ROOT."/Application/Views/header.php");
?>
	
	<?php if (!partial()) { ?>
<!-- Left side column. contains the logo and sidebar -->
	<aside class="main-sidebar">
		<!-- sidebar: style can be found in sidebar.less -->
		<section class="sidebar">
		<?php if (User::$logged and strcmp($this->action,'logout') !== 0) {?>
		<ul class="sidebar-menu">
			<li class="header"><?php echo gtext("MENÙ GENSTIONE PREFERENZE");?></li>
			<li class="<?php echo $tm["utenti"][0];?> treeview">
				<a href="#">
					<i class="fa fa-user-circle-o"></i>
					<span><?php echo gtext("Amministratori");?></span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/users/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi utente");?></a></li>
					<li><a href="<?php echo $this->baseUrl."/users/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista utenti");?></a></li>
				</ul>
			</li>
			<li class="<?php echo $tm["traduzioni"][0];?>"><a href="<?php echo $this->baseUrl."/traduzioni/main/1";?>"><i class="fa fa-language"></i> <?php echo gtext("Traduzioni");?></a></li>
			<li class="<?php echo ($this->controller == "impostazioni" && $this->action == "form") ? "active" : "";?>"><a href="<?php echo $this->baseUrl."/impostazioni/form/update/1";?>"><i class="fa fa-cogs"></i> <?php echo gtext("Impostazioni");?></a></li>
			<?php if (v("mostra_tipi_fasce")) { ?>
			<li class="<?php echo $tm["tipicontenuto"][0];?> treeview">
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
					<i class="fa fa-list"></i>
					<span><?php echo gtext("Fasce prezzo");?></span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/fasceprezzo/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi fascia");?></a></li>
					<li><a href="<?php echo $this->baseUrl."/fasceprezzo/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista fasce");?></a></li>
				</ul>
			</li>
			<?php } ?>
			<?php if (v("mostra_gestione_testi")) { ?>
			<li class="<?php echo tm($tm, "testi");?>"><a href="<?php echo $this->baseUrl."/testi/main/1";?>"><i class="fa fa-pencil"></i> <?php echo gtext("Elementi tema");?></a></li>
			<?php } ?>
			<?php if (count(Tema::getElencoTemi()) > 1 && v("permetti_cambio_tema")) { ?>
			<li class="<?php echo ($this->controller == "impostazioni" && $this->action == "tema") ? "active" : "";?>"><a href="<?php echo $this->baseUrl."/impostazioni/tema/1";?>"><i class="fa fa-eye"></i> <?php echo gtext("Cambia tema sito");?></a></li>
			<?php } ?>
			<?php if (v("attiva_tutte_le_categorie")) { ?>
			<li class="<?php echo tm($tm, "categories");?>"><a href="<?php echo $this->baseUrl."/categories/main/1";?>"><i class="fa fa-folder-open"></i> <?php echo gtext("Sezioni sito");?></a></li>
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
			<?php if (defined("CACHE_FOLDER") || v("attiva_cache_immagini")) { ?>
			<li class=""><a class="svuota_cache" href="<?php echo $this->baseUrl."/impostazioni/svuotacache";?>"><i class="fa fa-trash"></i> <?php echo gtext("Svuota cache");?></a></li>
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
