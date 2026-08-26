<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_standard_cms_menu") && v("mostra_storia")) { ?>
<li class="<?php echo tm($tm, "storia");?> treeview">
	<a href="#">
		<i class="fa fa-history"></i>
		<span><?php echo gtextPlain("Storia");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/storia/main";?>"><i class="fa fa-list"></i> <?php echo gtextPlain("Lista");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/storia/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtextPlain("Aggiungi");?></a></li>
	</ul>
</li>
<?php } ?>
