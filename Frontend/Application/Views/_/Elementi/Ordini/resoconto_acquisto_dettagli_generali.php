<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php echo flash("stato_modificato");?>

<table class="table uk-width-3-4@m uk-table uk-table-divider uk-table-hover uk-margin-remove-top uk-table-small">
	<tr>
		<td class="first_column"><?php echo gtextPlain("Ordine", false); ?>:</td>
		<td><b>#<?php echo $ordine["id_o"];?></b></td>
	</tr>
	<tr>
		<td class="first_column"><?php echo gtextPlain("Data", false); ?>:</td>
		<td><b><?php echo smartDate($ordine["data_creazione"]);?></b></td>
	</tr>
	<tr>
		<td class="first_column"><?php echo gtextPlain("Totale", false); ?>:</td>
		<td><b>&euro; <?php echo setPriceReverse($ordine["total"]);?></b></td>
	</tr>
	<?php if ($ordine["acconto"] > 0) { ?>
	<tr>
		<td class="first_column"><?php echo gtextPlain("Acconto", false); ?>:</td>
		<td><b>&euro; <?php echo setPriceReverse($ordine["acconto"]);?></b></td>
	</tr>
	<tr>
		<td class="first_column"><?php echo gtextPlain("Saldo", false); ?>:</td>
		<td><b>&euro; <?php echo setPriceReverse($ordine["saldo"]);?></b></td>
	</tr>
	<?php } ?>
	<?php if (strcmp($tipoOutput,"web") === 0 || !OrdiniModel::conPagamentoOnline($ordine)) { ?>
	<tr>
		<td class="first_column"><?php echo gtextPlain("Stato ordine", false); ?>:</td>
		<td class="uk-flex uk-flex-between">
			<b><?php echo statoOrdine($ordine["stato"]);?></b>
			
			<?php if (strcmp($tipoOutput,"web") === 0 && $puoAnnullare) { ?>
			<a class="uk-button uk-button-danger uk-button-small" href="<?php echo $baseUrl."resoconto-acquisto/".$ordine["id_o"]."/".$ordine["cart_uid"]."/".$ordine["admin_token"];?>?n=y&annulla_ordine"><?php echo gtextPlain("Annulla ordine")?></a>
			<?php } ?>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td class="first_column"><?php echo gtextPlain("Metodo di pagamento", false); ?>:</td>
		<td><b><?php echo metodoPagamento($ordine["pagamento"]);?></b></td>
	</tr>
	<?php if (OpzioniModel::isAttiva("CAMPI_FORM_CHECKOUT", "fattura") && $ordine["tipo_cliente"] == "privato" && $ordine["fattura"]) { ?>
	<tr>
		<td><?php echo gtextPlain("Fattura");?>:</td>
		<td><b><?php echo gtextPlain("Richiesta");?></b></td>
	</tr>
	<?php } ?>
	<?php if ($ordine["id_corriere"] && v("mostra_modalita_spedizione_in_resoconto") && ($ordine["da_spedire"] || $ordine["mostra_sempre_corriere"])) { ?>
	<tr>
		<td><?php echo gtextPlain("Modalità di spedizione", false); ?>:</td>
		<td><b><?php echo gtextPlain(CorrieriModel::g()->where(array("id_corriere"=>(int)$ordine["id_corriere"]))->field("titolo"));?></b></td>
	</tr>
	<?php } ?>
	<?php if (OrdiniModel::g()->pdfScaricabile((int)$ordine["id_o"])) { ?>
	<tr>
		<td><?php echo gtextPlain("PDF ordine", false); ?>:</td>
		<td><a target="_blank" class="uk-button uk-button-primary uk-button-small" href="<?php echo $baseUrl."pdf-ordine/".$ordine["id_o"]."/".$ordine["cart_uid"]."/".$ordine["admin_token"];?>"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/download.svg");?></span> <?php echo gtextPlain("Scarica PDF", false); ?></a></td>
	</tr>
	<?php } ?>
	<?php if (v("attiva_gestione_spedizioni")) {
		$spModel = SpedizioninegozioModel::g();
		$spedizioniOrdine = $spModel->getSpedizioniOrdine((int)$ordine["id_o"]);
		
		if (count($spedizioniOrdine) > 0) { 
	?>
		<tr>
			<td class="first_column"><?php echo gtextPlain("Stato spedizione", false); ?>:</td>
			<td><b><?php echo $spModel->badgeSpedizione((int)$ordine["id_o"], 0, true, "<hr />", "uk-label");;?></b></td>
		</tr>
		<?php } ?>
	<?php } ?>
	<?php
	$tabellaPeriodiReso = OrdiniModel::g(false)->gTabellaPeriodiResoNonIdSpedizione($ordine["id_o"]);
	if (count($tabellaPeriodiReso) > 0 && StatiordineModel::g(false)->permettiReso($ordine["stato"])) { ?>
		<?php foreach ($tabellaPeriodiReso as $pr) {
			if ($pr["richiesta"] || OrdiniperiodiresoModel::g(false)->inPeriodoReso($pr["id_o_periodo_reso"])) {
		?>
			<tr>
				<td><?php echo gtextPlain("Reso", false); ?>:</td>
				<td>
					<?php include(tpf("/Elementi/Ordini/resoconto_acquisto_dettagli_reso.php"));?>
				</td>
			</tr>
			<?php } ?>
		<?php } ?>
	<?php } ?>
</table>
