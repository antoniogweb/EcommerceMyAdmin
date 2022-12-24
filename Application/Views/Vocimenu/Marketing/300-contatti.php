<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_sezione_contatti")) { ?>
<li class="<?php echo tm($tm, "contatti");?> treeview">
	<a href="#">
		<i class="fa fa-user-o"></i>
		<span><?php echo gtext("Contatti")?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/contatti/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
	</ul>
</li>
<?php } ?>
