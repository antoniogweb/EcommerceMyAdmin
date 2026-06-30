<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="panel panel-info">
	<div class="panel-heading">
		<div class="pull-right pulsanti_genera_invia_pdf">
			<?php if ($ricezione["chiuso"]) { ?>
				<a class="pull-right btn btn-xs btn-warning btn-rounded make_spinner" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/chiudi/".$ricezione["id_ordine_acquisto_ricezione"];?>/0"><i class="fa fa-unlock"></i> <?php echo gtext("Apri");?></a>
			<?php } else { ?>
				<a class="pull-right btn btn-xs btn-success btn-rounded make_spinner" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/chiudi/".$ricezione["id_ordine_acquisto_ricezione"];?>/1"><i class="fa fa-lock"></i> <?php echo gtext("Chiudi");?></a>
			<?php } ?>
		</div>
		<?php echo gtext("N° Ricezione");?> #<b><?php echo $ricezione["id_ordine_acquisto_ricezione"];?></b></b>
	</div>
	<div class="text-left panel-body">
		<div class="row">
			<div class="col-lg-6">
				<table class="table table-striped" style="margin-bottom:0px;">
					<tr>
						<td><?php echo gtext("N° Ricezione");?>:</td>
						<td><b>#<?php echo $ricezione["id_ordine_acquisto_ricezione"];?></b></td>
					</tr>
					<tr>
						<td><?php echo gtext("Numero DDT");?>:</td>
						<td><b><?php echo $ricezione["numero_documento_trasporto"];?></b></td>
					</tr>
					<tr>
						<td><?php echo gtext("Data ricezione");?>:</td>
						<td><b><?php echo F::getDateInCorrectFormat(strtotime($ricezione["data_ricezione_merce"]));?></b></td>
					</tr>
				</table>
			</div>
			<div class="col-lg-6">
				<?php $ordiniAcquisto = OrdiniacquistoricezioniModel::g()->ordiniCollegati((int)$ricezione["id_ordine_acquisto_ricezione"]);?>
				<?php if (count($ordiniAcquisto) > 0) { ?>
				<table class="table table-striped" style="margin-bottom:0px;">
					
					<tr>
						<td><?php echo gtext("Ordini di acquisto").":";?></td>
						<td>
							<?php foreach ($ordiniAcquisto as $ordine) { ?>
							<?php echo gtext("N°");?> <a target="_blank" href="<?php echo $this->baseUrl."/$urlOrdineAcquisto/form/update/".$ordine["ordini_acquisto"]["id_ordine_acquisto"];?>"><b><?php echo $ordine["ordini_acquisto"]["numero_ordine"];?></b></a> <?php echo gtext("del");?> <b><?php echo smartDate($ordine["ordini_acquisto"]["data_ordine"], v("default_date_format"));?></b>
							<br />
							<?php } ?>
						</td>
					</tr>
					
				</table>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
