<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$linguaNazioneUrl = v("attiva_nazione_nell_url") ? $ordine["lingua"]."_".strtolower($ordine["nazione"]) : $ordine["lingua"];
?>
<table class="table table-striped">
	<tr>
		<td><?php echo gtext("N° Ordine");?>:</td>
		<td><b>#<?php echo $ordine["id_o"];?></b> 
			<?php if (!isset($nascondiVediLatoCliente)) { ?>
			<a <?php if (partial()) { ?>target="_blank"<?php } ?> class="<?php if (!partial()) { ?>iframe<?php } ?> pull-right help_ordine_lato_cliente" href="<?php echo Domain::$name."/".$linguaNazioneUrl."/resoconto-acquisto/".$ordine["id_o"]."/".$ordine["cart_uid"]."/".$ordine["admin_token"]."?n=y";?>"><i class="fa fa-eye"></i> <?php echo gtext("Vedi ordine lato cliente");?></a>
			<?php } ?>
		</td>
	</tr>
	<tr>
		<td><?php echo gtext("Data");?>:</td>
		<td><b><?php echo smartDate($ordine["data_creazione"]);?></b></td>
	</tr>
	<tr>
		<td><?php echo gtext("Totale");?>:</td>
		<td><b>&euro; <?php echo setPriceReverse($ordine["total"]);?></b></td>
	</tr>
	<?php if (true or strcmp($tipoOutput,"web") === 0 or strcmp($ordine["pagamento"],"bonifico") === 0 or strcmp($ordine["pagamento"],"contrassegno") === 0) { ?>
	<tr>
		<td><?php echo gtext("Stato ordine");?>:</td>
		<td><b><span class="label label-<?php echo labelStatoOrdine($ordine["stato"]);?>"><?php echo statoOrdine($ordine["stato"]);?></span></b></td>
	</tr>
	<?php } ?>
	<?php if (!isset($nascondiStatoPagamento)) { ?>
	<tr>
		<td><?php echo gtext("Stato pagamento");?>:</td>
		<td>
			<?php if ($ordine["pagato"] || StatiordineModel::g(false)->pagato($ordine["stato"])) { ?>
			<span class="label label-success"><?php echo gtext("Ordine pagato");?></span>
				<?php if ($ordine["data_pagamento"]) { ?>
				<?php echo gtext("in data");?> <b><?php echo date("d-m-Y H:i", strtotime($ordine["data_pagamento"]));?></b>
				<?php } ?>
			<?php } else { ?>
			<span class="label label-warning"><?php echo gtext("Ordine NON pagato");?></span>
			<?php } ?>
		</td>
	</tr>
	<?php } ?>
	<?php if (!isset($nascondiMetodoDiPagamento)) { ?>
	<tr>
		<td><?php echo gtext("Metodo di pagamento");?>:</td>
		<td><b><?php echo metodoPagamento($ordine["pagamento"]);?></b></td>
	</tr>
	<?php } ?>
	<?php if (v("permetti_ordini_offline") && !isset($nascondiTipoOrdine)) { ?>
	<tr>
		<td><?php echo gtext("Tipo ordine");?>:</td>
		<td><b><?php echo OrdiniModel::getLabelTipoOrdine($ordine["tipo_ordine"]);?></b></td>
	</tr>
	<?php } ?>
	<?php if (!$ordine["da_spedire"] && !empty($corriere)) { ?>
	<tr>
		<td><?php echo gtext("Tipo di consegna");?>:</td>
		<td><b><?php echo $corriere["titolo"];?></b></td>
	</tr>
	<?php } ?>
	<?php if (v("attiva_ip_location")) { ?>
	<tr>
		<td><?php echo gtext("Nazione navigazione");?>:</td>
		<td><b><?php echo findTitoloDaCodice($ordine["nazione_navigazione"]);?></b></td>
	</tr>
	<?php } ?>
	<?php if (OpzioniModel::isAttiva("CAMPI_FORM_CHECKOUT", "fattura") && $ordine["tipo_cliente"] == "privato" && $ordine["fattura"]) { ?>
	<tr>
		<td><?php echo gtext("Fattura");?>:</td>
		<td><b class="text text-primary"><?php echo gtext("Richiesta");?></b></td>
	</tr>
	<?php } ?>
	<?php if ($ordine["codice_promozione"]) { ?>
	<tr>
		<td><?php echo gtext("Coupon");?>:</td>
		<td>
			<b><?php echo $ordine["codice_promozione"];?></b> (<i><?php echo $ordine["nome_promozione"];?></i>)
		</td>
	</tr>
	<?php } ?>
	<?php if (v("attiva_agenti") && $ordine["id_agente"]) { ?>
	<tr>
		<td><?php echo gtext("Agente");?>:</td>
		<td>
			<b class="text text-primary"><?php echo OrdiniModel::g()->agenteCrud(array("orders"=>$ordine));?></b>
		</td>
	</tr>
	<?php } ?>
	<?php if ($ordine["id_lista_regalo"]) { ?>
	<tr>
		<td><?php echo gtext("Lista regalo");?>:</td>
		<td>
			<?php echo ListeregaloModel::specchietto($ordine["id_lista_regalo"]);?>
		</td>
	</tr>
	<?php } ?>
	<?php if (v("attiva_gestione_pixel")) {
		$eventiPixel = PixeleventiModel::getStatusPixelEventoElemento("PURCHASE", $ordine["id_o"], "orders");
		
		if (count($eventiPixel) > 0)
		{
	?>
		<tr>
			<td><?php echo gtext("Pixel");?>:</td>
			<td>
				<?php foreach ($eventiPixel as $ev) { ?>
					<b><?php echo $ev["pixel"]["titolo"];?></b>
					<?php echo gtext("inviato in data/ora").": ".date("d-m-Y H:i", strtotime($ev["pixel_eventi"]["data_creazione"]));?> <i class="text text-success fa fa-thumbs-up"></i>
					<a class="iframe" title="<?php echo gtext("Vedi codice script");?>" href="<?php echo $this->baseUrl."/ordini/vediscriptpixel/".$ev["pixel_eventi"]["id_pixel_evento"];?>?partial=Y&nobuttons=Y"><i class="fa fa-eye"></i></a>
					<br />
				<?php } ?>
			</td>
		</tr>
		<?php } ?>
	<?php } ?>
	<?php if (!isset($nascondiCreaSpedizione) && v("attiva_gestione_spedizioni") && OrdiniModel::daSpedire($ordine["id_o"])) { ?>
	<tr>
		<td><?php echo gtext("Spedizione");?>:</td>
		<td>
			<?php
			$righeDaSpedire = OrdiniModel::righeDaSpedire($ordine["id_o"]);
			
			echo SpedizioninegozioModel::g(false)->badgeSpedizione($ordine["id_o"]);?>
			
			<?php if (count($righeDaSpedire) > 0 && ControllersModel::checkAccessoAlController(array("spedizioninegozio"))) { ?>
			<div>
				<?php $queryStringCreaSpedizioneLista = $ordine["id_lista_regalo"] ? "&id_lista_regalo=".(int)$ordine["id_lista_regalo"] : "";?>
				<a class="iframe badge" href="<?php echo $this->baseUrl."/spedizioninegozio/form/insert/0?id_o=".$ordine["id_o"].$queryStringCreaSpedizioneLista;?>&partial=Y"><i class="fa fa-plus-square-o"></i> <?php echo gtext("Crea spedizione");?></a>
			</div>
			<?php } ?>
		</td>
	</tr>
	<?php } ?>
</table>
