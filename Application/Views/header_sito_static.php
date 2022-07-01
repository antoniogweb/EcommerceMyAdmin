<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_standard_cms_menu")) { ?>
	<li class="<?php echo tm($tm, "slide");?> treeview help_slide">
		<a href="#">
			<i class="fa fa-picture-o"></i>
			<span>Slide</span>
		</a>
		<ul class="treeview-menu">
			<li><a href="<?php echo $this->baseUrl."/".v("url_elenco_slide");?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
			<li><a href="<?php echo $this->baseUrl."/".v("url_inserisci_slide");?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
		</ul>
	</li>
	<?php if (v("blog_attivo")) { ?>
	<li class="<?php echo tm($tm, array("blog", "blogcat"));?> treeview help_blog">
		<a href="#">
			<i class="fa fa-rss"></i>
			<span><?php echo gtext("Blog");?></span>
		</a>
		<ul class="treeview-menu">
			<li><a href="<?php echo $this->baseUrl."/blog/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
			<li><a href="<?php echo $this->baseUrl."/blog/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
			<li class="dropdown-header"><?php echo gtext("Categorie");?></li>
			<li class="<?php echo tm($tm, array("blogcat"));?>"><a href="<?php echo $this->baseUrl."/blogcat/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista categorie");?></a></li>
		</ul>
	</li>
	<?php } ?>
	<?php if (v("mostra_eventi")) { ?>
	<li class="<?php echo tm($tm, array("eventi","eventicat"));?> treeview">
		<a href="#">
			<i class="fa fa-calendar-o"></i>
			<span><?php echo gtext("Eventi");?></span>
		</a>
		<ul class="treeview-menu">
			<li><a href="<?php echo $this->baseUrl."/eventi/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
			<li><a href="<?php echo $this->baseUrl."/eventi/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
			<li class="dropdown-header">Categorie</li>
			<li class="<?php echo tm($tm, array("eventicat"));?>"><a href="<?php echo $this->baseUrl."/eventicat/main/1";?>"><i class="fa fa-list"></i> Lista categorie</a></li>
		</ul>
	</li>
	<?php } ?>
	<li class="<?php echo tm($tm, "pagine");?> help_pagine">
		<a href="<?php echo $this->baseUrl."/pagine/main";?>">
			<i class="fa fa-folder-open"></i> <span>Pagine</span>
		</a>
	</li>
	<?php if (v("referenze_attive")) { ?>
	<li class="<?php echo tm($tm, "referenze");?> treeview">
		<a href="#">
			<i class="fa fa-cubes"></i>
			<span><?php echo gtext("Referenze");?></span>
		</a>
		<ul class="treeview-menu">
			<li><a href="<?php echo $this->baseUrl."/referenze/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
			<li><a href="<?php echo $this->baseUrl."/referenze/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
		</ul>
	</li>
	<?php } ?>
	<?php if (v("team_attivo")) { ?>
	<li class="<?php echo tm($tm, "team");?> treeview">
		<a href="#">
			<i class="fa fa-users"></i>
			<span>Team</span>
		</a>
		<ul class="treeview-menu">
			<li><a href="<?php echo $this->baseUrl."/team/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
			<li><a href="<?php echo $this->baseUrl."/team/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
		</ul>
	</li>
	<?php } ?>
	<?php if (v("download_attivi")) { ?>
	<li class="<?php echo tm($tm, "download");?> treeview">
		<a href="#">
			<i class="fa fa-download"></i>
			<span><?php echo gtext("Downloads");?></span>
		</a>
		<ul class="treeview-menu">
			<li><a href="<?php echo $this->baseUrl."/download/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
			<li><a href="<?php echo $this->baseUrl."/download/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
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
			<li><a href="<?php echo $this->baseUrl."/avvisi/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
		</ul>
	</li>
	<?php } ?>
	<?php if (v("attiva_modali")) { ?>
	<li class="<?php echo tm($tm, "modali");?> treeview">
		<a href="#">
			<i class="fa fa-flash"></i>
			<span><?php echo gtext("Popup");?></span>
		</a>
		<ul class="treeview-menu">
			<li><a href="<?php echo $this->baseUrl."/modali/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
			<li><a href="<?php echo $this->baseUrl."/modali/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
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
			<li><a href="<?php echo $this->baseUrl."/faq/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
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
			<li><a href="<?php echo $this->baseUrl."/testimonial/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
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
			<li><a href="<?php echo $this->baseUrl."/gallery/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
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
			<li><a href="<?php echo $this->baseUrl."/icone/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
		</ul>
	</li>
	<?php } ?>
	<?php if (v("mostra_servizi")) { ?>
	<li class="<?php echo tm($tm, "servizi");?> treeview">
		<a href="#">
			<i class="fa fa-rocket"></i>
			<span><?php echo gtext("Servizi");?></span>
		</a>
		<ul class="treeview-menu">
			<li><a href="<?php echo $this->baseUrl."/servizi/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
			<li><a href="<?php echo $this->baseUrl."/servizi/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
		</ul>
	</li>
	<?php } ?>
	<?php if (v("mostra_soci")) { ?>
	<li class="<?php echo tm($tm, "soci");?> treeview">
		<a href="#">
			<i class="fa fa-paw"></i>
			<span><?php echo gtext("Soci");?></span>
		</a>
		<ul class="treeview-menu">
			<li><a href="<?php echo $this->baseUrl."/soci/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
			<li><a href="<?php echo $this->baseUrl."/soci/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
		</ul>
	</li>
	<?php } ?>
	<?php if (v("mostra_progetti")) { ?>
	<li class="<?php echo tm($tm, "progetti");?> treeview">
		<a href="#">
			<i class="fa fa-hand-paper-o"></i>
			<span><?php echo gtext("Progetti");?></span>
		</a>
		<ul class="treeview-menu">
			<li><a href="<?php echo $this->baseUrl."/progetti/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
			<li><a href="<?php echo $this->baseUrl."/progetti/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
		</ul>
	</li>
	<?php } ?>
	<?php if (v("mostra_sedi")) { ?>
	<li class="<?php echo tm($tm, array("sedi", "sedicat"));?> treeview">
		<a href="#">
			<i class="fa fa-thumb-tack"></i>
			<span><?php echo gtext("Sedi");?></span>
		</a>
		<ul class="treeview-menu">
			<li><a href="<?php echo $this->baseUrl."/sedi/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
			<li><a href="<?php echo $this->baseUrl."/sedi/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
			<?php if (v("attiva_categorie_sedi")) { ?>
			<li class="dropdown-header"><?php echo gtext("Categorie");?></li>
			<li class="<?php echo tm($tm, array("sedicat"));?>"><a href="<?php echo $this->baseUrl."/sedicat/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista categorie");?></a></li>
			<?php } ?>
		</ul>
	</li>
	<?php } ?>
<?php } ?>
