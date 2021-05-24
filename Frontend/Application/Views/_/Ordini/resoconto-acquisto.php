<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php
if (!isset($baseUrl))
	$baseUrl = $this->baseUrl."/";
?>

<?php if (strcmp($tipoOutput,"mail_al_negozio") === 0 || strcmp($tipoOutput,"mail_al_cliente") === 0) { ?>
<h1><?php echo gtext("Resoconto dell'ordine");?></h1>
<?php } ?>

<?php if (strcmp($tipoOutput,"web") === 0) { ?>
<!--<div class="for_print">
	<a href="#" class="stampa_pagina">Stampa</a>
</div>-->
<?php } ?>

<?php if (strcmp($tipoOutput,"mail_al_negozio") !== 0 and !isset($_GET["n"])) { ?>
<p class="uk-text-muted uk-text-small"><?php echo gtext("Grazie! Il suo ordine è stato ricevuto e verrà processato al più presto.");?></p>
<?php } ?>

<?php if (strcmp($tipoOutput,"mail_al_negozio") === 0 ) { ?>
<p class="uk-text-muted uk-text-small"><?php echo gtext("Può controllare l'ordine al", false); ?> <a href="<?php echo $baseUrl."resoconto-acquisto/".$ordine["id_o"]."/".$ordine["cart_uid"]."/".$ordine["admin_token"];?>?n=y"><?php echo gtext("seguente indirizzo web", false); ?></a>.</p>
<?php } ?>

<?php if (strcmp($tipoOutput,"mail_al_cliente") === 0 ) { ?>
<p class="uk-text-muted uk-text-small"><?php echo gtext("Può controllare in qualsiasi momento i dettagli dell'ordine al");?> <a href="<?php echo $baseUrl."resoconto-acquisto/".$ordine["id_o"]."/".$ordine["cart_uid"]."/token";?>?n=y"><?php echo gtext("seguente indirizzo web");?></a>.</p>
<?php } ?>

<?php if (strcmp($tipoOutput,"web") === 0 and !isset($_GET["n"])) { ?>
<p class="uk-text-muted uk-text-small"><?php echo gtext("Controlli la sua casella di posta elettronica, le è stata inviata una mail con il resoconto dell'ordine.");?></p>
<?php } ?>

<?php if (strcmp($tipoOutput,"web") === 0 and strcmp($ordine["admin_token"],$admin_token) === 0) { ?>
<!--<div id="admin_tools">
	<p>Modifica lo stato dell'ordine:</p>
	<?php echo $notice;?>
	
	<?php if(isset($actionFromAdmin)) { ?>
	<form action="<?php echo $actionFromAdmin;?>" method="POST">
	<?php } else { ?>
	<form action="<?php echo $baseUrl."resoconto-acquisto/".$ordine["id_o"]."/".$ordine["cart_uid"]."/".$ordine["admin_token"];?>?n=y" method="POST">
	<?php } ?>
		<?php 
		$statiOrdine = OrdiniModel::$stati;
		echo Html_Form::select("stato",$ordine["stato"],$statiOrdine,null,null,"yes");?>
		<input type="submit" name="modifica_stato_ordine" value="Invia" />
		
	</form>
</div>-->
<?php } ?>

<table class="table table_2 table_left">
	<tr>
		<td><?php echo gtext("Ordine", false); ?>:</td>
		<td><b>#<?php echo $ordine["id_o"];?></b></td>
	</tr>
	<tr>
		<td><?php echo gtext("Data", false); ?>:</td>
		<td><b><?php echo smartDate($ordine["data_creazione"]);?></b></td>
	</tr>
	<tr>
		<td><?php echo gtext("Totale", false); ?>:</td>
		<td><b>&euro; <?php echo setPriceReverse($ordine["total"]);?></b></td>
	</tr>
	<?php if (strcmp($tipoOutput,"web") === 0 or strcmp($ordine["pagamento"],"bonifico") === 0 or strcmp($ordine["pagamento"],"contrassegno") === 0) { ?>
	<tr>
		<td><?php echo gtext("Stato ordine", false); ?>:</td>
		<td><b><?php echo statoOrdine($ordine["stato"]);?></b></td>
	</tr>
	<?php } ?>
	<tr>
		<td><?php echo gtext("Metodo di pagamento", false); ?>:</td>
		<td><b><?php echo metodoPagamento($ordine["pagamento"]);?></b></td>
	</tr>
</table>

<?php if (strcmp($ordine["pagamento"],"bonifico") === 0 and strcmp($ordine["stato"],"pending") === 0) { ?>

<h2><?php echo gtext("Dettagli pagamento:");?></h2>

<p><?php echo testo("Esegua il bonifico alle seguenti coordinate bancarie ...");?></p>

<?php } else if (strcmp($ordine["pagamento"],"contrassegno") === 0 and strcmp($ordine["stato"],"pending") === 0) { ?>

<h2><?php echo gtext("Dettagli pagamento:");?></h2>

<p><?php echo testo("Esegua il pagamento al corriere alla consegna della merce.");?></p>

<?php } else if (strcmp($ordine["pagamento"],"paypal") === 0 and strcmp($ordine["stato"],"pending") === 0 and strcmp($tipoOutput,"web") === 0) { ?>

	<?php if(!isset($actionFromAdmin)) { ?>
	<div class="pulsante_paypal"><br /><?php echo $pulsantePaypal;?></div>
	<?php } else { ?>
	<h2>Dettagli pagamento:</h2>
	<p>Pagamento tramite paypal ancora da eseguire</p>
	<?php } ?>

<?php } else if (strcmp($ordine["pagamento"],"carta_di_credito") === 0 and strcmp($ordine["stato"],"pending") === 0 and strcmp($tipoOutput,"web") === 0) { ?>

	<?php if(!isset($actionFromAdmin)) { ?>
	<div class="pulsante_paypal"><?php echo $pulsantePaga;?></div>
	<?php } else { ?>
	<h2>Dettagli pagamento:</h2>
	<p>Pagamento tramite carta di credito ancora da eseguire</p>
	<?php } ?>
	
<?php } ?>

<h2><?php echo gtext("Dettagli ordine", false); ?>:</h2>

<div class="uk-overflow-auto">
	<table width="100%" class="uk-table uk-table-divider uk-table-hover" cellspacing="0">
		<thead>
			<tr class="cart_head">
				<th colspan="2" align="left" class="nome_prodotto row_left"><?php echo gtext("Prodotto", false); ?></th>
				<th align="left" class="nome_prodotto"><?php echo gtext("Codice", false); ?></th>
				<th align="left" class="prezzo_prodotto"><?php echo gtext("Prezzo", false); ?> <?php if (!v("prezzi_ivati_in_carrello")) { ?> <?php echo gtext("(Iva esclusa)", false); ?><?php } ?></th>
				<th align="left" class="quantita_prodotto"><?php echo gtext("Quantità", false); ?></th>
				<th style="text-align:right;" class="subtotal_prodotto"><?php echo gtext("Totale", false); ?><?php if (!v("prezzi_ivati_in_carrello")) { ?> <?php echo gtext("(Iva esclusa)", false); ?><?php } ?></th>
			</tr>
		</thead>
		
		<?php foreach ($righeOrdine as $p) { ?>
		<tr class="cart_item_row">
			<?php if ($p["righe"]["id_p"]) { ?>
			<td width="4%" style="vertical-align:top;">-</td>
			<?php } ?>
			<td colspan="<?php if (!$p["righe"]["id_p"]) { ?>2<?php } else { ?>1<?php } ?>" class="cart_item_product row_left"><?php echo $p["righe"]["title"];?>
			<?php if (strcmp($p["righe"]["id_c"],0) !== 0) { echo "<br />".$p["righe"]["attributi"]; } ?>
			</td>
			<td style="vertical-align:top;" class="cart_item_product"><?php echo $p["righe"]["codice"];?></td>
			<td style="vertical-align:top;" class="cart_item_price">
				<?php if (isset($p["righe"]["in_promozione"]) and strcmp($p["righe"]["in_promozione"],"Y")===0){ echo "<del>€ ".setPriceReverse(p($p["righe"],$p["righe"]["prezzo_intero"]))."</del>"; } ?> &euro; <span class="item_price_single"><?php echo setPriceReverse(p($p["righe"],$p["righe"]["price"]));?></span>
				<?php if (ImpostazioniModel::$valori["mostra_scritta_iva_inclusa"] == "Y") { ?>
				<div class="scritta_iva_carrello"><?php echo gtext("Iva", false); ?>: <?php echo setPriceReverse($p["righe"]["iva"]);?> %</div>
				<?php } ?>
			</td>
			<td style="vertical-align:top;" class="cart_item_quantity"><?php echo $p["righe"]["quantity"];?></td>
			<td style="vertical-align:top;text-align:right;" class="cart_item_subtotal">&euro; <span class="item_price_subtotal"><?php echo setPriceReverse(p($p["righe"],$p["righe"]["quantity"] * $p["righe"]["price"]));?></span></td>
		</tr>
		<?php } ?>
	</table>
</div>

<br />
<p class="checkout_totali">
	<?php
	$strIvato = v("prezzi_ivati_in_carrello") ? "_ivato" : "";
	?>
	<?php echo gtext("Totale merce", false); ?>: <strong>&euro; <?php echo setPriceReverse($ordine["subtotal".$strIvato]);?></strong>
	<?php if (strcmp($ordine["usata_promozione"],"Y") === 0) { ?>
	<br /><?php echo gtext("Prezzo scontato", false); ?> (<i><?php echo $ordine["nome_promozione"];?></i>): <strong>€ <?php echo setPriceReverse($ordine["prezzo_scontato".$strIvato]);?></strong>
	<?php } ?>
	<br /><?php echo gtext("Spese spedizione", false); ?>: <strong>&euro; <?php echo setPriceReverse($ordine["spedizione".$strIvato]);?></strong>
	<?php if (!v("prezzi_ivati_in_carrello")) { ?>
	<br /><?php echo gtext("Iva", false); ?>: <strong>&euro; <?php echo setPriceReverse($ordine["iva"]);?></strong>
	<?php } ?>
	<br /><?php echo gtext("Totale ordine", false); ?>: <strong>&euro; <?php echo setPriceReverse($ordine["total"]);?></strong>
	<?php if (v("prezzi_ivati_in_carrello") && $ordine["id_iva_estera"]) { ?>
	<br /><span style="color:#999;font-style:italic;"><?php echo gtext("Di cui IVA", false); ?> (<?php echo $ordine["stringa_iva_estera"];?>): <strong>&euro; <?php echo setPriceReverse($ordine["iva"]);?></strong></span>
	<?php } ?>
</p>

<?php if (trim($ordine["note"])) { ?>
<h2><?php echo gtext("Note d'acquisto");?></h2>
<?php echo nl2br($ordine["note"]);?>
<br /><br />
<?php } ?>

<h2><?php echo gtext("Dati di fatturazione", false); ?></h2>

<div class="uk-overflow-auto">
	<table class="uk-table uk-table-divider uk-table-hover">
		<?php if (strcmp($ordine["tipo_cliente"],"privato") === 0 || strcmp($ordine["tipo_cliente"],"libero_professionista") === 0) { ?>
		<tr>
			<td class="first_column"><?php echo gtext("Nome", false); ?></td>
			<td><?php echo $ordine["nome"];?></td>
		</tr>
		<tr>
			<td class="first_column"><?php echo gtext("Cognome", false); ?></td>
			<td><?php echo $ordine["cognome"];?></td>
		</tr>
		<?php } ?>
		<?php if (strcmp($ordine["tipo_cliente"],"azienda") === 0) { ?>
		<tr>
			<td class="first_column"><?php echo gtext("Ragione sociale", false); ?></td>
			<td><?php echo $ordine["ragione_sociale"];?></td>
		</tr>
		<?php } ?>
		<?php if ($ordine["p_iva"]) { ?>
			<?php if (strcmp($ordine["tipo_cliente"],"azienda") === 0 || strcmp($ordine["tipo_cliente"],"libero_professionista") === 0) { ?>
			<tr>
				<td class="first_column"><?php echo gtext("P. IVA", false); ?></td>
				<td><?php echo $ordine["p_iva"];?></td>
			</tr>
			<?php } ?>
		<?php } ?>
		<?php if ($ordine["codice_fiscale"]) { ?>
		<tr>
			<td class="first_column"><?php echo gtext("Codice fiscale", false); ?></td>
			<td><?php echo $ordine["codice_fiscale"];?></td>
		</tr>
		<?php } ?>
		<tr>
			<td class="first_column"><?php echo gtext("Indirizzo", false); ?></td>
			<td><?php echo $ordine["indirizzo"];?></td>
		</tr>
		<?php if ($ordine["cap"]) { ?>
		<tr>
			<td class="first_column"><?php echo gtext("Cap", false); ?></td>
			<td><?php echo $ordine["cap"];?></td>
		</tr>
		<?php } ?>
		<tr>
			<td class="first_column"><?php echo gtext("Nazione", false); ?></td>
			<td><?php echo nomeNazione($ordine["nazione"]);?></td>
		</tr>
		<tr>
			<td class="first_column"><?php echo gtext("Provincia", false); ?></td>
			<td><?php echo $ordine["provincia"];?></td>
		</tr>
		<tr>
			<td class="first_column"><?php echo gtext("Città", false); ?></td>
			<td><?php echo $ordine["citta"];?></td>
		</tr>
		<tr>
			<td class="first_column"><?php echo gtext("Telefono", false); ?></td>
			<td><?php echo $ordine["telefono"];?></td>
		</tr>
		<tr>
			<td class="first_column"><?php echo gtext("Email", false); ?></td>
			<td><?php echo $ordine["email"];?></td>
		</tr>
		<?php if ($ordine["pec"]) { ?>
		<tr>
			<td class="first_column"><?php echo gtext("Pec", false); ?></td>
			<td><?php echo $ordine["pec"];?></td>
		</tr>
		<?php } ?>
		<?php if ($ordine["codice_destinatario"]) { ?>
		<tr>
			<td class="first_column"><?php echo gtext("Codice destinatario", false); ?></td>
			<td><?php echo $ordine["codice_destinatario"];?></td>
		</tr>
		<?php } ?>
	</table>
</div>

<h2><?php echo gtext("Dati di spedizione", false); ?></h2>

<div class="uk-overflow-auto">
	<table class="uk-table uk-table-divider uk-table-hover">
		<tr>
			<td class="first_column"><?php echo gtext("Indirizzo", false); ?></td>
			<td><?php echo $ordine["indirizzo_spedizione"];?></td>
		</tr>
		<?php if ($ordine["cap_spedizione"]) { ?>
		<tr>
			<td class="first_column"><?php echo gtext("Cap", false); ?></td>
			<td><?php echo $ordine["cap_spedizione"];?></td>
		</tr>
		<?php } ?>
		<tr>
			<td class="first_column"><?php echo gtext("Nazione", false); ?></td>
			<td><?php echo nomeNazione($ordine["nazione_spedizione"]);?></td>
		</tr>
		<tr>
			<td class="first_column"><?php echo gtext("Provincia", false); ?></td>
			<td><?php echo $ordine["provincia_spedizione"];?></td>
		</tr>
		<tr>
			<td class="first_column"><?php echo gtext("Città", false); ?></td>
			<td><?php echo $ordine["citta_spedizione"];?></td>
		</tr>
		<tr>
			<td class="first_column"><?php echo gtext("Telefono", false); ?></td>
			<td><?php echo $ordine["telefono_spedizione"];?></td>
		</tr>
	</table>
</div>
<br /><br />
<?php if (strcmp($tipoOutput,"mail_al_cliente") === 0 ) { ?>
<p><?php echo gtext("Può controllare in qualsiasi momento i dettagli dell'ordine al", false); ?> <a href="<?php echo $baseUrl."resoconto-acquisto/".$ordine["id_o"]."/".$ordine["cart_uid"]."/token";?>?n=y"><?php echo gtext("seguente indirizzo web", false); ?></a>.</p>
<?php } ?>

<?php
if (isset($isFromAreariservata))
	include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
?>
