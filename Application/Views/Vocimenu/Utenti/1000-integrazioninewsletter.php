<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("mostra_gestione_newsletter")) { ?>
<li class="<?php echo tm($tm, "integrazioninewsletter");?> treeview">
	<a href="#">
		<i class="fa fa-share"></i>
		<span><?php echo gtextPlain("Integrazione newsletter")?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/integrazioninewsletter/main";?>"><i class="fa fa-list"></i> <?php echo gtextPlain("Lista")?></a></li>
	</ul>
</li>
<?php } ?>
