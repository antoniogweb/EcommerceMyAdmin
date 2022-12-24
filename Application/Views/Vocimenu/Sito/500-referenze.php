<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_standard_cms_menu") && v("referenze_attive")) { ?>
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
