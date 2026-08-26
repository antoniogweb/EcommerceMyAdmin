<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<li class="<?php echo tm($tm, "cookiearchivio");?> treeview">
	<a href="#">
		<i class="fa fa-archive"></i>
		<span><?php echo gtextPlain("Archivio cookie terzi")?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/cookiearchivio/main";?>"><i class="fa fa-list"></i> <?php echo gtextPlain("Lista")?></a></li>
	</ul>
</li>
