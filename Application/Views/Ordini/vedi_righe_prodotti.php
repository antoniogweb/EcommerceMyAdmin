<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

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
