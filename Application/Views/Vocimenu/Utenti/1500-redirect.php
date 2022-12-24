<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_redirect")) { ?>
<li class="<?php echo tm($tm, "redirect");?> treeview">
	<a href="#">
		<i class="fa fa-send-o"></i>
		<span><?php echo gtext("Gestione redirect")?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/redirect/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/redirect/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
	</ul>
</li>
<?php } ?>
