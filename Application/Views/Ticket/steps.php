<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") {
	$recordTicket = TicketModel::g()->select("*")->inner(array("tipologia","cliente"))->whereId((int)$id)->first();
	$stile = TicketModel::g()->getStile($recordTicket["ticket"]["stato"]);
	$titoloStato = TicketModel::g()->getTitoloStato($recordTicket["ticket"]["stato"]);
	$nominativoCliente = TicketModel::getNominativo($recordTicket["regusers"]);
?>

<div class="box box-widget">
	<div class="box-body">
		<div class="row">
			<div class="col-lg-6">
				<table style="margin-bottom:5px !important;" class="table table-striped">
					<tr>
						<td><?php echo gtext("Stato");?>:</td>
						<td>
							<span style="<?php echo $stile;?>" class="label label-default"><?php echo $titoloStato;?></span>
						</td>
					</tr>
					<tr>
						<td><?php echo gtext("Data invio");?>:</td>
						<td>
							<b><?php echo date("d-m-Y H:I",strtotime($recordTicket["ticket"]["data_invio"]));?></b>
						</td>
					</tr>
					<tr>
						<td><?php echo gtext("Cliente");?>:</td>
						<td>
							<a class="iframe label label-info pull-right" href="<?php echo $this->baseUrl."/regusers/form/update/".$recordTicket["regusers"]["id_user"];?>?partial=Y&nobuttons=Y"><i class="fa fa-user"></i> <?php echo gtext("dettagli cliente");?></a>
							<b><?php echo $nominativoCliente;?></b> (<?php echo $recordTicket["regusers"]["username"];?>)
						</td>
					</tr>
					<?php if ($recordTicket["ticket"]["id_o"]) {
						$ordine = OrdiniModel::g()->whereId((int)$recordTicket["ticket"]["id_o"])->first();
					?>
					<tr>
						<td><?php echo gtext("Ordine");?>:</td>
						<td>
							<a class="label label-<?php echo OrdiniModel::getLabelStato($ordine["orders"]["stato"]);?>" target="_blank" href="<?php echo $this->baseUrl."/ordini/vedi/".(int)$ordine["orders"]["id_o"];?>">#<?php echo (int)$ordine["orders"]["id_o"];?></a>
						</td>
					</tr>
					<?php } ?>
					<?php if ($recordTicket["ticket"]["id_lista_regalo"]) {
						$lista = ListeregaloModel::g()->whereId((int)$recordTicket["ticket"]["id_lista_regalo"])->record();
					?>
					<tr>
						<td><?php echo gtext("Lista regalo");?>:</td>
						<td>
							<a class="badge" target="_blank" href="<?php echo $this->baseUrl."/listeregalo/form/update/".(int)$lista["id_lista_regalo"];?>"><?php echo $lista["titolo"];?> <i class="fa fa-arrow-right"></i></a>
						</td>
					</tr>
					<?php } ?>
				</table>
			</div>
			<div class="col-lg-6">
				<div class="panel panel-default no-margin">
					<div class="panel-body no-padding">
						<?php $stati = TicketstatiModel::g()->selectTendina(false);?>
						<?php if (count($stati) > 0) { ?>
						<table class="table no-margin">
							<tr>
								<th><?php echo gtext("Modifica lo stato del ticket")?></th>
								<th>
									<a class="pull-right" data-toggle="collapse" href="#collapseStati" role="button" aria-expanded="false" aria-controls="collapseStati">
										<?php echo gtext("Mostra stati");?>
									</a>
								</th>
							</tr>
							<tbody class="no-border collapse" id="collapseStati">
							<?php foreach ($stati as $codiceStato => $titoloStato) {
								
								if ($codiceStato == $recordTicket["ticket"]["stato"])
									continue;
								
								$stile = TicketModel::g()->getStile($codiceStato);
								$titoloStato = TicketModel::g()->getTitoloStato($codiceStato);
							?>
								<tr>
									<td><span style="<?php echo $stile;?>" class="label label-default"><?php echo $titoloStato;?></span></td>
									<td class="text-right">
										<a title="<?php echo gtext("Imposta")?>" class="make_spinner help_cambia_stato btn btn-default btn-xs" href="<?php echo $this->baseUrl."/ticket/setstato/".$recordTicket["ticket"]["id_ticket"]."/".$codiceStato.$this->viewStatus."&no_mail_stato";?>">
											<i class="fa fa-thumbs-up"></i>
										</a>
										
										<a style="margin-left:5px;" title="<?php echo gtext("Imposta e manda mail")?>" class="make_spinner help_cambia_stato_mail btn btn-info btn-xs" href="<?php echo $this->baseUrl."/ticket/setstato/".$recordTicket["ticket"]["id_ticket"]."/".$codiceStato.$this->viewStatus;?>">
											<i class="fa fa-envelope-o"></i>
										</a>
									</td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
</ul>

<div style="clear:left;"></div>
<?php } ?>
