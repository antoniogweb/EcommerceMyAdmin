<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtextPlain("Home") 		=> $this->baseUrl,
	gtextPlain("Area riservata")	=>	$this->baseUrl."/".Url::routeToUrl("area-riservata"),
	gtextPlain("Ordini effettuati") => "",
);

$titoloPagina = gtextPlain("Ordini effettuati");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "ordini";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>
<?php if (count($ordini) > 0) { ?>
<div class="uk-overflow-auto" role="region" tabindex="0" aria-label="<?php echo gtextAttr("Elenco ordini", false);?>">
	<table class="uk-table uk-table-divider uk-table-hover" cellspacing="0" aria-label="<?php echo gtextAttr("Ordini effettuati", false);?>">
		<thead>
			<tr class="ordini_head">
				<th scope="col"><?php echo gtextPlain("Ordine");?></th>
				<th scope="col"><?php echo gtextPlain("Data");?></th>
				<th scope="col"><?php echo gtextPlain("Stato");?></th>
				<th scope="col"><?php echo gtextPlain("Totale (€)");?></th>
				<?php if (v("fatture_attive")) { ?>
				<th scope="col" width="3%"><?php echo gtextPlain("Fattura");?></th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($ordini as $ordine) { ?>
		<tr class="ordini_table_row uk-text-small">
			<td><a aria-label="<?php echo gtextAttr("Dettaglio ordine numero", false)." ".(int)$ordine["orders"]["id_o"];?>" href="<?php echo $this->baseUrl."/resoconto-acquisto/".$ordine["orders"]["id_o"]."/".$ordine["orders"]["cart_uid"]."/".$ordine["orders"]["admin_token"];?>?n=y">#<?php echo $ordine["orders"]["id_o"];?></a></td>
			<td><?php echo smartDate($ordine["orders"]["data_creazione"]);?></td>
			<td><?php echo statoOrdine($ordine["orders"]["stato"]);?></td>
			<td><?php echo setPriceReverse($ordine["orders"]["total"]);?></td>
			<?php if (v("fatture_attive")) { ?>
			<td><?php echo pulsanteFattura($ordine["orders"]["id_o"]);?></td>
			<?php } ?>
		</tr>
		<?php } ?>
		</tbody>
	</table>
</div>
<?php } else { ?>
<p><?php echo gtextPlain("Non hai effettuato alcun ordine");?></p>
<?php } ?>
<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
