<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (OpzioniModel::isAttiva("CAMPI_FORM_CHECKOUT", "fattura") && $ordine["tipo_cliente"] == "privato" && $ordine["fattura"]) { ?>
<tr>
	<td><?php echo gtext("Fattura");?>:</td>
	<td><b class="text text-primary"><?php echo gtext("Richiesta");?></b></td>
</tr>
<?php } ?>
