<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<table class="table table-striped">
	<?php include(ROOT."/Application/Views/Ordini/ElementiTop/numero_ordine.php")?>
	<?php include(ROOT."/Application/Views/Ordini/ElementiTop/data_creazione.php")?>
	<?php include(ROOT."/Application/Views/Ordini/ElementiTop/total.php")?>
	<?php include(ROOT."/Application/Views/Ordini/ElementiTop/acconto.php")?>
	<?php include(ROOT."/Application/Views/Ordini/ElementiTop/stato.php")?>
	<?php if (!isset($nascondiStatoPagamento)) { ?>
	<?php include(ROOT."/Application/Views/Ordini/ElementiTop/stato_pagamento.php")?>
	<?php } ?>
	<?php if (!isset($nascondiMetodoDiPagamento)) { ?>
	<?php include(ROOT."/Application/Views/Ordini/ElementiTop/pagamento.php")?>
	<?php } ?>
	<?php if (v("permetti_ordini_offline") && !isset($nascondiTipoOrdine)) { ?>
	<?php include(ROOT."/Application/Views/Ordini/ElementiTop/tipo_ordine.php")?>
	<?php } ?>
	<?php include(ROOT."/Application/Views/Ordini/ElementiTop/tipo_consegna.php")?>
	<?php include(ROOT."/Application/Views/Ordini/ElementiTop/nazione_navigazione.php")?>
	<?php include(ROOT."/Application/Views/Ordini/ElementiTop/fattura.php")?>
	<?php include(ROOT."/Application/Views/Ordini/ElementiTop/codice_promozione.php")?>
	<?php if (isset($mostraClienteInSpecchietto)) { ?>
	<?php include(ROOT."/Application/Views/Ordini/ElementiTop/cliente.php")?>
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
