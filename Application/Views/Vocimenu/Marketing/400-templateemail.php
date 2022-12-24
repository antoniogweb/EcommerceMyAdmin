<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_template_email") && v("attiva_eventi_retargeting")) { ?>
<li class="<?php echo tm($tm, array("eventiretargeting","templateemail"));?> treeview">
	<a href="#">
		<i class="fa fa-plug"></i>
		<span><?php echo gtext("Email automatiche");?></span>
	</a>
	<ul class="treeview-menu">
		<li class="dropdown-header"><?php echo gtext("Template");?></li>
		<li class="<?php echo tm($tm, "templateemail");?>"><a href="<?php echo $this->baseUrl."/templateemail/main";?>"><i class="fa fa-envelope-open-o"></i> <?php echo gtext("Template email")?></a></li>
		<li class="dropdown-header"><?php echo gtext("Eventi");?></li>
		<li class="<?php echo tm($tm, "eventiretargeting");?>"><a href="<?php echo $this->baseUrl."/eventiretargeting/main";?>"><i class="fa fa-clock-o"></i> <?php echo gtext("Eventi scatenanti")?></a></li>
	</ul>
</li>
<?php } ?>
