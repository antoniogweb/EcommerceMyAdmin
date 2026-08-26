<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="panel panel-info">
	<div class="panel-heading">
		<div class="pull-right pulsanti_genera_invia_pdf">
			<?php include($this->viewPath("top_steps_buttons"));?>
		</div>
		<?php echo gtextPlain("N° Ordine");?> #<b><?php echo $ordine["numero_ordine"];?></b></b>
	</div>
	<div class="text-left panel-body">
		<div class="row">
			<div class="col-lg-6">
				<table class="table table-striped" style="margin-bottom:0px;">
					<tr>
						<td><?php echo gtextPlain("N° Ordine");?>:</td>
						<td><b>#<?php echo $ordine["numero_ordine"];?></b></td>
					</tr>
					<tr>
						<td><?php echo gtextPlain("Data ordine");?>:</td>
						<td><b><?php echo F::getDateInCorrectFormat(strtotime($ordine["data_ordine"]));?></b></td>
					</tr>
					<?php $fornitore = FornitoriModel::g(false)->selectId((int)$ordine["id_fornitore"]);?>
					<?php if (!empty($fornitore)) { ?>
					<tr>
						<td><?php echo gtextPlain("Fornitore");?>:</td>
						<td>
							<b><?php echo $fornitore["ragione_sociale"];?></b>
							<?php if ($fornitore["email_amministrativa"]) { ?>
							<br /><i class="fa fa-envelope"></i> <?php echo $fornitore["email_amministrativa"];?>
							<?php } ?>
							<?php if ($fornitore["telefono"]) { ?>
							<br /><i class="fa fa-phone"></i> <?php echo $fornitore["telefono"];?>
							<?php } ?>
						</td>
					</tr>
					<?php } ?>
					<tr>
						<td><?php echo gtextPlain("Stato ordine");?>:</td>
						<td>
							<?php echo OrdiniacquistoModel::g(false)->statoordinelabel(array("ordini_acquisto" => $ordine));?>
							<?php if (OrdiniacquistostatistoricoModel::numero((int)$ordine["id_ordine_acquisto"])) { ?>
							<a <?php if (partial()) { ?>target="_blank"<?php } ?> class="<?php if (!partial()) { ?>iframe<?php } ?> pull-right help_storico_stati_ordini_acquisto" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/storicostati/".$ordine["id_ordine_acquisto"];?>?partial=Y"><i class="fa fa-history"></i> <?php echo gtextPlain("Vedi storico cambio stato");?></a>
							<?php } ?>
						</td>
					</tr>
					<?php $ricezioniCollegate = OrdiniacquistoModel::g()->ricezioniCollegate((int)$ordine["id_ordine_acquisto"]);?>
					<?php if (count($ricezioniCollegate) > 0) { ?>
					<table class="table table-striped" style="margin-bottom:0px;">
						<tr>
							<td><?php echo gtextPlain("Ricezioni").":";?></td>
							<td>
								<?php foreach ($ricezioniCollegate as $ricezione) { ?>
								<?php echo gtextPlain("N°");?> <a target="_blank" href="<?php echo $this->baseUrl."/$urlOrdineAcquistoRicezioni/form/update/".$ricezione["ordini_acquisto_ricezioni"]["id_ordine_acquisto_ricezione"];?>"><b><?php echo $ricezione["ordini_acquisto_ricezioni"]["id_ordine_acquisto_ricezione"];?></b></a> <?php echo gtextPlain("del");?> <b><?php echo smartDate($ricezione["ordini_acquisto_ricezioni"]["data_ricezione_merce"], v("default_date_format"));?></b> <?php echo OrdiniacquistoricezioniModel::g()->statoLabelCrud($ricezione);?>
								<br />
								<?php } ?>
							</td>
						</tr>
					</table>
					<?php } ?>
				</table>
			</div>
			<div class="col-lg-6">
				<table class="table table-striped" style="margin-bottom:0px;">
					<?php if ($ordine["imponibile"] != $ordine["imponibile_pieno"]) { ?>
					<tr>
						<td><?php echo gtextPlain("Imponibile");?>:</td>
						<td><b>&euro; <?php echo setPriceReverse($ordine["imponibile_pieno"]);?></b></td>
					</tr>
					<tr>
						<td><?php echo gtextPlain("Imponibile scontato");?>:</td>
						<td><b>&euro; <?php echo setPriceReverse($ordine["imponibile"]);?></b></td>
					</tr>
					<?php } else { ?>
					<tr>
						<td><?php echo gtextPlain("Imponibile");?>:</td>
						<td><b>&euro; <?php echo setPriceReverse($ordine["imponibile"]);?></b></td>
					</tr>
					<?php } ?>
					<tr>
						<td><?php echo gtextPlain("Iva");?>:</td>
						<td><b>&euro; <?php echo setPriceReverse($ordine["iva"]);?></b></td>
					</tr>
					<tr>
						<td><?php echo gtextPlain("Totale");?>:</td>
						<td><b>&euro; <?php echo setPriceReverse($ordine["totale"]);?></b></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
