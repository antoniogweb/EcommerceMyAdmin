<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<tr>
	<td><?php echo gtext("Stato ordine");?>:</td>
	<td>
		<b><span class="label label-<?php echo labelStatoOrdine($ordine["stato"]);?>"><?php echo statoOrdine($ordine["stato"]);?></span></b>
		<?php if (v("attiva_log_cambio_stato")) { ?>
		<a <?php if (partial()) { ?>target="_blank"<?php } ?> class="<?php if (!partial()) { ?>iframe<?php } ?> pull-right help_storico_stati" href="<?php echo $this->baseUrl."/ordini/storicostati/".$ordine["id_o"];?>?partial=Y"><i class="fa fa-history"></i> <?php echo gtext("Vedi storico cambio stati");?></a>
		<?php } ?>
	</td>
</tr>
