<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("ecommerce_attivo")) { ?>
<li class="<?php echo tm($tm, "nazioni");?> treeview">
	<a href="#">
		<i class="fa fa-globe"></i>
		<span><?php echo gtextPlain("Nazioni");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/nazioni/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtextPlain("Aggiungi")?></a></li>
		<li><a href="<?php echo $this->baseUrl."/nazioni/main";?>"><i class="fa fa-list"></i> <?php echo gtextPlain("Lista")?></a></li>
	</ul>
</li>
<?php } ?>
