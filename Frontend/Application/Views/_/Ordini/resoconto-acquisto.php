<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php
if (!isset($baseUrl))
	$baseUrl = $this->baseUrl."/";
?>
<?php if (strcmp($tipoOutput,"mail_al_negozio") === 0 || strcmp($tipoOutput,"mail_al_cliente") === 0) { ?>
<h1><?php echo gtext("Resoconto dell'ordine");?></h1>
<?php } ?>

<?php
$idListaRegalo = $ordine["id_lista_regalo"];
include(tpf(ElementitemaModel::p("AVVISO_LISTA_SELEZIONATA","", array(
	"titolo"	=>	"Avviso quando hai una lista selezionata",
	"percorso"	=>	"Elementi/ListaRegalo/AvvisoCarrelloCheckout",
))));
?>

<?php if (strcmp($tipoOutput,"web") === 0) { ?>
<!--<div class="for_print">
	<a href="#" class="stampa_pagina">Stampa</a>
</div>-->
<?php } ?>

<?php include(tpf("Elementi/Ordini/resoconto_top.php"));?>

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

<?php include(tpf("Elementi/Ordini/resoconto_acquisto_dettagli_generali.php"));?>

<?php include(tpf("Elementi/Ordini/resoconto_pagamento_top.php"));?>

<?php include(tpf("Ordini/resoconto_pagamento.php"));?>

<h2 class="uk-heading-bullet"><?php echo gtext("Dettagli ordine", false); ?>:</h2>

<?php include(tpf("Ordini/resoconto_prodotti.php"));?>

<div class="uk-grid" uk-grid>
	<div class="uk-width-1-2@m"></div>
	<div class="uk-width-1-2@m">
		<table class="table uk-table uk-table-divider uk-table-hover uk-margin-remove-top uk-table-striped">
			<?php
			$scrittaFinaleTotale = "Totale ordine";
			$strIvato = v("prezzi_ivati_in_carrello") ? "_ivato" : "";
			?>
			<?php if ($ordine["da_spedire"] || $ordine["usata_promozione"] == "Y") { ?>
			<tr>
				<td class="first_column"><?php echo gtext("Totale merce", false); ?>:</td> <td class="uk-text-right"><strong>&euro; <?php echo setPriceReverse($ordine["subtotal".$strIvato]);?></strong></td>
			</tr>
			<?php } ?>
			<?php if (strcmp($ordine["usata_promozione"],"Y") === 0 && $ordine["tipo_promozione"] == "PERCENTUALE") { ?>
			<tr>
				<td class="first_column"><?php echo gtext("Prezzo scontato", false); ?> (<i><?php echo $ordine["nome_promozione"];?></i>):</td> <td class="uk-text-right"> <strong>€ <?php echo setPriceReverse($ordine["prezzo_scontato".$strIvato]);?></strong></td>
			</tr>
			<?php } ?>
			<?php if ($ordine["costo_pagamento"] > 0) { ?>
			<tr>
				<td class="first_column"><?php echo gtext("Spese pagamento", false); ?>:</td> <td class="uk-text-right"> <strong>&euro; <?php echo setPriceReverse($ordine["costo_pagamento".$strIvato]);?></strong></td>
			</tr>
			<?php } ?>
			<?php if ($ordine["da_spedire"]) { ?>
			<tr>
				<td class="first_column"><?php echo gtext("Spese spedizione", false); ?>:</td> <td class="uk-text-right"> <strong>&euro; <?php echo setPriceReverse($ordine["spedizione".$strIvato]);?></strong></td>
			</tr>
			<?php } ?>
			<?php if (!v("prezzi_ivati_in_carrello")) { ?>
			<tr>
				<td class="first_column"><?php echo gtext("Iva", false); ?>:</td> <td class="uk-text-right"> <strong>&euro; <?php echo setPriceReverse($ordine["iva"]);?></strong></td>
			</tr>
			<?php } ?>
			<?php if (strcmp($ordine["usata_promozione"],"Y") === 0 && $ordine["tipo_promozione"] == "ASSOLUTO") {
				$scrittaFinaleTotale = "Totale da pagare";
			?>
			<tr>
				<td class="first_column"><?php echo gtext("Totale ordine", false); ?>:</td> <td class="uk-text-right"> <strong>&euro; <?php echo setPriceReverse($ordine["total_pieno"]);?></strong></td>
			</tr>
			<tr>
				<td class="first_column"><?php echo gtext("Sconto coupon", false); ?> (<i><?php echo $ordine["nome_promozione"];?></i>):</td> <td class="uk-text-right"> <strong>&euro; <?php echo setPriceReverse($ordine["total"] - $ordine["total_pieno"]);?></strong></td>
			</tr>
			<?php } ?>
			<tr>
				<td class="first_column"><?php echo gtext($scrittaFinaleTotale, false); ?>:</td> <td class="uk-text-right"> <strong>&euro; <?php echo setPriceReverse($ordine["total"]);?></strong></td>
			</tr>
			<?php if (v("prezzi_ivati_in_carrello") && $ordine["id_iva_estera"] && !$ordine["nascondi_iva_estera"]) { ?>
			<tr>
				<td class="first_column"><span style="color:#999;font-style:italic;"><?php echo gtext("Di cui IVA", false); ?> (<?php echo $ordine["stringa_iva_estera"];?>):</td> <td class="uk-text-right"> <strong>&euro; <?php echo setPriceReverse($ordine["iva"]);?></strong></span></td>
			</tr>
			<?php } ?>
		</table>
	</div>
</div>

<?php if (trim($ordine["note"])) { ?>
<h2 class="uk-heading-bullet"><?php echo gtext("Note d'acquisto");?></h2>
<?php echo nl2br($ordine["note"]);?>
<br /><br />
<?php } ?>

<h2 class="uk-heading-bullet"><?php echo gtext("Dati di fatturazione", false); ?></h2>

<div class="uk-overflow-auto">
	<table class="table uk-table uk-table-divider uk-table-hover">
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
		<?php if (trim($ordine["telefono"])) { ?>
		<tr>
			<td class="first_column"><?php echo gtext("Telefono", false); ?></td>
			<td><?php echo $ordine["telefono"];?></td>
		</tr>
		<?php } ?>
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

<?php if ($ordine["da_spedire"]) { ?>
<h2 class="uk-heading-bullet"><?php echo gtext("Dati di spedizione", false); ?></h2>

<div class="uk-overflow-auto">
	<table class="table uk-table uk-table-divider uk-table-hover">
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
		<?php if (trim($ordine["telefono_spedizione"])) { ?>
		<tr>
			<td class="first_column"><?php echo gtext("Telefono", false); ?></td>
			<td><?php echo $ordine["telefono_spedizione"];?></td>
		</tr>
		<?php } ?>
		<tr>
			<td class="first_column"><?php echo gtext("Modalità di spedizione", false); ?></td>
			<td><?php echo CorrieriModel::g()->where(array("id_corriere"=>(int)$ordine["id_corriere"]))->field("titolo");?></td>
		</tr>
	</table>
</div>
<?php } ?>
<br /><br />
<?php if (strcmp($tipoOutput,"mail_al_cliente") === 0 ) { ?>
<p><?php echo gtext("Può controllare in qualsiasi momento i dettagli dell'ordine al", false); ?> <a href="<?php echo $baseUrl."resoconto-acquisto/".$ordine["id_o"]."/".$ordine["cart_uid"]."/token";?>?n=y"><?php echo gtext("seguente indirizzo web", false); ?></a>.</p>
<?php } ?>

<?php
if (isset($isFromAreariservata))
	include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
?>
