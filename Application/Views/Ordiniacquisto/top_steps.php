<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="panel panel-info">
	<div class="panel-heading">
		<div class="pull-right pulsanti_genera_invia_pdf">
			<a class="pull-right btn btn-xs btn-warning btn-rounded make_spinner" href="<?php echo $this->baseUrl."/impegni/ordiniimpegni/inviapdf/".$ordine["id_ordine_acquisto"];?>"><i class="fa fa-envelope"></i> <?php echo gtext("Invia PDF");?></a>
			
			<a target="_blank" class="pull-right btn btn-xs btn-success btn-rounded" href="<?php echo $this->baseUrl."/impegni/ordiniimpegni/stampapdf/".$ordine["id_ordine_acquisto"];?>"><i class="fa fa-file-pdf-o"></i> <?php echo gtext("Stampa PDF");?></a>
		</div>
		<?php echo gtext("N° Ordine");?> #<b><?php echo $ordine["numero_ordine"];?></b></b>
		</div>
	<div class="text-left panel-body">
		<div class="row">
			<div class="col-lg-6">
				<table class="table table-striped" style="margin-bottom:0px;">
					
				</table>
			</div>
			<div class="col-lg-6">
				<table class="table table-striped" style="margin-bottom:0px;">
					<tr>
						<td><?php echo gtext("Imponibile");?>:</td>
						<td><b>&euro; <?php echo setPriceReverse($ordine["imponibile"]);?></b></td>
					</tr>
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
