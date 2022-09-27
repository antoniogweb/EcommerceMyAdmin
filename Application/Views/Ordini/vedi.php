<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<section class="content-header">
	<h1>Gestione ordini</h1>
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
			<div class="box">
				<div class="box-header with-border main">
					<?php echo flash("notice");?>
					<?php echo $notice_send;?>
					<?php echo flash("notice_send");?>
					
					<div class="row">
						<div class="col-lg-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<?php echo gtext("Gestione stato ordine");?>
								</div>
								<div class="panel-body">
									<div class="help_numero_ordine"><?php echo gtext("L'ordine");?> <b>#<?php echo $ordine["id_o"];?></b> <?php echo gtext("è nello stato");?>: <span class="help_stato_ordine label label-<?php echo labelStatoOrdine($ordine["stato"]);?>"><?php echo statoOrdine($ordine["stato"]);?></span></div>
									
									<?php $statiSuccessivi = OrdiniModel::statiSuccessivi($ordine["stato"]);?>
									<?php if (count($statiSuccessivi) > 0) { ?>
										<br /><br />Imposta nuovo stato ordine:<br />
										<?php foreach ($statiSuccessivi as $statoSucc) { ?>
										<a class="help_cambia_stato btn-sm btn btn-<?php echo labelStatoOrdine($statoSucc);?>" href="<?php echo $this->baseUrl."/ordini/setstato/".$ordine["id_o"]."/$statoSucc".$this->viewStatus;?>">Imposta come <b><?php echo str_replace("ORDINE","",strtoupper(statoOrdine($statoSucc)));?></b></a>
										<?php } ?>
									<?php } ?>
									
									<?php if (count($mail_altre) > 0) { ?>
									<br /><br /><h3 class="help_storico"><?php echo gtext("Storico invii mail");?></h3>
									<table class="table table-striped">
										<tr>
											<th><?php echo gtext("Data invio");?></th>
											<th><?php echo gtext("Tipo mail");?></th>
											<th style="width:1%;"></th>
										</tr>
										<?php foreach ($mail_altre as $mailFatt) { ?>
										<tr>
											<td><?php echo date("d-m-Y H:i", strtotime($mailFatt["data_creazione"]));?></td>
											<td><?php echo OrdiniModel::getTipoMail($mailFatt["tipo"]);?></td>
											<td><i style="font-size:18px;" class="text text-success fa fa-check-circle"></i></td>
										</tr>
										<?php } ?>
									</table>
									<?php } ?>
								</div>
							</div>
						</div>
						<?php if (v("fatture_attive")) { ?>
						<div class="col-lg-6">
							<?php if ($fattureOk) { ?>
							<div class="panel panel-info">
								<div class="panel-heading">
									Gestione fattura ordine
								</div>
								<div class="panel-body">
									<?php if (count($fatture) > 0) { ?>
									<a title="Invia mail con fattura in allegato" class="btn btn-primary btn-sm pull-right" href="<?php echo $this->baseUrl."/ordini/vedi/" . $ordine["id_o"].$this->viewStatus."&invia_fattura=Y";?>"><i class="fa fa-envelope"></i> Invia fattura</a>
									
									<a class="btn btn-success btn-sm" href="<?php echo $this->baseUrl."/fatture/vedi/" . $ordine["id_o"];?>"><i class="fa fa-download"></i> Scarica fattura</a>
									<a class="btn btn-default btn-sm" href="<?php echo $this->baseUrl."/fatture/crea/" . $ordine["id_o"];?>"><i class="fa fa-refresh"></i> Rigenera fattura</a>
									
									<?php } else { ?>
									<a class="btn btn-default btn-sm" href="<?php echo $this->baseUrl."/fatture/crea/" . $ordine["id_o"];?>"><i class="fa fa-refresh"></i> Genera fattura</a>
									<?php } ?>
								
									<?php if (count($mail_fatture) > 0) { ?>
									<h3>Storico invii fatture</h3>
									<table class="table table-striped">
										<tr>
											<th>Data invio</th>
											<th style="width:1%;"></th>
										</tr>
										<?php foreach ($mail_fatture as $mailFatt) { ?>
										<tr>
											<td><?php echo date("d-m-Y H:i", strtotime($mailFatt["data_creazione"]));?></td>
											<td><i style="font-size:18px;" class="text text-success fa fa-check-circle"></i></td>
										</tr>
										<?php } ?>
									</table>
									<?php } ?>
								</div>
							</div>
							<?php } ?>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
			
			<div class="box">
				<div class="box-header with-border main help_resoconto">
					<a class="iframe pull-right help_ordine_lato_cliente" href="<?php echo Domain::$name."/".$ordine["lingua"]."/resoconto-acquisto/".$ordine["id_o"]."/".$ordine["cart_uid"]?>"><i class="fa fa-eye"></i> <?php echo gtext("Vedi ordine lato cliente");?></a>
					<h3><?php echo gtext("Resoconto dell'ordine");?></h3>
					
					<table class="table table-striped">
						<tr>
							<td><?php echo gtext("N° Ordine");?>:</td>
							<td><b>#<?php echo $ordine["id_o"];?></b></td>
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
							<td><?php echo gtext("Metodo di pagamento");?>:</td>
							<td><b><?php echo metodoPagamento($ordine["pagamento"]);?></b></td>
						</tr>
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
					</table>
				</div>
			</div>
			
			<div class="box">
				<div class="box-header with-border main help_righe_ordine">
					<h3><?php echo gtext("Righe ordine");?>:</h3>
	
					<table width="100%" class="table table-striped" cellspacing="0">
						<thead>
							<tr class="">
								<th colspan="2" align="left" class=""><?php echo gtext("Prodotto");?></th>
								<th class="text-right"><?php echo gtext("Codice");?></th>
								<th class="text-right"><?php echo gtext("Peso");?></th>
								<th class="text-right"><?php echo gtext("Quantità");?></th>
								<th class="text-right colonne_non_ivate"><?php echo gtext("Prezzo");?><br /><?php echo gtext("IVA esclusa");?></th>
								<?php if (strcmp($ordine["usata_promozione"],"Y") === 0 && $ordine["tipo_promozione"] == "PERCENTUALE") { ?>
								<th class="text-right colonne_non_ivate"><?php echo gtext("Sconto");?><br />(<i><?php echo $ordine["nome_promozione"];?></i>)</th>
								<th class="text-right colonne_non_ivate"><?php echo gtext("Prezzo scontato");?><br /><?php echo gtext("IVA esclusa");?></th>
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
									<th class="text-right"><?php echo gtext("Totale IVA");?> <?php if (v("prezzi_ivati_in_carrello")) { ?><?php echo gtext("inclusa");?><?php } else { ?><?php echo gtext("esclusa");?><?php } ?></th>
								<?php } ?>
								<th class="text-right"><?php echo gtext("Totale IVA esclusa");?></th>
							</tr>
						</thead>
						
						<?php
						$pesoTotale = 0;
						foreach ($righeOrdine as $p) {
							$pesoTotale += $p["righe"]["peso"] * $p["righe"]["quantity"];
						?>
						<tr class="">
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
										</tr>
									<?php foreach ($elementiRiga as $el) { ?>
									<tr>
										<td style="text-align:left;font-size:13px;">
											<?php echo $el["email"];?>
										</td>
										<td style="text-align:left;font-size:13px;">
											<?php echo nl2br($el["testo"]);?>
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
										<br /><a title="<?php echo gtext("Vedi dettagli promo");?>" class="iframe" href="<?php echo $this->baseUrl."/promozioni/form/update/".$promo["id_p"];?>?partial=Y&nobuttons=Y"><i class="fa fa-info-circle"></i></a> <?php echo gtext("Codice");?>: <span class="badge badge-info"><?php echo $promo["codice"];?></span> <?php echo gtext("Stato");?>: <?php echo $promo["attivo"] == "Y" ? "<span class='label label-success'>".gtext("Attivo")."</span>" : "<span class='label label-warning'>".gtext("Non attivo")."</span>";?>
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
							<td class="text-right"><?php echo $p["righe"]["codice"];?></td>
							<td class="text-right"><?php echo setPriceReverse($p["righe"]["peso"]);?></td>
							<td class="text-right"><?php echo $p["righe"]["quantity"];?></td>
							<td class="text-right colonne_non_ivate">
								<?php if (isset($p["righe"]["in_promozione"]) and strcmp($p["righe"]["in_promozione"],"Y")===0){ echo "<del>".setPriceReverse($p["righe"]["prezzo_intero"], v("cifre_decimali"))." €</del>"; } ?> <span class="item_price_single"><?php echo setPriceReverse($p["righe"]["price"], v("cifre_decimali"));?></span> €
								
								<?php $jsonSconti = json_decode($p["righe"]["json_sconti"],true);?>
								
								<?php if (count($jsonSconti) > 0) { ?>
									<div class="well">
										<?php echo implode("<br />", $jsonSconti);?>
									</div>
								<?php } ?>
							</td>
							<?php if (strcmp($ordine["usata_promozione"],"Y") === 0 && $ordine["tipo_promozione"] == "PERCENTUALE") { ?>
							<td class="text-right colonne_non_ivate"><?php echo setPriceReverse($p["righe"]["percentuale_promozione"]);?> %</td>
							<td class="text-right colonne_non_ivate"><?php echo setPriceReverse($p["righe"]["prezzo_finale"], v("cifre_decimali"));?></td>
							<?php } ?>
							<td class="text-right colonne_non_ivate"><?php echo setPriceReverse($p["righe"]["iva"]);?> %</td>
							<?php if (false) { ?>
								<?php if (v("prezzi_ivati_in_carrello")) { ?>
									<td class="text-right">
										<?php if (isset($p["righe"]["in_promozione"]) and strcmp($p["righe"]["in_promozione"],"Y")===0){ echo "<del>".setPriceReverse($p["righe"]["prezzo_intero_ivato"])." €</del>"; } ?> <span class="item_price_single"><?php echo setPriceReverse($p["righe"]["price_ivato"]);?></span> €
									</td>
									<?php if (strcmp($ordine["usata_promozione"],"Y") === 0 && $ordine["tipo_promozione"] == "PERCENTUALE") { ?>
									<td class="text-right"><?php echo setPriceReverse($p["righe"]["percentuale_promozione"]);?> %</td>
									<td class="text-right"><?php echo setPriceReverse($p["righe"]["prezzo_finale_ivato"]);?></td>
									<?php } ?>
								<?php } ?>
								<td class="text-right">
									<?php if (v("prezzi_ivati_in_carrello")) { ?>
									<span class="item_price_subtotal"><?php echo setPriceReverse($p["righe"]["quantity"] * $p["righe"]["prezzo_finale_ivato"]);?></span> €
									<?php } else { ?>
									<span class="item_price_subtotal"><?php echo setPriceReverse($p["righe"]["quantity"] * $p["righe"]["prezzo_finale"],v("cifre_decimali"));?></span> €
									<?php } ?>
								</td>
							<?php } ?>
							<td class="text-right">
								<span class="item_price_subtotal"><?php echo setPriceReverse($p["righe"]["quantity"] * $p["righe"]["prezzo_finale"],v("cifre_decimali"));?></span> €
							</td>
						</tr>
						<?php } ?>
						<?php if ($ordine["da_spedire"]) { ?>
						<tr>
							<td colspan="2"><?php echo gtext("Spese di spedizione");?></td>
							<td class="text-right"></td>
							<td class="text-right"></td>
							<td class="text-right">
								1
							</td>
							<td class="text-right colonne_non_ivate">
								<?php echo setPriceReverse($ordine["spedizione"], v("cifre_decimali"));?> €
							</td>
							<?php if (strcmp($ordine["usata_promozione"],"Y") === 0 && $ordine["tipo_promozione"] == "PERCENTUALE") { ?>
							<td class="text-right colonne_non_ivate">
								0%
							</td>
							<td class="text-right colonne_non_ivate">
								<?php echo setPriceReverse($ordine["spedizione"], v("cifre_decimali"));?> €
							</td>
							<?php } ?>
							<td class="text-right colonne_non_ivate">
								<?php echo setPriceReverse($ordine["iva_spedizione"], 2);?> %
							</td>
							<?php if (false) { ?>
								<?php if (v("prezzi_ivati_in_carrello")) { ?>
									<td class="text-right">
										<?php echo setPriceReverse($ordine["spedizione_ivato"]);?> €
									</td>
									<?php if (strcmp($ordine["usata_promozione"],"Y") === 0 && $ordine["tipo_promozione"] == "PERCENTUALE") { ?>
									<td class="text-right">
										0%
									</td>
									<td class="text-right">
										<?php echo setPriceReverse($ordine["spedizione_ivato"]);?> €
									</td>
									<?php } ?>
								<?php } ?>
								<td class="text-right">
									<?php if (!v("prezzi_ivati_in_carrello")) { ?>
									<?php echo setPriceReverse($ordine["spedizione"], v("cifre_decimali"));?> €
									<?php } else { ?>
									<?php echo setPriceReverse($ordine["spedizione_ivato"]);?> €
									<?php } ?>
								</td>
							<?php } ?>
							<td class="text-right">
								<?php echo setPriceReverse($ordine["spedizione"], v("cifre_decimali"));?> €
							</td>
						</tr>
						<?php } ?>
						<?php if (strcmp($ordine["usata_promozione"],"Y") === 0 && $ordine["tipo_promozione"] == "ASSOLUTO") { ?>
						<tr class="text text-warning">
							<td colspan="2"><?php echo gtext("Coupon");?>: <b><?php echo $ordine["nome_promozione"];?></b>. <?php echo gtext("Codice coupon");?>: <b><?php echo $ordine["codice_promozione"];?></b></td>
							<td class="text-right"></td>
							<td class="text-right"></td>
							<td class="text-right">
								1
							</td>
							<td class="text-right colonne_non_ivate">
								- <?php echo setPriceReverse($ordine["euro_promozione"] / (1 + ($ordine["iva_spedizione"] / 100)), v("cifre_decimali"));?> €
							</td>
							<td class="text-right colonne_non_ivate">
								<?php echo setPriceReverse($ordine["iva_spedizione"], 2);?> %
							</td>
							<td class="text-right">
								- <?php echo setPriceReverse($ordine["euro_promozione"] / (1 + ($ordine["iva_spedizione"] / 100)), v("cifre_decimali"));?> €
							</td>
						</tr>
						<?php } ?>
					</table>
				</div>
			</div>
			
			<div class="row">
				<div class="col-lg-6">
					<div class="box">
						<div class="box-header with-border main help_totali_ordine">
							<h3><?php echo gtext("Totali ordine");?>:</h3>
							
							<?php
							$arrayIva = OrdiniModel::getTotaliIva($ordine["id_o"]);
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
							<h3><?php echo gtext("Spedizione");?></h3>
							
							<table class="table table-striped">
								<tr>
									<td>
										<?php echo gtext("Spese spedizione");?><br />
										<?php echo gtext("Peso totale");?>: <span class="badge badge-info"><b><?php echo setPriceReverse($pesoTotale);?> kg</b></span> <?php if (!empty($corriere)) { ?><br />
										<?php echo gtext("Corriere scelto");?>: <span class="badge badge-info"><?php echo $corriere["titolo"];?></span><?php } ?>
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
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
			
			<?php if (trim($ordine["note"])) { ?>
			<div class="box">
				<div class="box-header with-border main">
					<h3><?php echo gtext("Note");?></h3>
					<?php echo nl2br($ordine["note"])?>
				</div>
			</div>
			<?php } ?>
			
			<div class="box">
				<div class="box-header with-border main help_fatturazione">
					<div class="row">
						<div class="col-lg-6">
							<h3><?php echo gtext("Dati di fatturazione");?>:</h3>
							
							<table class="table table-striped">
								<?php if ($cliente) { ?>
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
						</div>
						<?php if ($ordine["da_spedire"]) { ?>
						<div class="col-lg-6">
							<h3><?php echo gtext("Dati di spedizione");?>:</h3>
							
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
								<!--<tr>
									<td class="first_column">PROVINCIA</td>
									<td><?php echo $ordine["nazione_spedizione"];?></td>
								</tr>-->
								<tr>
									<td class="first_column"><?php echo gtext("TELEFONO");?></td>
									<td><?php echo $ordine["telefono_spedizione"];?></td>
								</tr>
							</table>
						</div>
						<?php } ?>
					</div>
					
					<div class="row">
						<div class="col-lg-12">
							<br />
							<h3><?php echo gtext("Dati per fatturazione elettronica");?>:</h3>
							
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
