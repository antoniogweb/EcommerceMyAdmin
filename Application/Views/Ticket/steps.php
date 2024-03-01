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
							<a class="iframe label label-info pull-right" href="<?php echo $this->baseUrl."/regusers/form/update/".$recordTicket["regusers"]["id_user"];?>?partial=Y&nobuttons=Y"><?php echo gtext("dettagli cliente");?></a>
							<b><?php echo $nominativoCliente;?></b> (<?php echo $recordTicket["regusers"]["username"];?>)
						</td>
					</tr>
				</table>
			</div>
			<div class="col-lg-6">
				<table class="table table-striped">
					
				</table>
			</div>
		</div>
	</div>
</div>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
</ul>

<div style="clear:left;"></div>
<?php } ?>
