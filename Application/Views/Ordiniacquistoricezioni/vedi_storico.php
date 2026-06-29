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
							<th>
								<?php echo gtext("Data ora");?>
							</th>
							<th>
								<?php echo gtext("Stato");?>
							</th>
							<th>
								<?php echo gtext("Utente admin");?>
							</th>
						</tr>
						<tbody>
							<?php foreach ($stati as $s) { ?>
							<tr>
								<td><?php echo date("d-m-Y H:i", strtotime($s["ordini_acquisto_stati_storico"]["data_creazione"]));?></td>
								<td><span class="label label-<?php echo OrdiniacquistostatiModel::getCampo($s["ordini_acquisto_stati_storico"]["id_ordine_acquisto_stato"], "classe");?>"><?php echo $s["ordini_acquisto_stati"]["titolo"];?></span></td>
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
