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
				<?php echo $menu;?>
			</div>
			<?php } ?>
			<div class="box">
				<div class="box-header with-border main">
					<?php echo $notice_send;?>
					<?php echo flash("notice_send");?>
					
					<div class="row">
						<div class="col-lg-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									Gestione stato ordine
								</div>
								<div class="panel-body">
									L'ordine <b>#<?php echo $ordine["id_o"];?></b> è nello stato: <span class="label label-<?php echo labelStatoOrdine($ordine["stato"]);?>"><?php echo statoOrdine($ordine["stato"]);?></span>
									
									<?php $statiSuccessivi = OrdiniModel::statiSuccessivi($ordine["stato"]);?>
									<?php if (count($statiSuccessivi) > 0) { ?>
										<br /><br />Imposta nuovo stato ordine:<br />
										<?php foreach ($statiSuccessivi as $statoSucc) { ?>
										<a class="btn-sm btn btn-<?php echo labelStatoOrdine($statoSucc);?>" href="<?php echo $this->baseUrl."/ordini/setstato/".$ordine["id_o"]."/$statoSucc".$this->viewStatus;?>">Imposta come <b><?php echo str_replace("ORDINE","",strtoupper(statoOrdine($statoSucc)));?></b></a>
										<?php } ?>
									<?php } ?>
									
									<?php if (count($mail_altre) > 0) { ?>
									<h3>Storico invii mail</h3>
									<table class="table table-striped">
										<tr>
											<th>Data invio</th>
											<th>Tipo mail</th>
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
				<div class="box-header with-border main">
					<a class="iframe pull-right" href="<?php echo Domain::$name."/".$ordine["lingua"]."/resoconto-acquisto/".$ordine["id_o"]."/".$ordine["cart_uid"]?>"><i class="fa fa-eye"></i> Vedi ordine lato cliente</a>
					<h3>Resoconto dell'ordine</h3>
					
					<table class="table table-striped">
						<tr>
							<td>Ordine:</td>
							<td><b>#<?php echo $ordine["id_o"];?></b></td>
						</tr>
						<tr>
							<td>Data:</td>
							<td><b><?php echo smartDate($ordine["data_creazione"]);?></b></td>
						</tr>
						<tr>
							<td>Totale:</td>
							<td><b>&euro; <?php echo setPriceReverse($ordine["total"]);?></b></td>
						</tr>
						<?php if (strcmp($tipoOutput,"web") === 0 or strcmp($ordine["pagamento"],"bonifico") === 0 or strcmp($ordine["pagamento"],"contrassegno") === 0) { ?>
						<tr>
							<td>Stato ordine:</td>
							<td><b><span class="label label-<?php echo labelStatoOrdine($ordine["stato"]);?>"><?php echo statoOrdine($ordine["stato"]);?></span></b></td>
						</tr>
						<?php } ?>
						<tr>
							<td>Metodo di pagamento:</td>
							<td><b><?php echo metodoPagamento($ordine["pagamento"]);?></b></td>
						</tr>
						<?php if (v("attiva_ip_location")) { ?>
						<tr>
							<td>Nazione navigazione:</td>
							<td><b><?php echo findTitoloDaCodice($ordine["nazione_navigazione"]);?></b></td>
						</tr>
						<?php } ?>
					</table>
				</div>
			</div>
			
			<div class="box">
				<div class="box-header with-border main">
					<h3>Righe ordine:</h3>
	
					<table width="100%" class="table table-striped" cellspacing="0">
						<thead>
							<tr class="">
								<th colspan="2" align="left" class="">Prodotto</th>
								<th class="text-right">Codice</th>
								<th class="text-right">Peso</th>
								<th class="text-right">Quantità</th>
								<th class="text-right colonne_non_ivate">Prezzo<br />IVA esclusa</th>
								<?php if (strcmp($ordine["usata_promozione"],"Y") === 0) { ?>
								<th class="text-right colonne_non_ivate">Sconto<br />(<i><?php echo $ordine["nome_promozione"];?></i>)</th>
								<th class="text-right colonne_non_ivate">Prezzo scontato<br />IVA esclusa</th>
								<?php } ?>
								<th class="text-right colonne_non_ivate">Aliquota</th>
								<?php if (false) { ?>
									<?php if (v("prezzi_ivati_in_carrello")) { ?>
										<th class="text-right">Prezzo<br />IVA inclusa</th>
										<?php if (strcmp($ordine["usata_promozione"],"Y") === 0) { ?>
										<th class="text-right">Sconto<br />(<i><?php echo $ordine["nome_promozione"];?></i>)</th>
										<th class="text-right">Prezzo scontato<br />IVA inclusa</th>
										<?php } ?>
									<?php } ?>
									<th class="text-right">Totale IVA <?php if (v("prezzi_ivati_in_carrello")) { ?>inclusa<?php } else { ?>esclusa<?php } ?></th>
								<?php } ?>
								<th class="text-right">Totale IVA esclusa</th>
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
							<?php if (strcmp($ordine["usata_promozione"],"Y") === 0) { ?>
							<td class="text-right colonne_non_ivate"><?php echo setPriceReverse($p["righe"]["percentuale_promozione"]);?> %</td>
							<td class="text-right colonne_non_ivate"><?php echo setPriceReverse($p["righe"]["prezzo_finale"], v("cifre_decimali"));?></td>
							<?php } ?>
							<td class="text-right colonne_non_ivate"><?php echo setPriceReverse($p["righe"]["iva"]);?> %</td>
							<?php if (false) { ?>
								<?php if (v("prezzi_ivati_in_carrello")) { ?>
									<td class="text-right">
										<?php if (isset($p["righe"]["in_promozione"]) and strcmp($p["righe"]["in_promozione"],"Y")===0){ echo "<del>".setPriceReverse($p["righe"]["prezzo_intero_ivato"])." €</del>"; } ?> <span class="item_price_single"><?php echo setPriceReverse($p["righe"]["price_ivato"]);?></span> €
									</td>
									<?php if (strcmp($ordine["usata_promozione"],"Y") === 0) { ?>
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
						<tr>
							<td colspan="2">Spese di spedizione</td>
							<td class="text-right"></td>
							<td class="text-right"></td>
							<td class="text-right">
								1
							</td>
							<td class="text-right colonne_non_ivate">
								<?php echo setPriceReverse($ordine["spedizione"], v("cifre_decimali"));?> €
							</td>
							<?php if (strcmp($ordine["usata_promozione"],"Y") === 0) { ?>
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
									<?php if (strcmp($ordine["usata_promozione"],"Y") === 0) { ?>
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
					</table>
				</div>
			</div>
			
			<div class="row">
				<div class="col-lg-6">
					<div class="box">
						<div class="box-header with-border main">
							<h3>Totali ordine:</h3>
							
							<?php
							$arrayIva = OrdiniModel::getTotaliIva($ordine["id_o"]);
							$totaleIva = number_format(array_sum($arrayIva),2,".","");
							$imponibile = $ordine["total"] - $totaleIva;
							?>
							
							<table class="table table-striped">
								<tr>
									<td>Imponibile</td>
									<td class="text-right"><b><?php echo setPriceReverse($imponibile);?> €</b></td>
								</tr>
								<?php foreach ($arrayIva as $idAliquota => $totale) { ?>
								<tr>
									<td><?php echo IvaModel::getTitoloDaId($idAliquota);?></td>
									<td class="text-right"><b><?php echo setPriceReverse($totale);?> €</b></td>
								</tr>
								<?php } ?>
								<tr>
									<td>Totale ordine</td>
									<td class="text-right"><b><?php echo setPriceReverse($ordine["total"]);?> €</b></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="box">
						<div class="box-header with-border main">
							<h3>Spedizione</h3>
							
							<table class="table table-striped">
								<tr>
									<td>
										Spese spedizione<br />
										Peso totale: <span class="badge badge-info"><b><?php echo setPriceReverse($pesoTotale);?> kg</b></span> <?php if (!empty($corriere)) { ?><br />
										Corriere scelto: <span class="badge badge-info"><?php echo $corriere["titolo"];?></span><?php } ?>
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
			</div>
			
			<?php if (trim($ordine["note"])) { ?>
			<div class="box">
				<div class="box-header with-border main">
					<h3>Note</h3>
					<?php echo nl2br($ordine["note"])?>
				</div>
			</div>
			<?php } ?>
			
			<div class="box">
				<div class="box-header with-border main">
					<div class="row">
						<div class="col-lg-6">
							<h3>Dati di fatturazione:</h3>
							
							<table class="table table-striped">
								<?php if ($cliente) { ?>
								<tr>
									<td class="first_column">ACCOUNT CLIENTE</td>
									<td><a class="iframe label label-success" href="<?php echo $this->baseUrl."/regusers/form/update/".$cliente["id_user"]?>?partial=Y"><?php echo $cliente["username"];?></a></td>
								</tr>
								<?php } ?>
								<?php if (strcmp($ordine["tipo_cliente"],"privato") === 0 || strcmp($ordine["tipo_cliente"],"libero_professionista") === 0) { ?>
								<tr>
									<td class="first_column">NOME</td>
									<td><?php echo $ordine["nome"];?></td>
								</tr>
								<tr>
									<td class="first_column">COGNOME</td>
									<td><?php echo $ordine["cognome"];?></td>
								</tr>
								<?php } ?>
								<?php if (strcmp($ordine["tipo_cliente"],"azienda") === 0) { ?>
								<tr>
									<td class="first_column">RAGIONE SOCIALE</td>
									<td><?php echo $ordine["ragione_sociale"];?></td>
								</tr>
								<?php } ?>
								<?php if (strcmp($ordine["tipo_cliente"],"azienda") === 0 || strcmp($ordine["tipo_cliente"],"libero_professionista") === 0) { ?>
								<tr>
									<td class="first_column">PARTITA IVA</td>
									<td><?php echo $ordine["p_iva"];?></td>
								</tr>
								<?php } ?>
								<tr>
									<td class="first_column">CODICE FISCALE</td>
									<td><?php echo $ordine["codice_fiscale"];?></td>
								</tr>
								<tr>
									<td class="first_column">INDIRIZZO</td>
									<td><?php echo $ordine["indirizzo"];?></td>
								</tr>
								<tr>
									<td class="first_column">CAP</td>
									<td><?php echo $ordine["cap"];?></td>
								</tr>
								<tr>
									<td class="first_column">NAZIONE</td>
									<td><?php echo nomeNazione($ordine["nazione"]);?></td>
								</tr>
								<tr>
									<td class="first_column">PROVINCIA</td>
									<td><?php echo $ordine["provincia"];?></td>
								</tr>
								<tr>
									<td class="first_column">CITTÀ</td>
									<td><?php echo $ordine["citta"];?></td>
								</tr>
								<tr>
									<td class="first_column">TELEFONO</td>
									<td><?php echo $ordine["telefono"];?></td>
								</tr>
								<tr>
									<td class="first_column">EMAIL</td>
									<td><?php echo $ordine["email"];?></td>
								</tr>
								<?php if (strcmp($tipoOutput,"web") !== 0 and $sendPassword ) { ?>
								<tr>
									<td class="first_column">PASSWORD</td>
									<td><?php echo $password;?></td>
								</tr>
								<?php } ?>
							</table>
						</div>
						<div class="col-lg-6">
							<h3>Dati di spedizione:</h3>
							
							<table class="table table-striped">
								<tr>
									<td class="first_column">INDIRIZZO</td>
									<td><?php echo $ordine["indirizzo_spedizione"];?></td>
								</tr>
								<tr>
									<td class="first_column">CAP</td>
									<td><?php echo $ordine["cap_spedizione"];?></td>
								</tr>
								<tr>
									<td class="first_column">NAZIONE</td>
									<td><?php echo nomeNazione($ordine["nazione_spedizione"]);?></td>
								</tr>
								<tr>
									<td class="first_column">PROVINCIA</td>
									<td><?php echo $ordine["provincia_spedizione"];?></td>
								</tr>
								<tr>
									<td class="first_column">CITTÀ</td>
									<td><?php echo $ordine["citta_spedizione"];?></td>
								</tr>
								<!--<tr>
									<td class="first_column">PROVINCIA</td>
									<td><?php echo $ordine["nazione_spedizione"];?></td>
								</tr>-->
								<tr>
									<td class="first_column">TELEFONO</td>
									<td><?php echo $ordine["telefono_spedizione"];?></td>
								</tr>
							</table>
						</div>
					</div>
					
					<div class="row">
						<div class="col-lg-12">
							<br />
							<h3>Dati per fatturazione elettronica:</h3>
							
							<table class="table table-striped">
								<tr>
									<td class="first_column">PEC</td>
									<td><?php echo $ordine["pec"] ? $ordine["pec"] : "--";?></td>
								</tr>
								<tr>
									<td class="first_column">CODICE DESTINATARIO</td>
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
