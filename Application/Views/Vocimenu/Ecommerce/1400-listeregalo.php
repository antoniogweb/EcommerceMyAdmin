<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("ecommerce_attivo") && v("attiva_liste_regalo")) { ?>
<li class="<?php echo tm($tm, array("listeregalotipi","listeregalo"));?> treeview">
	<a href="#">
		<i class="fa fa-heart"></i>
		<span><?php echo gtext("Liste regalo")?></span>
	</a>
	<ul class="treeview-menu">
		<li class="dropdown-header"><?php echo gtext("Liste regalo")?></li>
		<li><a href="<?php echo $this->baseUrl."/listeregalo/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi")?></a></li>
		<li class="icon_list <?php if ($this->controller === "listeregalo") { ?>active<?php } ?>"><a href="<?php echo $this->baseUrl."/listeregalo/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Elenco liste regalo")?></a></li>
		
		<li class="dropdown-header"> <?php echo gtext("Tipologie")?></li>
		<li class="<?php echo tm($tm, array("listeregalotipi"));?>"><a href="<?php echo $this->baseUrl."/listeregalotipi/main/1";?>"><i class="fa fa-cogs"></i> <?php echo gtext("Elenco tipologie")?></a></li>
	</ul>
</li>
<?php } ?>
