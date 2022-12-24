<?php if (!defined('EG')) die('Direct access not allowed!'); ?>






<?php if (v("attiva_standard_cms_menu") && v("download_attivi")) { ?>
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
<?php if (v("attiva_standard_cms_menu") && v("mostra_avvisi")) { ?>
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
<?php if (v("attiva_standard_cms_menu") && v("attiva_modali")) { ?>
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
<?php if (v("attiva_standard_cms_menu") && v("mostra_faq")) { ?>
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
<?php if (v("attiva_standard_cms_menu") && v("mostra_testimonial")) { ?>
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
<?php if (v("attiva_standard_cms_menu") && v("mostra_gallery")) { ?>
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
<?php if (v("attiva_standard_cms_menu") && v("mostra_icone")) { ?>
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
<?php if (v("attiva_standard_cms_menu") && v("mostra_servizi")) { ?>
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
<?php if (v("attiva_standard_cms_menu") && v("mostra_soci")) { ?>
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
<?php if (v("attiva_standard_cms_menu") && v("mostra_progetti")) { ?>
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
<?php if (v("attiva_standard_cms_menu") && v("mostra_sedi")) { ?>
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
<?php if (v("attiva_standard_cms_menu") && v("mostra_alimenti")) { ?>
<li class="<?php echo tm($tm, array("alimenti", "alimenticat"));?> treeview">
	<a href="#">
		<i class="fa fa-shopping-basket"></i>
		<span><?php echo gtext("Alimenti");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/alimenti/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/alimenti/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
		<?php if (v("attiva_categorie_sedi")) { ?>
		<li class="dropdown-header"><?php echo gtext("Categorie");?></li>
		<li class="<?php echo tm($tm, array("alimenticat"));?>"><a href="<?php echo $this->baseUrl."/alimenticat/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista categorie");?></a></li>
		<?php } ?>
	</ul>
</li>
<?php } ?>
<?php if (v("attiva_standard_cms_menu") && v("mostra_ricette")) { ?>
<li class="<?php echo tm($tm, "ricette");?> treeview">
	<a href="#">
		<i class="fa fa-cutlery"></i>
		<span><?php echo gtext("Ricette");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/ricette/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/ricette/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
	</ul>
</li>
<?php } ?>
<?php if (v("attiva_standard_cms_menu") && v("mostra_storia")) { ?>
<li class="<?php echo tm($tm, "storia");?> treeview">
	<a href="#">
		<i class="fa fa-history"></i>
		<span><?php echo gtext("Storia");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/storia/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/storia/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
	</ul>
</li>
<?php } ?>
<?php if (v("attiva_standard_cms_menu") && v("attiva_gestione_menu")) { ?>
<li class="<?php echo tm($tm, "menu1");?> treeview help_menu">
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
