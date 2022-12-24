<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("mostra_fasce_prezzo")) { ?>
<li class="<?php echo tm($tm, "fasceprezzo");?> treeview">
	<a href="#">
		<i class="fa fa-money"></i>
		<span><?php echo gtext("Fasce prezzo");?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/fasceprezzo/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi fascia");?></a></li>
		<li><a href="<?php echo $this->baseUrl."/fasceprezzo/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista fasce");?></a></li>
	</ul>
</li>
<?php } ?>
