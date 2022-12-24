<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("mostra_gestione_antispam")) { ?>
<li class="<?php echo tm($tm, "captcha");?> treeview">
	<a href="#">
		<i class="fa fa-shield"></i>
		<span><?php echo gtext("Gestione antispam")?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/captcha/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
	</ul>
</li>
<?php } ?>
