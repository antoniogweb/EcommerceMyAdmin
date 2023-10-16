<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") {
	$spedizione = SpedizioninegozioModel::g()->select("*")->left(array("spedizioniere"))->whereId((int)$id)->first();
	$ordini = SpedizioninegozioModel::g()->getOrdini((int)$id, false);
	$stile = SpedizioninegozioModel::g()->getStile($spedizione["spedizioni_negozio"]["stato"]);
	$titoloStato = SpedizioninegozioModel::g()->getTitoloStato($spedizione["spedizioni_negozio"]["stato"]);
	$pesoTotale = SpedizioninegozioModel::g()->peso(array((int)$id));
	$numeroColli = SpedizioninegozioModel::g()->getColli(array((int)$id), true);
	$checkColli = SpedizioninegozioModel::g()->checkColli([(int)$id]);
	$modulo = SpedizioninegozioModel::getModulo((int)$id);
?>

<div class="box box-widget">
	<div class="box-body">
		<div class="row">
			<div class="col-lg-6">
				<table style="margin-bottom:5px !important;" class="table table-striped">
					<tr>
						<td><?php echo gtext("Stato");?>:</td>
						<td><span style="<?php echo $stile;?>" class="label label-default"><?php echo $titoloStato;?></span></td>
					</tr>
					<tr>
						<td><?php echo gtext("Spedizioniere");?>:</td>
						<td><b><?php echo $spedizione["spedizionieri"]["titolo"];?></b></td>
					</tr>
					<tr>
						<td><?php echo gtext("Data spedizione");?>:</td>
						<td><b><?php echo smartDate($spedizione["spedizioni_negozio"]["data_spedizione"],"d/m/Y");?></b></td>
					</tr>
					<?php if ($spedizione["spedizioni_negozio"]["data_consegna"]) { ?>
					<tr>
						<td><?php echo gtext("Data consegna");?>:</td>
						<td><b><?php echo smartDate($spedizione["spedizioni_negozio"]["data_consegna"],"d/m/Y H:i");?></b></td>
					</tr>
					<?php } ?>
					<tr>
						<td><?php echo gtext("ID Spedizione");?>:</td>
						<td><b><?php echo $spedizione["spedizioni_negozio"]["id_spedizione_negozio"];?></b></td>
					</tr>
				</table>
				
				<?php $statoSpedizione = SpedizioninegozioModel::getStato($id);?>
				<?php if ($statoSpedizione != "A" && $modulo->metodo("segnacollo") && !$spedizione["spedizioni_negozio"]["id_spedizioniere_lettera_vettura"]) { ?>
				<a style="margin-left:5px;" target="_blank" href="<?php echo $this->baseUrl."/spedizioninegozio/segnacollo/".(int)$id;?>" class="pull-right btn btn-primary"><i class="fa fa-file-pdf-o"></i> <?php echo gtext("PDF");?></a>
				<?php } ?>
				
				<?php if ($statoSpedizione == "A" && $modulo->metodo("prenotaSpedizione")) { ?>
				<a title="<?php echo gtext("Setta la spedizione a PRONTA PER L'INVIO con")?> <?php echo SpedizionieriModel::g(false)->titolo($spedizione["spedizioni_negozio"]["id_spedizioniere"]);?>" href="<?php echo $this->baseUrl."/spedizioninegozio/prontadainviare/".(int)$id."?partial=".$this->viewArgs["partial"];?>" class="pull-right btn btn-info make_spinner"><i class="fa fa-paper-plane"></i> <?php echo gtext("PRENOTA");?></a>
				<?php } else if ($statoSpedizione == "I") { ?>
					<?php if (SpedizioninegozioModel::g(false)->idLetteraDiVettura((int)$id)) { ?>
					<a style="margin-left:5px;" title="<?php echo gtext("Riporta la spedizione allo stato APERTO")?>" href="<?php echo $this->baseUrl."/spedizioninegozio/conferma/".(int)$id."?partial=".$this->viewArgs["partial"];?>" class="pull-right btn bg-purple make_spinner"><i class="fa fa-check"></i> <?php echo gtext("CONFERMA");?></a>
					<?php } ?>
					
					<a title="<?php echo gtext("Riporta la spedizione allo stato APERTO")?>" href="<?php echo $this->baseUrl."/spedizioninegozio/apri/".(int)$id."?partial=".$this->viewArgs["partial"];?>" confirm-message="<?php echo gtext("Attenzione, quando andrai a prenotare nuovamente la spedizione dovrai ristampare le etichette.")?>" class="confirm pull-right btn btn-default make_spinner_confirm"><i class="fa fa-unlock"></i> <?php echo gtext("APRI");?></a>
				<?php } ?>
			</div>
			<div class="col-lg-6">
				<table class="table table-striped">
					<?php if ($spedizione["spedizioni_negozio"]["numero_spedizione"]) { ?>
					<tr>
						<td><?php echo $modulo->getLabelNumeroSpedizione();?>:</td>
						<td><b class="label label-success"><?php echo $spedizione["spedizioni_negozio"]["numero_spedizione"];?></b></td>
					</tr>
					<?php } ?>
					<?php if ($statoSpedizione != "A" && $spedizione["spedizioni_negozio"]["id_spedizioniere_lettera_vettura"]) { ?>
					<tr>
						<td><?php echo gtext("Lettera di vettura");?>:</td>
						<td><a target="_blank" class="badge bg-purple" href="<?php echo $this->baseUrl."/spedizioninegozio/letteradivettura/".(int)$id;?>"><?php echo gtext("Scarica");?> <i class="fa fa-download"></i></a></td>
					</tr>
					<?php } ?>
					<?php if (!SpedizioninegozioModel::aperto((int)$id) && $modulo->metodo("getUrlTracking")) { ?>
					<tr>
						<td><?php echo gtext("URL tracking");?>:</td>
						<td><a target="_blank" href="<?php echo $modulo->getUrlTracking((int)$id);?>"><?php echo gtext("Pagina del tracking")?> <i class="fa fa-arrow-right"></i></a></td>
					</tr>
					<?php } ?>
					<?php if (!SpedizioninegozioModel::aperto((int)$id) && $spedizione["spedizioni_negozio"]["label_spedizioniere"]) { ?>
					<tr>
						<td><?php echo gtext("Label spedizioniere");?>:</td>
						<td><i><?php echo $spedizione["spedizioni_negozio"]["label_spedizioniere"];?></i></td>
					</tr>
					<?php } ?>
					<?php if (count($ordini) > 0) { ?>
					<tr>
						<td><?php echo gtext("Ordini");?>:</td>
						<td>
							<?php foreach ($ordini as $ordine) { ?>
							<a class="label label-<?php echo OrdiniModel::getLabelStato($ordine["orders"]["stato"]);?>" target="_blank" href="<?php echo $this->baseUrl."/ordini/vedi/".(int)$ordine["orders"]["id_o"];?>">#<?php echo (int)$ordine["orders"]["id_o"];?></a>
							<?php } ?>
						</td>
					</tr>
					<?php } ?>
					<?php if ($spedizione["spedizioni_negozio"]["id_lista_regalo"]) { ?>
					<tr>
						<td><?php echo gtext("Lista regalo");?>:</td>
						<td>
							<?php echo ListeregaloModel::specchietto($spedizione["spedizioni_negozio"]["id_lista_regalo"]);?>
						</td>
					</tr>
					<?php } ?>
					<tr>
						<td><?php echo gtext("Colli");?>:</td>
						<td>
							<?php echo gtext("Peso totale");?>: <b><?php echo setPriceReverse($pesoTotale);?> kg</b><br />
							<?php echo gtext("Numero colli");?>: <b><?php echo $numeroColli;?></b><br />
							<?php if (!$checkColli) { ?>
								<div class="text text-danger text-bold">
									<i class="fa fa-exclamation-triangle"></i> <?php echo gtext("Attenzione, inserire almeno un collo di peso maggiore di 0 kg.")."<br />".gtext("Controllare inoltre che nessun collo abbia peso 0kg.")?>
								</div>
							<?php } ?>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<?php if ($spedizione["spedizioni_negozio"]["errore_invio"]) { ?>
<div class="alert alert-danger">
	<i class="fa fa-exclamation-triangle"></i> <i><?php echo gtext("Errori invio");?>: <?php echo sanitizeHtml($spedizione["spedizioni_negozio"]["errore_invio"]);?></i>
</div>
<?php } ?>

<?php if ($spedizione["spedizioni_negozio"]["warning_invio"]) { ?>
<div class="alert alert-warning">
	<i class="fa fa-exclamation-triangle"></i> <i><?php echo gtext("Alert corriere");?>: <?php echo sanitizeHtml($spedizione["spedizioni_negozio"]["warning_invio"]);?></i>
</div>
<?php } ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
	<li <?php echo $posizioni['colli'];?>><a <?php if (!$checkColli) { ?>style="background-color:red !important;color:#FFF !important;"<?php } ?>href="<?php echo $this->baseUrl."/".$this->controller."/colli/$id".$this->viewStatus;?>"><?php echo gtext("Colli");?></a></li>
	<?php if (count(SpedizioninegozioModel::getElencoServizi((int)$id)) > 0) { ?>
	<li <?php echo $posizioni['servizi'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/servizi/$id".$this->viewStatus;?>"><?php echo gtext("Servizi aggiuntivi");?></a></li>
	<?php } ?>
	<?php if (SpedizioninegozioModel::legataAdOrdineOLista((int)$id)) { ?>
	<li <?php echo $posizioni['righe'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/righe/$id".$this->viewStatus;?>"><?php echo gtext("Righe ordine");?></a></li>
	<?php } ?>
	<li <?php echo $posizioni['eventi'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/eventi/$id".$this->viewStatus;?>"><?php echo gtext("Cronologia eventi");?></a></li>
	<li <?php echo $posizioni['info'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/info/$id".$this->viewStatus;?>"><?php echo gtext("Cronologia chiamate API");?></a></li>
</ul>

<div style="clear:left;"></div>
<?php } ?>
