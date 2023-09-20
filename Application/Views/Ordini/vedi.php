<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$mostraIvato = $this->viewArgs["prezzi"] == "I" ? true : false;
$labelIvaInclusaEsclusa = $this->viewArgs["prezzi"] == "I" ? "inclusa" : "esclusa";
?>

<section class="content-header">
	<h1><?php echo gtext("Gestione ordini");?></h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<?php if (!nobuttons()) { ?>
			<div class="mainMenu">
				<?php if (count($integrazioni) > 0) { ?>
				<div class="text-right pull-right">
					<?php
					foreach ($integrazioni as $pulsanteI)
					{
						echo $pulsanteI;
					}
					?>
				</div>
				<?php } ?>
				<?php echo $menu;?>
			</div>
			
			<?php include($this->viewPath("steps"));?>
			<?php } ?>
			<?php echo flash("notice");?>
			<?php echo $notice_send;?>
			<?php echo flash("notice_send");?>
			
			<div class="box">
				<div class="box-header with-border main help_resoconto">
					<div class="row">
						<div class="col-lg-6">
							<?php
							$linguaNazioneUrl = v("attiva_nazione_nell_url") ? $ordine["lingua"]."_".strtolower($ordine["nazione"]) : $ordine["lingua"];
							?>
							<table class="table table-striped">
								<tr>
									<td><?php echo gtext("N° Ordine");?>:</td>
									<td><b>#<?php echo $ordine["id_o"];?></b> <a <?php if (partial()) { ?>target="_blank"<?php } ?> class="<?php if (!partial()) { ?>iframe<?php } ?> pull-right help_ordine_lato_cliente" href="<?php echo Domain::$name."/".$linguaNazioneUrl."/resoconto-acquisto/".$ordine["id_o"]."/".$ordine["cart_uid"]?>"><i class="fa fa-eye"></i> <?php echo gtext("Vedi ordine lato cliente");?></a></td>
								</tr>
								<tr>
									<td><?php echo gtext("Data");?>:</td>
									<td><b><?php echo smartDate($ordine["data_creazione"]);?></b></td>
								</tr>
								<tr>
									<td><?php echo gtext("Totale");?>:</td>
									<td><b>&euro; <?php echo setPriceReverse($ordine["total"]);?></b></td>
								</tr>
								<?php if (strcmp($tipoOutput,"web") === 0 or strcmp($ordine["pagamento"],"bonifico") === 0 or strcmp($ordine["pagamento"],"contrassegno") === 0) { ?>
								<tr>
									<td><?php echo gtext("Stato ordine");?>:</td>
									<td><b><span class="label label-<?php echo labelStatoOrdine($ordine["stato"]);?>"><?php echo statoOrdine($ordine["stato"]);?></span></b></td>
								</tr>
								<?php } ?>
								<tr>
									<td><?php echo gtext("Stato pagamento");?>:</td>
									<td>
										<?php if ($ordine["pagato"] || StatiordineModel::g(false)->pagato($ordine["stato"])) { ?>
										<span class="label label-success"><?php echo gtext("Ordine pagato");?></span>
											<?php if ($ordine["data_pagamento"]) { ?>
											<?php echo gtext("in data");?> <b><?php echo date("d-m-Y H:i", strtotime($ordine["data_pagamento"]));?></b>
											<?php } ?>
										<?php } else { ?>
										<span class="label label-warning"><?php echo gtext("Ordine NON pagato");?></span>
										<?php } ?>
									</td>
								</tr>
								<tr>
									<td><?php echo gtext("Metodo di pagamento");?>:</td>
									<td><b><?php echo metodoPagamento($ordine["pagamento"]);?></b></td>
								</tr>
								<?php if (v("permetti_ordini_offline")) { ?>
								<tr>
									<td><?php echo gtext("Tipo ordine");?>:</td>
									<td><b><?php echo OrdiniModel::getLabelTipoOrdine($ordine["tipo_ordine"]);?></b></td>
								</tr>
								<?php } ?>
								<?php if (!$ordine["da_spedire"] && !empty($corriere)) { ?>
								<tr>
									<td><?php echo gtext("Tipo di consegna");?>:</td>
									<td><b><?php echo $corriere["titolo"];?></b></td>
								</tr>
								<?php } ?>
								<?php if (v("attiva_ip_location")) { ?>
								<tr>
									<td><?php echo gtext("Nazione navigazione");?>:</td>
									<td><b><?php echo findTitoloDaCodice($ordine["nazione_navigazione"]);?></b></td>
								</tr>
								<?php } ?>
								<?php if (OpzioniModel::isAttiva("CAMPI_FORM_CHECKOUT", "fattura") && $ordine["tipo_cliente"] == "privato" && $ordine["fattura"]) { ?>
								<tr>
									<td><?php echo gtext("Fattura");?>:</td>
									<td><b class="text text-primary"><?php echo gtext("Richiesta");?></b></td>
								</tr>
								<?php } ?>
								<?php if ($ordine["codice_promozione"]) { ?>
								<tr>
									<td><?php echo gtext("Coupon");?>:</td>
									<td>
										<b><?php echo $ordine["codice_promozione"];?></b> (<i><?php echo $ordine["nome_promozione"];?></i>)
									</td>
								</tr>
								<?php } ?>
								<?php if (v("attiva_agenti") && $ordine["id_agente"]) { ?>
								<tr>
									<td><?php echo gtext("Agente");?>:</td>
									<td>
										<b class="text text-primary"><?php echo OrdiniModel::g()->agenteCrud(array("orders"=>$ordine));?></b>
									</td>
								</tr>
								<?php } ?>
								<?php if ($ordine["id_lista_regalo"]) { ?>
								<tr>
									<td><?php echo gtext("Lista regalo");?>:</td>
									<td>
										<?php echo ListeregaloModel::specchietto($ordine["id_lista_regalo"]);?>
									</td>
								</tr>
								<?php } ?>
								<?php if (v("attiva_gestione_pixel")) {
									$eventiPixel = PixeleventiModel::getStatusPixelEventoElemento("PURCHASE", $ordine["id_o"], "orders");
									
									if (count($eventiPixel) > 0)
									{
								?>
									<tr>
										<td><?php echo gtext("Pixel");?>:</td>
										<td>
											<?php foreach ($eventiPixel as $ev) { ?>
												<b><?php echo $ev["pixel"]["titolo"];?></b>
												<?php echo gtext("inviato in data/ora").": ".date("d-m-Y H:i", strtotime($ev["pixel_eventi"]["data_creazione"]));?> <i class="text text-success fa fa-thumbs-up"></i>
												<a class="iframe" title="<?php echo gtext("Vedi codice script");?>" href="<?php echo $this->baseUrl."/ordini/vediscriptpixel/".$ev["pixel_eventi"]["id_pixel_evento"];?>&partial=Y&nobuttons=Y"><i class="fa fa-eye"></i></a>
												<br />
											<?php } ?>
										</td>
									</tr>
									<?php } ?>
								<?php } ?>
								<?php if ($ordine["da_spedire"] && v("attiva_gestione_spedizioni") && OrdiniModel::daSpedire($ordine["id_o"])) { ?>
								<tr>
									<td><?php echo gtext("Spedizione");?>:</td>
									<td>
										<?php
										$righeDaSpedire = OrdiniModel::righeDaSpedire($ordine["id_o"]);
										
										echo SpedizioninegozioModel::g(false)->badgeSpedizione($ordine["id_o"]);?>
										
										<?php if (count($righeDaSpedire) > 0 && ControllersModel::checkAccessoAlController(array("spedizioninegozio"))) { ?>
										<div>
											<a class="iframe badge" href="<?php echo $this->baseUrl."/spedizioninegozio/form/insert/0?id_o=".$ordine["id_o"];?>&partial=Y"><i class="fa fa-plus-square-o"></i> <?php echo gtext("Crea spedizione");?></a>
										</div>
										<?php } ?>
									</td>
								</tr>
								<?php } ?>
							</table>
						</div>
						<div class="col-lg-6">
							<?php if (v("fatture_attive") && $fattureOk) { ?>
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
									<?php $statiSuccessivi = OrdiniModel::statiSuccessivi($ordine["stato"]);?>
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
													<a title="<?php echo gtext("Imposta")?>" class="make_spinner help_cambia_stato btn btn-default btn-xs" href="<?php echo $this->baseUrl."/ordini/setstato/".$ordine["id_o"]."/".$statoSucc["codice"].$this->viewStatus."&no_mail_stato";?>">
														<i class="fa fa-thumbs-up"></i>
													</a>
													
													<?php if ($statoSucc["manda_mail_al_cambio_stato"] && ($statoSucc["codice"] == "pending" || !F::blank($statoSucc["descrizione"]) || file_exists(tpf("/Ordini/mail-".$statoSucc["codice"].".php")))) { ?>
													<a style="margin-left:5px;" title="<?php echo gtext("Imposta e manda mail")?>" class="make_spinner help_cambia_stato_mail btn btn-info btn-xs" href="<?php echo $this->baseUrl."/ordini/setstato/".$ordine["id_o"]."/".$statoSucc["codice"].$this->viewStatus;?>">
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
						</div>
					</div>
				</div>
			</div>
			
			<div class="box">
				<div class="box-header with-border main help_righe_ordine">
					<?php if (v("prezzi_ivati_in_prodotti")) { ?>
					<div style="margin-bottom:20px;" class="btn-group pull-right">
						<?php
						$tempViewArgs = $this->viewArgs;
						$tempViewArgs["prezzi"] = "I";
						?>
						<a href="<?php echo $this->baseUrl."/ordini/vedi/".$ordine["id_o"].Url::createUrl($tempViewArgs);?>" type="button" class="btn btn-<?php echo $mostraIvato ? "primary" : "default"; ?> btn-xs"><?php echo gtext("Prezzi IVA inclusa")?></a>
						<?php
						$tempViewArgs = $this->viewArgs;
						$tempViewArgs["prezzi"] = "NI";
						?>
						<a href="<?php echo $this->baseUrl."/ordini/vedi/".$ordine["id_o"].Url::createUrl($tempViewArgs);?>" type="button" style="margin-left:8px;" class="btn btn-<?php echo !$mostraIvato ? "primary" : "default"; ?> btn-xs"><?php echo gtext("Prezzi IVA esclusa")?></a>
					</div>
					<?php } ?>
					
					<h4 style="margin-top:0px;" class="text-bold"><?php echo gtext("Righe ordine");?>:</h4>
					
					<div class="scroll-x" style="clear:both;">
						<table width="100%" class="table table-striped" cellspacing="0">
							<thead>
								<tr class="">
									<th class="text-left"><?php echo gtext("Immagine");?></th>
									<th colspan="2" align="left" class=""><?php echo gtext("Prodotto");?></th>
									<?php if ($ordine["da_spedire"] && v("attiva_gestione_spedizioni")) { ?>
									<th class="text-left"><?php echo gtext("Spedizione");?></th>
									<?php } ?>
									<th class="text-right"><?php echo gtext("Codice");?></th>
									<th class="text-right"><?php echo gtext("Peso");?></th>
									<th class="text-right"><?php echo gtext("Quantità");?></th>
									<th class="text-right colonne_non_ivate"><?php echo gtext("Prezzo");?><br /><?php echo gtext("IVA $labelIvaInclusaEsclusa");?></th>
									<?php if (strcmp($ordine["usata_promozione"],"Y") === 0 && $ordine["tipo_promozione"] == "PERCENTUALE") { ?>
									<th class="text-right colonne_non_ivate"><?php echo gtext("Sconto");?><br />(<i><?php echo $ordine["nome_promozione"];?></i>)</th>
									<th class="text-right colonne_non_ivate"><?php echo gtext("Prezzo scontato");?><br /><?php echo gtext("IVA $labelIvaInclusaEsclusa");?></th>
									<?php } ?>
									<th class="text-right colonne_non_ivate"><?php echo gtext("Aliquota");?></th>
									<?php if (false) { ?>
										<?php if (v("prezzi_ivati_in_carrello")) { ?>
											<th class="text-right"><?php echo gtext("Prezzo");?><br /><?php echo gtext("IVA inclusa");?></th>
											<?php if (strcmp($ordine["usata_promozione"],"Y") === 0 && $ordine["tipo_promozione"] == "PERCENTUALE") { ?>
											<th class="text-right"><?php echo gtext("Sconto");?><br />(<i><?php echo $ordine["nome_promozione"];?></i>)</th>
											<th class="text-right"><?php echo gtext("Prezzo scontato");?><br /><?php echo gtext("IVA inclusa");?></th>
											<?php } ?>
										<?php } ?>
										<th class="text-right"><?php echo gtext("Totale IVA");?> <?php echo $labelIvaInclusaEsclusa; ?></th>
									<?php } ?>
									<th class="text-right"><?php echo gtext("Totale IVA $labelIvaInclusaEsclusa");?></th>
								</tr>
							</thead>
							
							<?php
							$pesoTotale = 0;
							foreach ($righeOrdine as $p) {
								$pesoTotale += $p["righe"]["peso"] * $p["righe"]["quantity"];
							?>
							<tr class="">
								<td>
								<?php if ($p["righe"]["immagine"]) { ?>
									<img src='<?php echo Url::getRoot()."thumb/immagineinlistaprodotti/0/".$p["righe"]["immagine"];?>' />
								<?php } ?>
								</td>
								<?php if ($p["righe"]["id_p"]) { ?>
								<td width="1%"><i class="fa fa-arrow-right"></i></td>
								<?php } ?>
								<td colspan="<?php if (!$p["righe"]["id_p"]) { ?>2<?php } else { ?>1<?php } ?>" class=""><?php echo $p["righe"]["title"];?>
								<?php if ($p["righe"]["gift_card"]) { ?>
									<?php $elementiRiga = RigheelementiModel::getElementiRiga($p["righe"]["id_r"]);
				
									if (count($elementiRiga) > 0) { ?>
										<table width="100%" class="table" cellspacing="0">
											<tr>
												<th style="text-align:left;font-size:13px;"><?php echo gtext("Da inviare a");?></th>
												<th style="text-align:left;font-size:13px;"><?php echo gtext("Dedica e firma");?></th>
												<th></th>
											</tr>
										<?php foreach ($elementiRiga as $el) { ?>
										<tr>
											<td style="text-align:left;font-size:13px;">
												<?php echo $el["email"] ? $el["email"] : "--";?>
											</td>
											<td style="text-align:left;font-size:13px;">
												<?php echo $el["testo"] ? nl2br($el["testo"]) : "--";?>
											</td>
											<td style="text-align:left;font-size:13px;">
												<a class="iframe" title="<?php echo gtext("Modifica email e dedica")?>" href="<?php echo $this->baseUrl."/righeelementi/form/update/".$el["id_riga_elemento"];?>?partial=Y&nobuttons=Y"><i class="fa fa-pencil"></i></a>
											</td>
										</tr>
										<?php } ?>
									</table>
									<?php } ?>
									
									<?php $promozioni = PromozioniModel::getPromoRigaOrdine($p["righe"]["id_r"]);
									
									if (count($promozioni) > 0) {
										echo "<br />------------<br /><b>".gtext("Codici delle Gift Card legate alla righa d'ordine").":</b>";
									
										foreach ($promozioni as $promo) { 
										?>
											<br /><a title="<?php echo gtext("Vedi dettagli promo");?>" class="iframe" href="<?php echo $this->baseUrl."/promozioni/form/update/".$promo["id_p"];?>?partial=Y&nobuttons=Y"><i class="fa fa-info-circle"></i></a> <?php echo gtext("Codice");?>: <span class="badge badge-info"><?php echo $promo["codice"];?></span> <?php echo gtext("Stato");?>: <?php echo PromozioniModel::g()->isActiveCoupon($promo["codice"],null,false) ? "<span class='label label-success'>".gtext("Attivo")."</span>" : "<span class='label label-warning'>".gtext("Non attivo")."</span>";?>
											<?php $inviataA = EventiretargetingelementiModel::getElemento($promo["id_p"], "promozioni"); ?>
											<?php if (!empty($inviataA)) { ?>
											<span class="uk-text-meta"><?php echo gtext("Inviato a");?>:</span> <b><?php echo $inviataA["email"];?></b>
											<?php } ?>
											
											<?php $euroUsati = PromozioniModel::gNumeroEuroUsati($promo["id_p"]);?>
											<?php if ($euroUsati > 0) { ?>
											<?php echo gtext("Usati");?>: <strong><?php echo setPriceReverse($euroUsati);?> €</strong>
											<?php } ?>
										<?php } ?>
									<?php } ?>
								<?php } ?>
								<?php if (strcmp($p["righe"]["id_c"],0) !== 0) { echo "<br />".$p["righe"]["attributi"]; } ?>
								</td>
								<?php if ($ordine["da_spedire"] && v("attiva_gestione_spedizioni")) { ?>
								<td class="text-left"><?php echo SpedizioninegozioModel::g(false)->badgeSpedizione($ordine["id_o"], $p["righe"]["id_r"], false, "")?></td>
								<?php } ?>
								<td class="text-right"><?php echo $p["righe"]["codice"];?></td>
								<td class="text-right"><?php echo setPriceReverse($p["righe"]["peso"]);?></td>
								<td class="text-right"><?php echo $p["righe"]["quantity"];?></td>
								<td class="text-right colonne_non_ivate">
									<?php
									$campoIvato = $mostraIvato ? "_ivato" : "";
									$prezzoFisso = $p["righe"]["prezzo_fisso_intero$campoIvato"];
									$prezzoFissoFinale = $p["righe"]["prezzo_fisso$campoIvato"];
									
									$strPrezzoFisso = ($prezzoFisso > 0) ? setPriceReverse($prezzoFisso)." + " : "";
									$strPrezzoFissoFinale = ($prezzoFissoFinale > 0) ? setPriceReverse($prezzoFissoFinale)." + " : "";
									?>
									<?php if (isset($p["righe"]["in_promozione"]) and strcmp($p["righe"]["in_promozione"],"Y")===0){ echo "<del>".$strPrezzoFisso.($mostraIvato ? setPriceReverse($p["righe"]["prezzo_intero_ivato"]) : setPriceReverse($p["righe"]["prezzo_intero"], v("cifre_decimali")))." €</del>"; } ?> <span class="item_price_single"><?php echo $strPrezzoFissoFinale.($mostraIvato ? setPriceReverse($p["righe"]["price_ivato"]) : setPriceReverse($p["righe"]["price"], v("cifre_decimali")));?></span> €
									
									<?php $jsonSconti = json_decode($p["righe"]["json_sconti"],true);?>
									
									<?php if (count($jsonSconti) > 0) { ?>
										<div class="well no-margin">
											<?php echo implode("<br />", $jsonSconti);?>
										</div>
									<?php } ?>
								</td>
								<?php if (strcmp($ordine["usata_promozione"],"Y") === 0 && $ordine["tipo_promozione"] == "PERCENTUALE") { ?>
								<td class="text-right colonne_non_ivate"><?php echo setPriceReverse($p["righe"]["percentuale_promozione"]);?> %</td>
								<td class="text-right colonne_non_ivate"><?php echo $mostraIvato ? setPriceReverse($p["righe"]["prezzo_finale_ivato"]) : setPriceReverse($p["righe"]["prezzo_finale"], v("cifre_decimali"));?></td>
								<?php } ?>
								<td class="text-right colonne_non_ivate"><?php echo setPriceReverse($p["righe"]["iva"]);?> %</td>
								<td class="text-right">
									<span class="item_price_subtotal"><?php echo $mostraIvato ? setPriceReverse($p["righe"]["quantity"] * $p["righe"]["prezzo_finale_ivato"]) : setPriceReverse($p["righe"]["quantity"] * $p["righe"]["prezzo_finale"],v("cifre_decimali"));?></span> €
								</td>
							</tr>
							<?php } ?>
							<?php if ($ordine["costo_pagamento"]) { ?>
							<tr>
								<td></td>
								<td colspan="2"><?php echo gtext("Spese pagamento");?> (<?php echo str_replace("_"," ",$ordine["pagamento"]);?>)</td>
								<?php if ($ordine["da_spedire"] && v("attiva_gestione_spedizioni")) { ?><td class="text-left"></td><?php } ?>
								<td class="text-right"></td>
								<td class="text-right"></td>
								<td class="text-right">
									1
								</td>
								<td class="text-right colonne_non_ivate">
									<?php echo $mostraIvato ? setPriceReverse($ordine["costo_pagamento_ivato"]) : setPriceReverse($ordine["costo_pagamento"], v("cifre_decimali"));?> €
								</td>
								<?php if (strcmp($ordine["usata_promozione"],"Y") === 0 && $ordine["tipo_promozione"] == "PERCENTUALE") { ?>
								<td class="text-right colonne_non_ivate">
									0%
								</td>
								<td class="text-right colonne_non_ivate">
									<?php echo $mostraIvato ? setPriceReverse($ordine["costo_pagamento_ivato"]) : setPriceReverse($ordine["costo_pagamento"], v("cifre_decimali"));?> €
								</td>
								<?php } ?>
								<td class="text-right colonne_non_ivate">
									<?php echo setPriceReverse($ordine["iva_spedizione"], 2);?> %
								</td>
								<td class="text-right">
									<?php echo $mostraIvato ? setPriceReverse($ordine["costo_pagamento_ivato"]) : setPriceReverse($ordine["costo_pagamento"], v("cifre_decimali"));?> €
								</td>
							</tr>
							<?php } ?>
							<?php if ($ordine["da_spedire"]) { ?>
							<tr>
								<td></td>
								<td colspan="2"><?php echo gtext("Spese di spedizione");?></td>
								<?php if ($ordine["da_spedire"] && v("attiva_gestione_spedizioni")) { ?><td class="text-left"></td><?php } ?>
								<td class="text-right"></td>
								<td class="text-right"></td>
								<td class="text-right">
									1
								</td>
								<td class="text-right colonne_non_ivate">
									<?php echo $mostraIvato ? setPriceReverse($ordine["spedizione_ivato"]) : setPriceReverse($ordine["spedizione"], v("cifre_decimali"));?> €
								</td>
								<?php if (strcmp($ordine["usata_promozione"],"Y") === 0 && $ordine["tipo_promozione"] == "PERCENTUALE") { ?>
								<td class="text-right colonne_non_ivate">
									0%
								</td>
								<td class="text-right colonne_non_ivate">
									<?php echo $mostraIvato ? setPriceReverse($ordine["spedizione_ivato"]) : setPriceReverse($ordine["spedizione"], v("cifre_decimali"));?> €
								</td>
								<?php } ?>
								<td class="text-right colonne_non_ivate">
									<?php echo setPriceReverse($ordine["iva_spedizione"], 2);?> %
								</td>
								<td class="text-right">
									<?php echo $mostraIvato ? setPriceReverse($ordine["spedizione_ivato"]) : setPriceReverse($ordine["spedizione"], v("cifre_decimali"));?> €
								</td>
							</tr>
							<?php } ?>
							<?php if ((strcmp($ordine["usata_promozione"],"Y") === 0 || $ordine["sconto"] > 0) && $ordine["tipo_promozione"] == "ASSOLUTO") { ?>
							<tr class="text text-warning">
								<td></td>
								<td colspan="2">
									<?php if ($ordine["nome_promozione"]) { ?>
									<?php echo gtext("Coupon");?>: <b><?php echo $ordine["nome_promozione"];?></b>. <?php echo gtext("Codice coupon");?>: <b><?php echo $ordine["codice_promozione"];?></b>
									<?php } ?>
									<?php if ($ordine["sconto"] > 0) { ?>
									<?php echo gtext("Sconto");?>:
									<?php } ?>
								</td>
								<?php if ($ordine["da_spedire"] && v("attiva_gestione_spedizioni")) { ?><td class="text-left"></td><?php } ?>
								<td class="text-right"></td>
								<td class="text-right"></td>
								<td class="text-right">
									1
								</td>
								<td class="text-right colonne_non_ivate">
									- <?php echo $mostraIvato ? setPriceReverse($ordine["euro_promozione"]) : setPriceReverse($ordine["euro_promozione"] / (1 + ($ordine["iva_spedizione"] / 100)), v("cifre_decimali"));?> €
								</td>
								<td class="text-right colonne_non_ivate">
									<?php echo setPriceReverse($ordine["iva_spedizione"], 2);?> %
								</td>
								<td class="text-right">
									- <?php echo $mostraIvato ? setPriceReverse($ordine["euro_promozione"]) : setPriceReverse($ordine["euro_promozione"] / (1 + ($ordine["iva_spedizione"] / 100)), v("cifre_decimali"));?> €
								</td>
							</tr>
							<?php } ?>
						</table>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-lg-6">
					<div class="box">
						<div class="box-header with-border main help_totali_ordine">
							<h4 style="margin-top:0px;" class="text-bold"><?php echo gtext("Totali ordine");?>:</h4>
							
							<?php
							list($arrayIva, $arraySubtotali) = OrdiniModel::getTotaliIva($ordine["id_o"]);
							$totaleIva = number_format(array_sum($arrayIva),2,".","");
							$imponibile = $ordine["total"] - $totaleIva;
							?>
							
							<table class="table table-striped">
								<?php if (count($arrayIva) === 1) { ?>
								<tr>
									<td><?php echo gtext("Imponibile");?></td>
									<td class="text-right"><b><?php echo setPriceReverse($imponibile);?> €</b></td>
								</tr>
								<?php } ?>
								<?php foreach ($arrayIva as $idAliquota => $totale) { ?>
								<tr>
									<td><?php echo IvaModel::getTitoloDaId($idAliquota);?></td>
									<td class="text-right"><b><?php echo setPriceReverse($totale);?> €</b></td>
								</tr>
								<?php } ?>
								<tr>
									<td><?php echo gtext("Totale ordine");?></td>
									<td class="text-right"><b><?php echo setPriceReverse($ordine["total"]);?> €</b></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<?php if ($ordine["da_spedire"]) { ?>
				<div class="col-lg-6">
					<div class="box">
						<div class="box-header with-border main help_spese_di_spedizione">
							<h4 style="margin-top:0px;" class="text-bold"><?php echo gtext("Spedizione");?></h4>
							
							<table class="table table-striped">
								<tr>
									<td>
										<?php echo gtext("Spese spedizione");?><br />
										<?php echo gtext("Peso totale");?>: <span class="badge badge-info"><b><?php echo setPriceReverse($pesoTotale);?> kg</b></span> <?php if (!empty($corriere)) { ?><br />
										<?php echo gtext("Corriere scelto");?>: <span class="badge badge-info"><?php echo $corriere["titolo"];?></span><?php } ?>
										<?php if ($ordine["id_spedizioniere"]) { ?>
										<br /><?php echo gtext("Spedizioniere");?>: <b><?php echo SpedizionieriModel::g()->titolo($ordine["id_spedizioniere"]);?></b>
										<?php } ?>
									</td>
									<td class="text-right">
										<?php if (v("prezzi_ivati_in_carrello")) { ?>
										<?php echo setPriceReverse($ordine["spedizione_ivato"]);?> €
										<?php } else { ?>
										<?php echo setPriceReverse($ordine["spedizione"], v("cifre_decimali"));?> €
										<?php } ?>
									</td>
								</tr>
							</table>
							
							<?php if ($ordine["link_tracking"]) { ?>
							<div style="margin-top:10px;"><a target="_blank" href="<?php echo $ordine["link_tracking"];?>"><i class="fa fa-truck"></i> <?php echo gtext("Link tracking");?></a></div>
							<?php } ?>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
			
			<div class="row">
				<?php if (trim($ordine["dedica"]) || trim($ordine["firma"])) { ?>
				<div class="col-lg-6">
					<div class="box">
						<div class="box-header with-border main">
							<h4 style="margin-top:0px;" class="text-bold"><?php echo gtext("Dedica e firma");?></h4>
							<blockquote cite="#">
								<div class="uk-margin-small-bottom"><?php echo nl2br($ordine["dedica"]);?></div>
								<?php if (trim($ordine["firma"])) { ?>
								<footer><?php echo $ordine["firma"];?></footer>
								<?php } ?>
							</blockquote>
							<?php if (v("attiva_liste_regalo")) { ?>
								<?php $dedica = OrdiniModel::g()->getElemendoDedica($ordine["id_o"]);?>
								<?php if ($dedica) { ?>
								<div class="alert alert-info"><?php echo gtext("La mail con la dedica e la firma è stata inviata all'utente creatore della lista")." (<b>".$dedica["email"]."</b>) ".gtext("in data")." ".date("d/m/Y H:i", strtotime($dedica["data_creazione"]));?></div>
								<?php } ?>
							<?php } ?>
						</div>
					</div>
				</div>
				<?php } ?>
				<?php if (trim($ordine["note"])) { ?>
				<div class="col-lg-6">
					<div class="box">
						<div class="box-header with-border main">
							<h4 style="margin-top:0px;" class="text-bold"><?php echo gtext("Note cliente");?></h4>
							<?php echo nl2br($ordine["note"])?>
						</div>
					</div>
				</div>
				<?php } ?>
				<?php if ($ordine["note_interne"]) { ?>
				<div class="col-lg-6">
					<div class="box">
						<div class="box-header with-border main">
							<h4 style="margin-top:0px;" class="text-bold"><?php echo gtext("Note interne");?></h4>
							<?php echo nl2br($ordine["note_interne"])?>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
			
			<div class="box">
				<div class="box-header with-border main help_fatturazione">
					<div class="row">
						<div class="col-lg-6">
							<h4 style="margin-top:0px;" class="text-bold"><?php echo gtext("Dati di fatturazione");?>:</h4>
							
							<table class="table table-striped">
								<?php if ($cliente && $cliente["deleted"] == "no" && ControllersModel::checkAccessoAlController(array("regusers"))) { ?>
								<tr>
									<td class="first_column"><?php echo gtext("ACCOUNT CLIENTE");?></td>
									<td><a class="iframe label label-success" href="<?php echo $this->baseUrl."/regusers/form/update/".$cliente["id_user"]?>?partial=Y"><?php echo $cliente["username"];?></a></td>
								</tr>
								<?php } ?>
								<?php if (strcmp($ordine["tipo_cliente"],"privato") === 0 || strcmp($ordine["tipo_cliente"],"libero_professionista") === 0) { ?>
								<tr>
									<td class="first_column"><?php echo gtext("NOME");?></td>
									<td><?php echo $ordine["nome"];?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("COGNOME");?></td>
									<td><?php echo $ordine["cognome"];?></td>
								</tr>
								<?php } ?>
								<?php if (strcmp($ordine["tipo_cliente"],"azienda") === 0) { ?>
								<tr>
									<td class="first_column"><?php echo gtext("RAGIONE SOCIALE");?></td>
									<td><?php echo $ordine["ragione_sociale"];?></td>
								</tr>
								<?php } ?>
								<?php if (strcmp($ordine["tipo_cliente"],"azienda") === 0 || strcmp($ordine["tipo_cliente"],"libero_professionista") === 0) { ?>
								<tr>
									<td class="first_column"><?php echo gtext("PARTITA IVA");?></td>
									<td><?php echo $ordine["p_iva"];?></td>
								</tr>
								<?php } ?>
								<tr>
									<td class="first_column"><?php echo gtext("CODICE FISCALE");?></td>
									<td><?php echo $ordine["codice_fiscale"];?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("INDIRIZZO");?></td>
									<td><?php echo $ordine["indirizzo"];?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("CAP");?></td>
									<td><?php echo $ordine["cap"];?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("NAZIONE");?></td>
									<td><?php echo nomeNazione($ordine["nazione"]);?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("PROVINCIA");?></td>
									<td><?php echo $ordine["provincia"];?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("CITTÀ");?></td>
									<td><?php echo $ordine["citta"];?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("TELEFONO");?></td>
									<td><?php echo $ordine["telefono"];?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("EMAIL");?></td>
									<td><?php echo $ordine["email"];?></td>
								</tr>
								<?php if (strcmp($tipoOutput,"web") !== 0 and $sendPassword ) { ?>
								<tr>
									<td class="first_column"><?php echo gtext("PASSWORD");?></td>
									<td><?php echo $password;?></td>
								</tr>
								<?php } ?>
							</table>
							<br />
						</div>
						<?php if ($ordine["da_spedire"]) { ?>
						<div class="col-lg-6">
							<h4 style="margin-top:0px;" class="text-bold"><?php echo gtext("Dati di spedizione");?>:</h4>
							
							<table class="table table-striped">
								<tr>
									<td class="first_column"><?php echo gtext("INDIRIZZO");?></td>
									<td><?php echo $ordine["indirizzo_spedizione"];?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("CAP");?></td>
									<td><?php echo $ordine["cap_spedizione"];?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("NAZIONE");?></td>
									<td><?php echo nomeNazione($ordine["nazione_spedizione"]);?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("PROVINCIA");?></td>
									<td><?php echo $ordine["provincia_spedizione"];?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("CITTÀ");?></td>
									<td><?php echo $ordine["citta_spedizione"];?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("TELEFONO");?></td>
									<td><?php echo $ordine["telefono_spedizione"];?></td>
								</tr>
								<?php if (OpzioniModel::isAttiva("CAMPI_SALVATAGGIO_SPEDIZIONE", "destinatario_spedizione")) { ?>
								<tr>
									<td class="first_column"><?php echo gtext("DESTINATARIO");?></td>
									<td><?php echo $ordine["destinatario_spedizione"];?></td>
								</tr>
								<?php } ?>
							</table>
						</div>
						<?php } ?>
					</div>
					
					<div class="row">
						<div class="col-lg-12">
							<br />
							<h4 style="margin-top:0px;" class="text-bold"><?php echo gtext("Dati per fatturazione elettronica");?>:</h4>
							
							<table class="table table-striped">
								<tr>
									<td class="first_column"><?php echo gtext("PEC");?></td>
									<td><?php echo $ordine["pec"] ? $ordine["pec"] : "--";?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("CODICE DESTINATARIO");?></td>
									<td><?php echo $ordine["codice_destinatario"] ? $ordine["codice_destinatario"] : "--";?></td>
								</tr>
							</table>
						</div>
					</div>
                </div>
			</div>
		</div>
	</div>
</section>

<style>
.colonne_non_ivate
{
	background-color: #ecf0f5;
}
</style>
