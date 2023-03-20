<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("ecommerce_attivo") && v("abilita_feedback")) { ?>
<li class="<?php echo tm($tm, array("feedback"));?> treeview">
	<a href="#">
		<i class="fa fa-comments-o"></i>
		<span><?php echo gtext("Feedback")?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/feedback/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
	</ul>
</li>
<?php } ?>
