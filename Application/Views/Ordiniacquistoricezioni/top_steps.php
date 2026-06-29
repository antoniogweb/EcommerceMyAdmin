<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="panel panel-info">
	<div class="panel-heading">
		<div class="pull-right pulsanti_genera_invia_pdf">
			
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
				<table class="table table-striped" style="margin-bottom:0px;">
					
				</table>
			</div>
		</div>
	</div>
</div>
