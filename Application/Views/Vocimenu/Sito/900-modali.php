<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_standard_cms_menu") && v("attiva_modali")) { ?>
<li class="<?php echo tm($tm, "modali");?> treeview">
	<a href="#">
		<i class="fa fa-flash"></i>
		<span><?php echo gtextPlain("Popup");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/modali/main";?>"><i class="fa fa-list"></i> <?php echo gtextPlain("Lista");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/modali/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtextPlain("Aggiungi");?></a></li>
	</ul>
</li>
<?php } ?>
