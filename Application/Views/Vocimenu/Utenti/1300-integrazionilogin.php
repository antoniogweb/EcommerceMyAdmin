<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("abilita_login_tramite_app")) { ?>
<li class="<?php echo tm($tm, "integrazionilogin");?> treeview">
	<a href="#">
		<i class="fa fa-sign-in"></i>
		<span><?php echo gtext("Login tramite APP")?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/integrazionilogin/main";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista")?></a></li>
	</ul>
</li>
<?php } ?>
