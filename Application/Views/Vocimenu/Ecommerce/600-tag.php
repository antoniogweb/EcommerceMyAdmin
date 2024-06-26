<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("usa_tag") && v("tag_in_prodotti")) { ?>
<li class="<?php echo tm($tm, array("tag"));?> treeview help_tag">
	<a href="#">
		<i class="fa fa-tags"></i>
		<span><?php echo gtext("Tag / Linee",true,"ucfirst");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/tag/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/tag/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
	</ul>
</li>
<?php } ?>
