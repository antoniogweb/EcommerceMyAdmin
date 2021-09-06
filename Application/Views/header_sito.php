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
			<li class="header"><?php echo gtext("MENÙ GESTIONE SITO")?></li>
			<?php if (v("attiva_standard_cms_menu")) { ?>
				<li class="<?php echo $tm["slide"][0];?> treeview help_slide">
					<a href="#">
						<i class="fa fa-picture-o"></i>
						<span>Slide</span>
					</a>
					<ul class="treeview-menu">
						<li><a href="<?php echo $this->baseUrl."/".v("url_elenco_slide");?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
						<li><a href="<?php echo $this->baseUrl."/".v("url_inserisci_slide");?>"><i class="fa fa-plus-circle"></i> Aggiungi</a></li>
					</ul>
				</li>
				<?php if (v("blog_attivo")) { ?>
				<li class="<?php echo $tm["blog"][0];?> treeview help_blog">
					<a href="#">
						<i class="fa fa-rss"></i>
						<span><?php echo gtext("Blog");?></span>
					</a>
					<ul class="treeview-menu">
						<li><a href="<?php echo $this->baseUrl."/blog/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
						<li><a href="<?php echo $this->baseUrl."/blog/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi</a></li>
						<li class="dropdown-header">Categorie</li>
						<li><a href="<?php echo $this->baseUrl."/blogcat/main/1";?>"><i class="fa fa-list"></i> Lista categorie</a></li>
					</ul>
				</li>
				<?php } ?>
				<?php if (v("mostra_eventi")) { ?>
				<li class="<?php echo tm($tm, "eventi");?> treeview">
					<a href="#">
						<i class="fa fa-calendar-o"></i>
						<span><?php echo gtext("Eventi");?></span>
					</a>
					<ul class="treeview-menu">
						<li><a href="<?php echo $this->baseUrl."/eventi/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
						<li><a href="<?php echo $this->baseUrl."/eventi/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi</a></li>
						<li class="dropdown-header">Categorie</li>
						<li><a href="<?php echo $this->baseUrl."/eventicat/main/1";?>"><i class="fa fa-list"></i> Lista categorie</a></li>
					</ul>
				</li>
				<?php } ?>
				<li class="<?php echo $tm["pagine"][0];?> help_pagine">
					<a href="<?php echo $this->baseUrl."/pagine/main";?>">
						<i class="fa fa-folder-open"></i> <span>Pagine</span>
					</a>
				</li>
				<?php if (v("referenze_attive")) { ?>
				<li class="<?php echo $tm["referenze"][0];?> treeview">
					<a href="#">
						<i class="fa fa-cubes"></i>
						<span><?php echo gtext("Referenze");?></span>
					</a>
					<ul class="treeview-menu">
						<li><a href="<?php echo $this->baseUrl."/referenze/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
						<li><a href="<?php echo $this->baseUrl."/referenze/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi</a></li>
					</ul>
				</li>
				<?php } ?>
				<?php if (v("team_attivo")) { ?>
				<li class="<?php echo $tm["team"][0];?> treeview">
					<a href="#">
						<i class="fa fa-users"></i>
						<span>Team</span>
					</a>
					<ul class="treeview-menu">
						<li><a href="<?php echo $this->baseUrl."/team/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
						<li><a href="<?php echo $this->baseUrl."/team/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi</a></li>
					</ul>
				</li>
				<?php } ?>
				<?php if (v("download_attivi")) { ?>
				<li class="<?php echo $tm["download"][0];?> treeview">
					<a href="#">
						<i class="fa fa-download"></i>
						<span><?php echo gtext("Downloads");?></span>
					</a>
					<ul class="treeview-menu">
						<li><a href="<?php echo $this->baseUrl."/download/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
						<li><a href="<?php echo $this->baseUrl."/download/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi</a></li>
						<?php if (v("attiva_categorie_download")) { ?>
						<li class="dropdown-header">Categorie</li>
						<li><a href="<?php echo $this->baseUrl."/downloadcat/main/1";?>"><i class="fa fa-list"></i> Lista categorie</a></li>
						<?php } ?>
					</ul>
				</li>
				<?php } ?>
				<?php if (v("mostra_avvisi")) { ?>
				<li class="<?php echo tm($tm, "avvisi");?> treeview help_avvisi">
					<a href="#">
						<i class="fa fa-bullhorn"></i>
						<span>Avvisi</span>
					</a>
					<ul class="treeview-menu">
						<li><a href="<?php echo $this->baseUrl."/avvisi/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
						<li><a href="<?php echo $this->baseUrl."/avvisi/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi</a></li>
					</ul>
				</li>
				<?php } ?>
				<?php if (v("mostra_faq")) { ?>
				<li class="<?php echo tm($tm, "faq");?> treeview help_faq">
					<a href="#">
						<i class="fa fa-question-circle"></i>
						<span>Faq</span>
					</a>
					<ul class="treeview-menu">
						<li><a href="<?php echo $this->baseUrl."/faq/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
						<li><a href="<?php echo $this->baseUrl."/faq/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi</a></li>
					</ul>
				</li>
				<?php } ?>
				<?php if (v("mostra_testimonial")) { ?>
				<li class="<?php echo tm($tm, "testimonial");?> treeview help_testimonial">
					<a href="#">
						<i class="fa fa-star"></i>
						<span>Testimonial</span>
					</a>
					<ul class="treeview-menu">
						<li><a href="<?php echo $this->baseUrl."/testimonial/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
						<li><a href="<?php echo $this->baseUrl."/testimonial/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi</a></li>
					</ul>
				</li>
				<?php } ?>
				<?php if (v("mostra_gallery")) { ?>
				<li class="<?php echo tm($tm, "gallery");?> treeview">
					<a href="#">
						<i class="fa fa-picture-o"></i>
						<span>Gallery</span>
					</a>
					<ul class="treeview-menu">
						<li><a href="<?php echo $this->baseUrl."/gallery/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
						<li><a href="<?php echo $this->baseUrl."/gallery/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi</a></li>
					</ul>
				</li>
				<?php } ?>
				<?php if (v("mostra_icone")) { ?>
				<li class="<?php echo tm($tm, "icone");?> treeview">
					<a href="#">
						<i class="fa fa-picture-o"></i>
						<span><?php echo gtext("Icone");?></span>
					</a>
					<ul class="treeview-menu">
						<li><a href="<?php echo $this->baseUrl."/icone/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
						<li><a href="<?php echo $this->baseUrl."/icone/form/insert/0";?>"><i class="fa fa-plus-circle"></i> Aggiungi</a></li>
					</ul>
				</li>
				<?php } ?>
			<?php } ?>
			<?php if (defined("APPS")) {
				foreach (APPS as $app)
				{
					$path = ROOT."/Application/Apps/".ucfirst($app)."/Menu/sito.php";
					
					if (file_exists($path))
						include($path);
				}
			} ?>
			<?php if (v("attiva_standard_cms_menu")) { ?>
			<li class="<?php echo $tm["menu1"][0];?> treeview help_menu">
				<a href="#">
					<i class="fa fa-list"></i>
					<span><?php echo gtext("Menù");?></span>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo $this->baseUrl."/menu/main?lingua=it";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
					<li><a href="<?php echo $this->baseUrl."/menu/form/insert/0?lingua=it";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
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
