<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_gestione_opzioni")) { ?>
<li class="<?php echo tm($tm, "opzioni");?> treeview">
	<a href="#">
		<i class="fa fa-list-ol"></i>
		<span><?php echo gtext("Gestione opzioni")?></span>
	</a>
	<ul class="treeview-menu">
		<?php $elencoArrayLinkOpzioni = OpzioniModel::getElencoCodiciLabel();?>
		<?php foreach ($elencoArrayLinkOpzioni as $codice => $label) { ?>
		<li><a href="<?php echo $this->baseUrl."/opzioni/main?codice=$codice";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista $label")?></a></li>
		<?php } ?>
	</ul>
</li>
<?php } ?>
