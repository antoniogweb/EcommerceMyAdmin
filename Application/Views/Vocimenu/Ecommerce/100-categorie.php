<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
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
