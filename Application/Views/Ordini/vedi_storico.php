<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<section class="content-header">
	<h1><?php echo gtext("Storico delle modifiche agli stati dell'ordine");?></h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">	
			<div class="box">
				<div class="box-header with-border main help_storico">
					<table class="table">
						<tr>
							<th colspan="3">
								<?php echo gtext("Data ora");?>
								<?php echo gtext("Stato");?>
								<?php echo gtext("Utente admin");?>
							</th>
						</tr>
						<tbody>
							<?php foreach ($stati as $s) { ?>
							<tr>
								<td><?php echo date("d-m-Y H:i", strtotime($s["orders_stati"]["data_creazione"]));?></td>
								<td><span class="label label-<?php echo labelStatoOrdine($s["stati_ordine"]["codice"]);?>"><?php echo $s["stati_ordine"]["titolo"];?></span></td>
								<td><?php echo $s["adminusers"]["username"] ?? "--";?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
