<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="panel panel-info">
	<div class="panel-heading">
		<div class="pull-right pulsanti_genera_invia_pdf">
			<a class="pull-right btn btn-xs btn-warning btn-rounded make_spinner" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/inviapdf/".$ordine["id_ordine_acquisto"];?>"><i class="fa fa-envelope"></i> <?php echo gtext("Invia PDF");?></a>
			
			<a target="_blank" class="pull-right btn btn-xs btn-success btn-rounded" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/stampapdf/".$ordine["id_ordine_acquisto"];?>"><i class="fa fa-file-pdf-o"></i> <?php echo gtext("Stampa PDF");?></a>
		</div>
		<?php echo gtext("N° Ordine");?> #<b><?php echo $ordine["numero_ordine"];?></b></b>
	</div>
	<div class="text-left panel-body">
		<div class="row">
			<div class="col-lg-6">
				<table class="table table-striped" style="margin-bottom:0px;">
					<tr>
						<td><?php echo gtext("N° Ordine");?>:</td>
						<td><b>#<?php echo $ordine["numero_ordine"];?></b></td>
					</tr>
					<tr>
						<td><?php echo gtext("Data ordine");?>:</td>
						<td><b><?php echo F::getDateInCorrectFormat(strtotime($ordine["data_ordine"]));?></b></td>
					</tr>
					<?php $fornitore = FornitoriModel::g(false)->selectId((int)$ordine["id_fornitore"]);?>
					<?php if (!empty($fornitore)) { ?>
					<tr>
						<td><?php echo gtext("Fornitore");?>:</td>
						<td>
							<b><?php echo $fornitore["ragione_sociale"];?></b>
							<br /><i class="fa fa-envelope"></i> <?php echo $fornitore["email_amministrativa"];?>
							<br /><i class="fa fa-phone"></i> <?php echo $fornitore["telefono"];?>
						</td>
					</tr>
					<?php } ?>
					<tr>
						<td><?php echo gtext("Stato ordine");?>:</td>
						<td>
							<?php echo OrdiniacquistoModel::g(false)->statoordinelabel(array("ordini_acquisto" => $ordine));?>
							<?php if (OrdiniacquistostatistoricoModel::numero((int)$ordine["id_ordine_acquisto"])) { ?>
							<a <?php if (partial()) { ?>target="_blank"<?php } ?> class="<?php if (!partial()) { ?>iframe<?php } ?> pull-right help_storico_stati_ordini_acquisto" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/storicostati/".$ordine["id_ordine_acquisto"];?>?partial=Y"><i class="fa fa-history"></i> <?php echo gtext("Vedi storico cambio stato");?></a>
							<?php } ?>
						</td>
					</tr>
				</table>
			</div>
			<div class="col-lg-6">
				<table class="table table-striped" style="margin-bottom:0px;">
					<?php if ($ordine["imponibile"] != $ordine["imponibile_pieno"]) { ?>
					<tr>
						<td><?php echo gtext("Imponibile");?>:</td>
						<td><b>&euro; <?php echo setPriceReverse($ordine["imponibile_pieno"]);?></b></td>
					</tr>
					<tr>
						<td><?php echo gtext("Imponibile scontato");?>:</td>
						<td><b>&euro; <?php echo setPriceReverse($ordine["imponibile"]);?></b></td>
					</tr>
					<?php } else { ?>
					<tr>
						<td><?php echo gtext("Imponibile");?>:</td>
						<td><b>&euro; <?php echo setPriceReverse($ordine["imponibile"]);?></b></td>
					</tr>
					<?php } ?>
					<tr>
						<td><?php echo gtext("Iva");?>:</td>
						<td><b>&euro; <?php echo setPriceReverse($ordine["iva"]);?></b></td>
					</tr>
					<tr>
						<td><?php echo gtext("Totale");?>:</td>
						<td><b>&euro; <?php echo setPriceReverse($ordine["totale"]);?></b></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
