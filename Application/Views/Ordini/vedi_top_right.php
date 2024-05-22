<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("fatture_attive") && isset($fatture) && $fattureOk) { ?>
	<div style="margin-bottom:10px;" class="panel panel-default">
		<div class="panel-heading">
			<?php if (count($fatture) > 0) { ?>
			<a style="margin-left:10px;" title="Invia mail con fattura in allegato" class="btn btn-primary btn-xs pull-right make_spinner" href="<?php echo $this->baseUrl."/ordini/vedi/" . $ordine["id_o"].$this->viewStatus."&invia_fattura=Y";?>"><i class="fa fa-envelope"></i> Invia</a>
			
			<a style="margin-left:10px;" class="btn btn-success btn-xs pull-right" href="<?php echo $this->baseUrl."/fatture/vedi/" . $ordine["id_o"];?>"><i class="fa fa-download"></i> Scarica</a>
			<a style="margin-left:10px;" class="btn btn-default btn-xs make_spinner pull-right" href="<?php echo $this->baseUrl."/fatture/crea/" . $ordine["id_o"];?>"><i class="fa fa-refresh"></i> Rigenera</a>
			
			<?php } else { ?>
			<a style="margin-left:10px;" class="btn btn-default btn-xs make_spinner pull-right" href="<?php echo $this->baseUrl."/fatture/crea/" . $ordine["id_o"];?>"><i class="fa fa-refresh"></i> Genera</a>
			<?php } ?>
			
			<b><?php echo gtext("Gestione fattura");?></b>
		</div>
		<?php if (count($fatture) > 0) {
			$fattura = $fatture[0]["fatture"];
		?>
		<div class="panel-body">
			<?php echo gtext("Fattura numero");?>: <b><?php echo $fattura["numero"];?></b> <?php echo gtext("del");?> <b><?php echo smartDate($fattura["data_fattura"]);?></b> <?php if (FattureModel::g()->manageable($fattura["id_f"])) { ?><a class="label label-info iframe" href="<?php echo $this->baseUrl."/fatture/form/update/".$fattura["id_f"]."?partial=Y&nobuttons=Y";?>"><i class="fa fa-pencil"></i></a><?php } ?>
			<?php if (GestionaliModel::getModulo()->integrazioneAttiva()) { ?>
			<div>
				<?php echo GestionaliModel::getModulo()->specchiettoOrdine($ordine);?>
			</div>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
<?php } ?>

<div class="panel panel-default no-margin">
	<div class="panel-body no-padding">
		<?php 
		if (!isset($statiSuccessivi))
			$statiSuccessivi = OrdiniModel::statiSuccessivi($ordine["stato"]);
		?>
		<?php if (count($statiSuccessivi) > 0) { ?>
		<table class="table no-margin">
			<tr>
				<th><?php echo gtext("Modifica lo stato dell'ordine")?></th>
				<th>
					<a class="pull-right" data-toggle="collapse" href="#collapseStati" role="button" aria-expanded="false" aria-controls="collapseStati">
						<?php echo gtext("Mostra stati");?>
					</a>
				</th>
			</tr>
			<tbody class="no-border collapse" id="collapseStati">
			<?php foreach ($statiSuccessivi as $statoSucc) { ?>
				<tr>
					<td><span class="label label-<?php echo labelStatoOrdine($statoSucc["codice"]);?>"><?php echo statoOrdine($statoSucc["codice"]);?></span></td>
					<td class="text-right">
						<a title="<?php echo gtext("Imposta")?>" class="make_spinner help_cambia_stato btn btn-default btn-xs" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/setstato/".$ordine["id_o"]."/".$statoSucc["codice"].$this->viewStatus."&no_mail_stato";?>">
							<i class="fa fa-thumbs-up"></i>
						</a>
						
						<?php if ($statoSucc["manda_mail_al_cambio_stato"] && ($statoSucc["codice"] == "pending" || !F::blank($statoSucc["descrizione"]) || file_exists(tpf("/Ordini/mail-".$statoSucc["codice"].".php")))) { ?>
						<a style="margin-left:5px;" title="<?php echo gtext("Imposta e manda mail")?>" class="make_spinner help_cambia_stato_mail btn btn-info btn-xs" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/setstato/".$ordine["id_o"]."/".$statoSucc["codice"].$this->viewStatus;?>">
							<i class="fa fa-envelope-o"></i>
						</a>
						<?php } ?>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		<?php } ?>
	</div>
</div>

<?php if (count($mail_altre) > 0) { ?>
<div style="margin-top:10px;margin-bottom:0px;" class="panel panel-default">
	<div class="panel-body no-padding">
		<table class="table">
			<tr>
				<th colspan="3">
					<a class="pull-right" data-toggle="collapse" href="#collapseMail" role="button" aria-expanded="false" aria-controls="collapseMail">
						<?php echo gtext("Mostra e-mail");?>
					</a>
					<?php echo gtext("Storico invii mail al cliente");?>
				</th>
			</tr>
			<tbody class="collapse" id="collapseMail">
				<tr>
					<th><?php echo gtext("Data invio");?></th>
					<th><?php echo gtext("Tipo / Oggetto mail");?></th>
					<th style="width:1%;"></th>
				</tr>
				<?php foreach ($mail_altre as $mailFatt) { ?>
				<tr>
					<td><?php echo date("d-m-Y H:i", strtotime($mailFatt["data_creazione"]));?></td>
					<td><?php echo OrdiniModel::getTipoMail($mailFatt["tipo"]);?><br /><i><b><?php echo $mailFatt["oggetto"];?></b></i></td>
					<td><i style="font-size:18px;" class="text text-<?php if ($mailFatt["inviata"]) { ?>success<?php } else { ?>danger<?php } ?> fa <?php if ($mailFatt["inviata"]) { ?>fa-check-circle<?php } else { ?>fa-ban<?php } ?>"></i></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<?php } ?>
