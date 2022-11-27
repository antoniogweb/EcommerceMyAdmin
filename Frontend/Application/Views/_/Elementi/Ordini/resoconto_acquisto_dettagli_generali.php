<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<table class="table uk-width-2-3@m uk-table uk-table-divider uk-table-hover uk-margin-remove-top">
	<tr>
		<td class="first_column"><?php echo gtext("Ordine", false); ?>:</td>
		<td><b>#<?php echo $ordine["id_o"];?></b></td>
	</tr>
	<tr>
		<td class="first_column"><?php echo gtext("Data", false); ?>:</td>
		<td><b><?php echo smartDate($ordine["data_creazione"]);?></b></td>
	</tr>
	<tr>
		<td class="first_column"><?php echo gtext("Totale", false); ?>:</td>
		<td><b>&euro; <?php echo setPriceReverse($ordine["total"]);?></b></td>
	</tr>
	<?php if (strcmp($tipoOutput,"web") === 0 || !OrdiniModel::conPagamentoOnline($ordine)) { ?>
	<tr>
		<td class="first_column"><?php echo gtext("Stato ordine", false); ?>:</td>
		<td><b><?php echo statoOrdine($ordine["stato"]);?></b></td>
	</tr>
	<?php } ?>
	<tr>
		<td class="first_column"><?php echo gtext("Metodo di pagamento", false); ?>:</td>
		<td><b><?php echo metodoPagamento($ordine["pagamento"]);?></b></td>
	</tr>
	<?php if (OpzioniModel::isAttiva("CAMPI_FORM_CHECKOUT", "fattura") && $ordine["tipo_cliente"] == "privato" && $ordine["fattura"]) { ?>
	<tr>
		<td><?php echo gtext("Fattura");?>:</td>
		<td><b><?php echo gtext("Richiesta");?></b></td>
	</tr>
	<?php } ?>
</table>
