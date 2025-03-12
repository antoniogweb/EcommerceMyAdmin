<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("ecommerce_attivo") && v("attiva_sezione_download_documenti")) { ?>
<li class="<?php echo tm($tm, array("documentidownload"));?> treeview">
	<a href="#">
		<i class="fa fa-download"></i>
		<span><?php echo gtext("Download documenti")?></span>
	</a>
	<ul class="treeview-menu">
		<li class="icon_list <?php if ($this->controller === "documentidownload") { ?>active<?php } ?>"><a href="<?php echo $this->baseUrl."/documentidownload/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Elenco download documenti")?></a></li>
	</ul>
</li>
<?php } ?>
