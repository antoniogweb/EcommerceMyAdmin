<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
	gtext("Ordini effettuati") => "",
);

$titoloPagina = gtext("Ordini effettuati");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "ordini";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>
<?php if (count($ordini) > 0) { ?>
<div class="uk-overflow-auto">
	<table class="uk-table uk-table-divider uk-table-hover" cellspacing="0">
		<thead>
			<tr class="ordini_head">
				<th><?php echo gtext("Ordine");?></th>
				<th><?php echo gtext("Data");?></th>
				<th><?php echo gtext("Stato");?></th>
				<th><?php echo gtext("Totale (€)");?></th>
				<?php if (v("fatture_attive")) { ?>
				<th width="3%"><?php echo gtext("Fattura");?></th>
				<?php } ?>
			</tr>
		</thead>
		<?php foreach ($ordini as $ordine) { ?>
		<tr class="ordini_table_row">
			<td><a href="<?php echo $this->baseUrl."/resoconto-acquisto/".$ordine["orders"]["id_o"]."/".$ordine["orders"]["cart_uid"];?>?n=y">#<?php echo $ordine["orders"]["id_o"];?></a></td>
			<td><?php echo smartDate($ordine["orders"]["data_creazione"]);?></td>
			<td><?php echo statoOrdine($ordine["orders"]["stato"]);?></td>
			<td><?php echo setPriceReverse($ordine["orders"]["total"]);?></td>
			<?php if (v("fatture_attive")) { ?>
			<td><?php echo pulsanteFattura($ordine["orders"]["id_o"]);?></td>
			<?php } ?>
		</tr>
		<?php } ?>
	</table>
</div>
<?php } else { ?>
<p><?php echo gtext("Non hai effettuato alcun ordine");?></p>
<?php } ?>
<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
