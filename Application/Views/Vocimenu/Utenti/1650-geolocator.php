<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_gestione_geolocator")) { ?>
<li class="<?php echo tm($tm, "geolocator");?> treeview">
	<a href="#">
		<i class="fa fa-globe"></i>
		<span><?php echo gtextPlain("Gestione geolocator")?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/geolocator/main";?>"><i class="fa fa-list"></i> <?php echo gtextPlain("Lista")?></a></li>
	</ul>
</li>
<?php } ?>
