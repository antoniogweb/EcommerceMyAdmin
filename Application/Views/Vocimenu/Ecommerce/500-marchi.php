<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("usa_marchi")) { ?>
<li class="<?php echo tm($tm, array("marchi"));?> treeview help_marchi">
	<a href="#">
		<i class="fa fa-font-awesome"></i>
		<span><?php echo gtextPlain("famiglie",true,"ucfirst");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/marchi/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtextPlain("Aggiungi");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/marchi/main";?>"><i class="fa fa-list"></i> <?php echo gtextPlain("Lista");?></a></li>
	</ul>
</li>
<?php } ?>
