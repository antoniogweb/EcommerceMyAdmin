<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$mostraIvato = $this->viewArgs["prezzi"] == "I" ? true : false;
$labelIvaInclusaEsclusa = $this->viewArgs["prezzi"] == "I" ? "inclusa" : "esclusa";
$haRipartizioni = OrdiniivaripartitaModel::g()->clear()->where(array(
	"id_o"	=>	(int)$ordine["id_o"],
))->rowNumber();
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
							<?php include($this->viewPath("vedi_top_left"));?>
						</div>
						<div class="col-lg-6">
							<?php include($this->viewPath("vedi_top_right"));?>
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
								$segnoPrezzo = $p["righe"]["acconto"] ? "- " : "";
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
								<?php if (strcmp($p["righe"]["id_c"],0) !== 0) { 
									$attributiRiga = $p["righe"]["attributi_backend"] ? $p["righe"]["attributi_backend"] : $p["righe"]["attributi"];
									echo "<br />".$attributiRiga; 
								} ?>
								</td>
								<?php if ($ordine["da_spedire"] && v("attiva_gestione_spedizioni")) { ?>
								<td class="text-left"><?php echo SpedizioninegozioModel::g(false)->badgeSpedizione($ordine["id_o"], $p["righe"]["id_r"], false, "")?></td>
								<?php } ?>
								<td class="text-right"><?php echo $p["righe"]["codice"];?></td>
								<td class="text-right"><?php echo $p["righe"]["id_riga_tipologia"] ? "" : setPriceReverse($p["righe"]["peso"]);?></td>
								<td class="text-right"><?php echo $p["righe"]["quantity"];?></td>
								<td class="text-right colonne_non_ivate">
									<?php
									$campoIvato = $mostraIvato ? "_ivato" : "";
									$prezzoFisso = $p["righe"]["prezzo_fisso_intero$campoIvato"];
									$prezzoFissoFinale = $p["righe"]["prezzo_fisso$campoIvato"];
									
									$strPrezzoFisso = ($prezzoFisso > 0) ? setPriceReverse($prezzoFisso)." + " : "";
									$strPrezzoFissoFinale = ($prezzoFissoFinale > 0) ? setPriceReverse($prezzoFissoFinale)." + " : "";
									?>
									<?php if (isset($p["righe"]["in_promozione"]) and strcmp($p["righe"]["in_promozione"],"Y")===0){ echo "<del>".$strPrezzoFisso.($mostraIvato ? $segnoPrezzo.setPriceReverse($p["righe"]["prezzo_intero_ivato"]) : $segnoPrezzo.setPriceReverse($p["righe"]["prezzo_intero"], v("cifre_decimali")))." €</del>"; } ?> <span class="item_price_single"><?php echo $strPrezzoFissoFinale.($mostraIvato ? $segnoPrezzo.setPriceReverse($p["righe"]["price_ivato"]) : $segnoPrezzo.setPriceReverse($p["righe"]["price"], v("cifre_decimali")));?></span> €
									
									<?php $jsonSconti = json_decode($p["righe"]["json_sconti"],true);?>
									
									<?php if (count($jsonSconti) > 0) { ?>
										<div class="well no-margin">
											<?php echo implode("<br />", $jsonSconti);?>
										</div>
									<?php } ?>
								</td>
								<?php if (strcmp($ordine["usata_promozione"],"Y") === 0 && $ordine["tipo_promozione"] == "PERCENTUALE") { ?>
								<td class="text-right colonne_non_ivate"><?php echo $p["righe"]["id_riga_tipologia"] ? "0,00%" : setPriceReverse($p["righe"]["percentuale_promozione"])."%";?> </td>
								<td class="text-right colonne_non_ivate"><?php echo $mostraIvato ? $segnoPrezzo.setPriceReverse($p["righe"]["prezzo_finale_ivato"]) : $segnoPrezzo.setPriceReverse($p["righe"]["prezzo_finale"], v("cifre_decimali"));?></td>
								<?php } ?>
								<td class="text-right colonne_non_ivate"><?php echo setPriceReverse($p["righe"]["iva"]);?> %</td>
								<td class="text-right">
									<span class="item_price_subtotal"><?php echo $mostraIvato ? $segnoPrezzo.setPriceReverse($p["righe"]["quantity"] * $p["righe"]["prezzo_finale_ivato"]) : $segnoPrezzo.setPriceReverse($p["righe"]["quantity"] * $p["righe"]["prezzo_finale"],v("cifre_decimali"));?></span> €
								</td>
							</tr>
							<?php } ?>
							<?php if ($ordine["costo_pagamento"] && $ordine["mostra_spese_pagamento_ordine_frontend"]) { ?>
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
									0,00%
								</td>
								<td class="text-right colonne_non_ivate">
									<?php echo $mostraIvato ? setPriceReverse($ordine["costo_pagamento_ivato"]) : setPriceReverse($ordine["costo_pagamento"], v("cifre_decimali"));?> €
								</td>
								<?php } ?>
								<td class="text-right colonne_non_ivate">
									<?php echo $haRipartizioni ? "" : setPriceReverse($ordine["iva_spedizione"], 2)." %";?>
								</td>
								<td class="text-right">
									<?php echo $mostraIvato ? setPriceReverse($ordine["costo_pagamento_ivato"]) : setPriceReverse($ordine["costo_pagamento"], v("cifre_decimali"));?> €
								</td>
							</tr>
							<?php } ?>
							<?php if ($ordine["da_spedire"] && $ordine["mostra_spese_spedizione_ordine_frontend"]) { ?>
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
									0,00%
								</td>
								<td class="text-right colonne_non_ivate">
									<?php echo $mostraIvato ? setPriceReverse($ordine["spedizione_ivato"]) : setPriceReverse($ordine["spedizione"], v("cifre_decimali"));?> €
								</td>
								<?php } ?>
								<td class="text-right colonne_non_ivate">
									<?php echo $haRipartizioni ? "" : setPriceReverse($ordine["iva_spedizione"], 2)." %";?>
								</td>
								<td class="text-right">
									<?php echo $mostraIvato ? setPriceReverse($ordine["spedizione_ivato"]) : setPriceReverse($ordine["spedizione"], v("cifre_decimali"));?> €
								</td>
							</tr>
							<?php } ?>
							<?php if ($ordine["euro_crediti"] > 0) { ?>
							<tr>
								<td></td>
								<td colspan="2"><?php echo gtext("Sconto crediti");?></td>
								<?php if ($ordine["da_spedire"] && v("attiva_gestione_spedizioni")) { ?><td class="text-left"></td><?php } ?>
								<td class="text-right"></td>
								<td class="text-right"></td>
								<td class="text-right">
									1
								</td>
								<td class="text-right colonne_non_ivate">
									- <?php echo $mostraIvato ? setPriceReverse($ordine["euro_crediti"]) : setPriceReverse($ordine["euro_crediti"] / (1 + ($ordine["iva_spedizione"] / 100)), v("cifre_decimali"));?> €
								</td>
								<?php if (strcmp($ordine["usata_promozione"],"Y") === 0 && $ordine["tipo_promozione"] == "PERCENTUALE") { ?>
								<td class="text-right colonne_non_ivate">
									0,00%
								</td>
								<td class="text-right colonne_non_ivate">
									- <?php echo $mostraIvato ? setPriceReverse($ordine["euro_crediti"]) : setPriceReverse($ordine["euro_crediti"] / (1 + ($ordine["iva_spedizione"] / 100)), v("cifre_decimali"));?> €
								</td>
								<?php } ?>
								<td class="text-right colonne_non_ivate">
									<?php echo $haRipartizioni ? "" : setPriceReverse($ordine["iva_spedizione"], 2)." %";?>
								</td>
								<td class="text-right">
									- <?php echo $mostraIvato ? setPriceReverse($ordine["euro_crediti"]) : setPriceReverse($ordine["euro_crediti"] / (1 + ($ordine["iva_spedizione"] / 100)), v("cifre_decimali"));?> €
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
									<?php echo $haRipartizioni ? "" :  setPriceReverse($ordine["iva_spedizione"], 2)." %";?>
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
									<td class="first_column"><?php echo gtext("Account cliente");?></td>
									<td><a class="iframe label label-success" href="<?php echo $this->baseUrl."/regusers/form/update/".$cliente["id_user"]?>?partial=Y"><?php echo $cliente["username"];?></a></td>
								</tr>
								<?php } ?>
								<?php if (strcmp($ordine["tipo_cliente"],"privato") === 0 || strcmp($ordine["tipo_cliente"],"libero_professionista") === 0) { ?>
								<tr>
									<td class="first_column"><?php echo gtext("Nome");?></td>
									<td><?php echo $ordine["nome"];?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("Cognome");?></td>
									<td><?php echo $ordine["cognome"];?></td>
								</tr>
								<?php } ?>
								<?php if (strcmp($ordine["tipo_cliente"],"azienda") === 0) { ?>
								<tr>
									<td class="first_column"><?php echo gtext("Ragione sociale");?></td>
									<td><?php echo $ordine["ragione_sociale"];?></td>
								</tr>
								<?php } ?>
								<?php if (strcmp($ordine["tipo_cliente"],"azienda") === 0 || strcmp($ordine["tipo_cliente"],"libero_professionista") === 0) { ?>
								<tr>
									<td class="first_column"><?php echo gtext("Partita IVA");?></td>
									<td><?php echo $ordine["p_iva"];?></td>
								</tr>
								<?php } ?>
								<tr>
									<td class="first_column"><?php echo gtext("Codice fiscale");?></td>
									<td><?php echo $ordine["codice_fiscale"];?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("Indirizzo");?></td>
									<td><?php echo $ordine["indirizzo"];?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("Cap");?></td>
									<td><?php echo $ordine["cap"];?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("Nazione");?></td>
									<td><?php echo nomeNazione($ordine["nazione"]);?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("Provincia");?></td>
									<td><?php echo $ordine["provincia"];?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("Città");?></td>
									<td><?php echo $ordine["citta"];?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("Telefono");?></td>
									<td><?php echo $ordine["telefono"];?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("Email");?></td>
									<td><?php echo $ordine["email"];?></td>
								</tr>
								<?php if (VariabiliModel::attivaCodiceGestionale()) { ?>
								<tr>
									<td class="first_column"><?php echo gtext("Codice gestionale");?></td>
									<td><?php echo $ordine["codice_gestionale_cliente"];?></td>
								</tr>
								<?php } ?>
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
									<td class="first_column"><?php echo gtext("Indirizzo");?></td>
									<td><?php echo $ordine["indirizzo_spedizione"];?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("Cap");?></td>
									<td><?php echo $ordine["cap_spedizione"];?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("Nazione");?></td>
									<td><?php echo nomeNazione($ordine["nazione_spedizione"]);?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("Provincia");?></td>
									<td><?php echo $ordine["provincia_spedizione"];?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("Città");?></td>
									<td><?php echo $ordine["citta_spedizione"];?></td>
								</tr>
								<tr>
									<td class="first_column"><?php echo gtext("Telefono");?></td>
									<td><?php echo $ordine["telefono_spedizione"];?></td>
								</tr>
								<?php if (OpzioniModel::isAttiva("CAMPI_SALVATAGGIO_SPEDIZIONE", "destinatario_spedizione")) { ?>
								<tr>
									<td class="first_column"><?php echo gtext("Destinatario");?></td>
									<td><?php echo $ordine["destinatario_spedizione"];?></td>
								</tr>
								<?php } ?>
								<?php if (VariabiliModel::attivaCodiceGestionale()) { ?>
								<tr>
									<td class="first_column"><?php echo gtext("Codice gestionale indirizzo");?></td>
									<td><?php echo $ordine["codice_gestionale_spedizione"];?></td>
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
