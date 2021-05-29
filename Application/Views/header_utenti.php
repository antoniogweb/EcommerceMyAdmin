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
			<li class="header">MENÙ GENSTIONE PREFERENZE</li>
			<li class="<?php echo $tm["utenti"][0];?> treeview">
				<a href="#">
					<i class="fa fa-user-circle-o"></i>
					<span>Amministratori</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/users/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi utente</a></li>
					<li><a href="<?php echo $this->baseUrl."/users/main/1";?>"><i class="fa fa-list"></i> Lista utenti</a></li>
				</ul>
			</li>
			<li class="<?php echo $tm["traduzioni"][0];?>"><a href="<?php echo $this->baseUrl."/traduzioni/main/1";?>"><i class="fa fa-language"></i> Traduzioni</a></li>
			<li class="<?php echo $tm["impostazioni"][0];?>"><a href="<?php echo $this->baseUrl."/impostazioni/form/update/1";?>"><i class="fa fa-cogs"></i> Impostazioni</a></li>
			<?php if (v("mostra_tipi_fasce")) { ?>
			<li class="<?php echo $tm["tipicontenuto"][0];?> treeview">
				<a href="#">
					<i class="fa fa-code"></i>
					<span>Tipologie fasce</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/tipicontenuto/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi tipo</a></li>
					<li><a href="<?php echo $this->baseUrl."/tipicontenuto/main/1";?>"><i class="fa fa-list"></i> Lista tipi</a></li>
				</ul>
			</li>
			<?php } ?>
			<?php if (v("mostra_tipi_documento")) { ?>
			<li class="<?php echo $tm["tipidocumento"][0];?> treeview">
				<a href="#">
					<i class="fa fa-list"></i>
					<span>Tipi documenti</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/tipidocumento/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi tipo</a></li>
					<li><a href="<?php echo $this->baseUrl."/tipidocumento/main/1";?>"><i class="fa fa-list"></i> Lista tipi</a></li>
				</ul>
			</li>
			<?php } ?>
			<?php if (v("mostra_fasce_prezzo")) { ?>
			<li class="<?php echo tm($tm, "fasceprezzo");?> treeview">
				<a href="#">
					<i class="fa fa-list"></i>
					<span>Fasce prezzo</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/fasceprezzo/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi fascia</a></li>
					<li><a href="<?php echo $this->baseUrl."/fasceprezzo/main/1";?>"><i class="fa fa-list"></i> Lista fasce</a></li>
				</ul>
			</li>
			<?php } ?>
			<?php if (v("mostra_gestione_testi")) { ?>
			<li class="<?php echo tm($tm, "testi");?>"><a href="<?php echo $this->baseUrl."/testi/main/1";?>"><i class="fa fa-pencil"></i> Elementi tema</a></li>
			<?php } ?>
			<?php if (v("attiva_tutte_le_categorie")) { ?>
			<li class="<?php echo tm($tm, "categories");?>"><a href="<?php echo $this->baseUrl."/categories/main/1";?>"><i class="fa fa-folder-open"></i> Categorie</a></li>
			<?php } ?>
			<?php if (defined("CACHE_FOLDER")) { ?>
			<li class=""><a class="svuota_cache" href="<?php echo $this->baseUrl."/impostazioni/svuotacache";?>"><i class="fa fa-trash"></i> Svuota cache</a></li>
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
