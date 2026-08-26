<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_standard_cms_menu") && v("mostra_partner")) { ?>
<li class="<?php echo tm($tm, array("partner", "partnercat"));?> treeview help_partner">
	<a href="#">
		<i class="fa fa-handshake-o"></i>
		<span><?php echo gtextPlain("Partner");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/partner/main";?>"><i class="fa fa-list"></i> <?php echo gtextPlain("Lista");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/partner/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtextPlain("Aggiungi");?></a></li>
		<li class="dropdown-header"><?php echo gtextPlain("Categorie");?></li>
		<li class="<?php echo tm($tm, array("partnercat"));?>"><a href="<?php echo $this->baseUrl."/partnercat/main/1";?>"><i class="fa fa-list"></i> <?php echo gtextPlain("Lista categorie");?></a></li>
	</ul>
</li>
<?php } ?>
