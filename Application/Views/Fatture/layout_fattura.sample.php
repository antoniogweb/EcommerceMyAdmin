<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<style type="text/Css">
.cart_table
{
	width:100%;
}
.cart_table td
{
	border-bottom:1px solid #D3D3D3;
    padding:5px;
/*     background-color: #F6F6F6; */
}
.stringa_attributi_value
{
	font-weight:bold;
	font-style:italic;
}
.stringa_attributi_title
{
	font-style:italic;
}
.cart_head td
{
	font-weight:bold;
}

.riga_informazioni
{
	margin-bottom:5px;
}
</style>

<htmlpageheader name="myHeader1">
	<table style="width:100%;padding-top:5mm;">
		<tr>
			<td><?php echo i("__LOGO_IN_FATTURA__");?></td>
			<td><?php echo t("__INTESTAZIONE_FATTURA__");?></td>
		</tr>
	</table>
</htmlpageheader>

<sethtmlpageheader name="myHeader1" value="on" show-this-page="1" />

<!--informazioni cliente-->
<div style="padding-left:100mm;">
	<div class="riga_informazioni">
		<b>Cliente:</b> 
		<?php if (strcmp($ordine["tipo_cliente"],"privato") === 0 || strcmp($ordine["tipo_cliente"],"libero_professionista") === 0) { ?>
		<b><?php echo ucfirst($ordine["nome"]);?> <?php echo ucfirst($ordine["cognome"]);?></b>
		<?php } else { ?>
		<b><?php echo ucfirst($ordine["ragione_sociale"]);?></b>
		<?php } ?>
	</div>
	<div class="riga_informazioni">
		Indirizzo: <b><?php echo $ordine["indirizzo"]."<br />".$ordine["cap"]." ".$ordine["citta"]." ".$ordine["provincia"]; ?></b>
	</div>
	<div class="riga_informazioni">
		Telefono: <b><?php echo $ordine["telefono"];?></b>
	</div>
	<div class="riga_informazioni">
		Email: <b><?php echo $ordine["email"];?></b>
	</div>
	<div class="riga_informazioni">
		C.F.: <b><?php echo $ordine["codice_fiscale"];?></b>
	</div>
	<?php if (strcmp($ordine["tipo_cliente"],"azienda") === 0 || strcmp($ordine["tipo_cliente"],"libero_professionista") === 0) { ?>
	<div class="riga_informazioni">
		P.IVA: <b><?php echo $ordine["p_iva"];?></b>
	</div>
	<?php } ?>
</div>

<!--informazioni ordine-->
<div style="margin-top:10mm;">
	<div class="riga_informazioni">
		Fattura n° <b><?php echo $numeroFattura;?>/web</b> del <b><?php echo $dataFattura;?></b>
	</div>
	<div class="riga_informazioni">
		- Rif Ordine n° <b><?php echo $ordine["id_o"];?></b> del <b><?php echo smartDate($ordine["data_creazione"]);?></b>
	</div>
	<div class="riga_informazioni">
		- Pagamento: <b><?php echo metodoPagamento($ordine["pagamento"]);?></b>
	</div>
</div>

<table style="margin-top:20mm;border:1px solid #FFF;" class="cart_table" align="left" cellspacing="0">
	
	<tr class="cart_head">
		<td align="left" style="width:15%">Codice</td>
		<td align="left" style="width:40%">Descrizione</td>
		<td align="left" style="width:15%">Quantità</td>
		<?php if (v("attiva_prezzo_fisso")) { ?>
		<td align="left" style="width:15%">Prezzo fisso</td>
		<?php } ?>
		<td align="left" style="width:15%">Prezzo</td>
		<td align="left" style="width:15%">Totale</td>
	</tr>

	<?php foreach ($righeOrdine as $p) { ?>
	<tr class="cart_item_row">
		<td style="width:15%"><?php echo $p["righe"]["codice"];?></td>
		<td style="width:40%"><?php echo htmlentitydecode($p["righe"]["title"]);?><?php if (strcmp($p["righe"]["id_c"],0) !== 0) { echo "<br />".$p["righe"]["attributi"]; } ?></td>
		<td style="width:15%"><?php echo $p["righe"]["quantity"];?></td>
		<?php if (v("attiva_prezzo_fisso")) { ?>
		<td style="width:15%"> <?php if (isset($p["righe"]["in_promozione"]) and strcmp($p["righe"]["in_promozione"],"Y")===0 && $p["righe"]["prezzo_fisso"] > 0){ echo "<del>€ ".setPriceReverse($p["righe"]["prezzo_fisso_intero"])."</del>"; } ?> &euro; <span class="item_price_single"><?php echo setPriceReverse($p["righe"]["prezzo_fisso"]);?></span></td>
		<?php } ?>
		<td style="width:15%"> <?php if (isset($p["righe"]["in_promozione"]) and strcmp($p["righe"]["in_promozione"],"Y")===0){ echo "<del>€ ".setPriceReverse($p["righe"]["prezzo_intero"])."</del>"; } ?> &euro; <span class="item_price_single"><?php echo setPriceReverse($p["righe"]["price"]);?></span></td>
		<td style="width:15%">&euro; <span class="item_price_subtotal"><?php echo setPriceReverse($p["righe"]["quantity"] * $p["righe"]["price"]);?></span></td>
	</tr>
	<?php } ?>

</table>

<!--informazioni cliente-->
<div style="margin-top:20mm;">
	<div class="riga_informazioni">
		Totale merce: <strong>&euro; <?php echo setPriceReverse($ordine["subtotal"]);?></strong>
	</div>
	<?php if (strcmp($ordine["usata_promozione"],"Y") === 0) { ?>
	<div class="riga_informazioni">
		Prezzo scontato (<i><?php echo htmlentitydecode($ordine["nome_promozione"]);?></i>): <strong>€ <?php echo setPriceReverse($ordine["prezzo_scontato"]);?></strong>
	</div>
	<?php } ?>
	<?php if ($ordine["costo_pagamento"] > 0) { ?>
	<div class="riga_informazioni">
		Spese pagamento: <strong>&euro; <?php echo setPriceReverse($ordine["costo_pagamento"]);?></strong>
	</div>
	<?php } ?>
	<div class="riga_informazioni">
		Spese spedizione: <strong>&euro; <?php echo setPriceReverse($ordine["spedizione"]);?></strong>
	</div>
	<div class="riga_informazioni">
		Totale IVA: <strong>&euro; <?php echo setPriceReverse($ordine["iva"]);?></strong>
	</div>
	<div style="padding-top:15px" class="riga_informazioni">
		Totale: <strong>&euro; <?php echo setPriceReverse($ordine["total"]);?></strong>
	</div>
</div>

<?php if (!$ordine["pec"] && (!$ordine["codice_destinatario"] || $ordine["codice_destinatario"] == "0000000")) { ?>

<?php } else { ?>
<div style="margin-top:10mm;">
	Fattura non valida ai fini fiscali. La fattura elettronica verrà emessa elettronicamente secondo i termini di legge.
</div>
<?php } ?>
