<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("mostra_tipi_documento")) { ?>
<li class="<?php echo tm($tm, "tipidocumento");?> treeview">
	<a href="#">
		<i class="fa fa-list"></i>
		<span><?php echo gtextPlain("Tipi documenti");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/tipidocumento/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtextPlain("Aggiungi tipo");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/tipidocumento/main/1";?>"><i class="fa fa-list"></i> <?php echo gtextPlain("Lista tipi");?></a></li>
	</ul>
</li>
<?php } ?>
