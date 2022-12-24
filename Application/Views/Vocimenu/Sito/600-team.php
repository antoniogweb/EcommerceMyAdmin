<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_standard_cms_menu") && v("team_attivo")) { ?>
<li class="<?php echo tm($tm, array("team","ruoli"));?> treeview">
	<a href="#">
		<i class="fa fa-users"></i>
		<span>Team</span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/team/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/team/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></li>
		<?php if (v("attiva_ruoli")) { ?>
		<li class="dropdown-header"><?php echo gtext("Ruoli");?></li>
		<li class="<?php echo tm($tm, array("ruoli"));?>"><a href="<?php echo $this->baseUrl."/ruoli/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista ruoli");?></a></li>
		<?php } ?>
	</ul>
</li>
<?php } ?>
