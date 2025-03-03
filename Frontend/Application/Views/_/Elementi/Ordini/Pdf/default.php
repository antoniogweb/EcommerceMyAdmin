<?php if (!defined('EG')) die('Direct access not allowed!');
	Pdf::$params["margin_left"] = "8";
	Pdf::$params["margin_right"] = "8";
	Pdf::$params["margin_bottom"] = "62";
	Pdf::$params["margin_top"] = "90";
	Pdf::$params["margin_header"] = "8";
	Pdf::$params["margin_footer"] = "8";
?> 

<style type="text/Css">
.box_header, .testata
{
	border: 0.5mm solid #CCC;
	padding:0 3mm;
	text-align:center;
	width:100%;
	display:block;
}
.testata
{
	margin-top:1mm;
}

.box_header_item, .testata_item
{
	padding:1mm 0;
}

.testata_item
{
	text-align:left;
	font-size:13px;
}

.box_header_item_linea, .testata_item_linea
{
	border-bottom: 0.5mm solid #CCC;
}

.header_left
{
	width:55%;
	float:left;
}

.header_right
{
	width:44%;
	float:right;
}

.testata_item_left
{
	width:55%;
	float:left;
}

.testata_item_right
{
	width:45%;
	float:left;
}

.medio
{
	font-size: 14px;
}

.piccolo
{
	font-size: 12px;
}
.molto_piccolo
{
	font-size: 11px;
}
.grigio
{
	color:#AAA;
}

.centrato
{
	text-align:center;
}

.box_link_sito
{
	margin-top:1mm;
}

.link_sito
{
	color:#e30613;
	text-decoration:none;
	font-size:12px;
	
	font-style:italic;
	font-weight:bold;
}

.corpo
{
	margin-top:1mm;
}
.corpo_intestazione
{
	background-color:#000;
	color:#FFF;
	padding:0.5mm 1mm;
}
.corpo_righe
{
	margin-top:0.5mm;
	border-top: 0.5mm solid #CCC;
	border-left: 0.5mm solid #CCC;
	border-right: 0.5mm solid #CCC;
	height: 145mm;
}
.corpo_left_inner
{
	height: 143mm;
	border-left: 0.5mm solid #CCC;
}
.bottom_righe
{
	border-top: 0.5mm solid #CCC;
	border-bottom: 0.5mm solid #CCC;
	border-left: 0.5mm solid #CCC;
	border-right: 0.5mm solid #CCC;
	padding:1mm;
	height:5mm;
}
.corpo_righe_ordine
{
	padding:1mm;
}

.footer_left, .footer_right
{
	width:50%;
	float:left;
	
}
.box_footer
{
	border: 0.5mm solid #CCC;
	padding:0 3mm;
	margin-top:1mm;
}
.box_footer_right
{
	
}
.box_footer_item_left
{
	width:65%;
	float:left;
}
.box_footer_item_right
{
	width:30%;
	float:right;
	text-align:right;
}

.box_footer_item
{
	padding:0.5mm 0;
}

.box_footer_linea
{
	border-bottom: 0.2mm solid #CCC;
}

.corpo_left
{
	width:87%;
	float:left;
}

.corpo_right
{
	width:12%;
	float:right;
/* 	border-left: 0.2mm solid #CCC; */
}

.bottom_righe_left
{
	width:50%;
	float:left;
}

.bottom_righe_right
{
	width:49%;
	float:right;
	text-align:right;
}

.text-center
{
	text-align:center;
}
.text-right
{
	text-align:right;
}
.corpo_codice, .corpo_descrizione, .corpo_um, .corpo_qta, .corpo_sconto, .corpo_consegna
{
	float:left;
}
.corpo_codice
{
	width:20%;
}
.corpo_descrizione
{
	width:69%;
}
.corpo_um
{
	width:5%;
}
.corpo_qta
{
	width:5%;
}
.corpo_sconto
{
	width:10%;
}
.corpo_consegna
{
	width:10%;
}
.corpo_riga_dinamica
{
	margin-bottom:1mm;
}
.footer_text
{
	padding-top:1.5mm;
}
.uk-table
{
	width:100%;
	line-height:2em;
}
.uk-table td
{
	font-size: 14px;
	border-bottom: 0.2mm solid #CCC;
}
.uk-table tr.riga_totale_finale td
{
	font-weight:bold;
	border-bottom: none;
}
</style>

<htmlpageheader name="myHeader1">
	<div class="header">
		<div class="header_left">
			<?php echo i("__LOGO_IN_TESTATA_PDF__");?>
			<div class="box_link_sito">
				
			</div>
		</div>
		<div class="header_right">
			<div class="box_header">
				<div class="box_header_item box_header_item_linea">
					<div class="nero medio"><b><?php echo $ordine["id_o"];?></b></div>
					<div class="grigio piccolo"><?php echo gtext("numero ordine");?></div>
				</div>
				<div class="box_header_item box_header_item_linea">
					<div class="nero medio"><b><?php echo date("d/m/Y", strtotime($ordine["data_creazione"]));?></b></div>
					<div class="grigio piccolo"><?php echo gtext("data ordine");?></div>
				</div>
				<?php if (v("mostra_modalita_spedizione_in_resoconto")) { ?>
				<div style="height:10mm;" class="box_header_item">
					<div class="nero piccolo">
						<?php $modalitaSpedizione = CorrieriModel::g()->where(array("id_corriere"=>(int)$ordine["id_corriere"]))->field("titolo");?>
						<?php if ($modalitaSpedizione) { ?>
							<?php echo gtext($modalitaSpedizione);?>
						<?php } ?>
					</div>
					<div class="grigio piccolo"><?php echo gtext("consegna");?></div>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="testata">
		<div class="testata_item testata_item_linea">
			<?php echo OrdinipdfModel::getNominativo($ordine);?>
		</div>
		<div class="testata_item testata_item_linea testata_item_left">
			<?php echo htmlentitydecode($ordine["indirizzo"]);?>
		</div>
		<div class="testata_item testata_item_linea testata_item_right">
			<b><?php echo gtext("Tel");?>:</b> <?php echo $ordine["telefono"];?>
		</div>
		<div class="testata_item testata_item_left">
			<b><?php echo gtext("Località");?>:</b> <?php echo $ordine["citta"];?>
			<?php if ($ordine["provincia"]) { ?>(<?php echo $ordine["provincia"];?>)<?php } ?>
			<?php if ($ordine["cap"]) { ?>, <?php echo $ordine["cap"];?><?php } ?>
		</div>
		<div class="testata_item testata_item_right">
			<b><?php echo gtext("Email");?>:</b> <?php echo $ordine["email"];?>
		</div>
	</div>
	<div class="testata">
		<div style="height:10mm;" class="testata_item">
			<b><?php echo gtext("Spedizione");?>:</b>
			<?php if ($ordine["da_spedire"] && ($ordine["indirizzo_spedizione"] || $ordine["citta_spedizione"])) { ?>
				<span class="uk-text-emphasis"><?php if ($ordine["indirizzo_spedizione"]) { ?><?php echo $ordine["indirizzo_spedizione"];?>
				<?php echo $ordine["cap_spedizione"];?>, <?php echo $ordine["citta_spedizione"];?> (<?php echo $ordine["nazione_spedizione"] == "IT" ? $ordine["provincia_spedizione"] : $ordine["dprovincia_spedizione"];?>)<?php } ?>
				<?php if ($ordine["nazione_spedizione"]) { ?>
				<span class="uk-text-emphasis"><?php echo nomeNazione($ordine["nazione_spedizione"]);?>
				<?php } ?>
				<?php } ?>
				<?php if (trim($ordine["telefono_spedizione"])) { ?>
			<span class="uk-text-emphasis"><?php echo gtext("Tel");?>:</span> <?php echo $ordine["telefono_spedizione"];?><br />
			<?php } ?>
			<?php if (trim($ordine["destinatario_spedizione"])) { ?>
				<span class="uk-text-emphasis"><b><?php echo gtext("Destinatario");?>:</b></span> <?php echo $ordine["destinatario_spedizione"];?><br />
			<?php } ?>
		</div>
	</div>
	<div class="corpo">
		<div class="corpo_intestazione">
			<div class="corpo_left">
				<div class="corpo_codice">
					<?php echo gtext("Cod. Articolo");?>
				</div>
				<div class="corpo_descrizione">
					<?php echo gtext("Descrizione");?>
				</div>
				<div class="corpo_um">
					<?php echo gtext("UM");?>
				</div>
				<div class="corpo_qta">
					<?php echo gtext("Q.tà");?>
				</div>
			</div>
			<div class="corpo_right">
				<div class="text-center"><?php echo gtext("Importo");?></div>
			</div>
		</div>
		<div class="corpo_righe">
			<div class="corpo_left">
				
			</div>
			<div class="corpo_right corpo_left_inner">
				
			</div>
		</div>
	</div>
</htmlpageheader>

<sethtmlpageheader name="myHeader1" value="on" show-this-page="1" />

<htmlpagefooter name="myFooter1" >
	<div class="bottom_righe">
		<b><?php echo gtext("Testo generico footer");?></b>
	</div>
	<div class="footer">
		<div class="footer_left">
			<div class="footer_text" style="margin-top:3mm;"><b><?php echo gtext("Ha accettato le condizioni della privacy e le condizioni di vendita consultabili nel nosto sito web.");?></b></div>
		</div>
		<div class="footer_right" >
			<div class="box_footer" style="height:50mm;">
				<div style="padding-top:2mm;">
					<?php
					include(tpf(ElementitemaModel::p("RESOCONTO_TOTALI","", array(
						"titolo"	=>	"Totali ordine",
						"percorso"	=>	"Elementi/Ordini/Resoconto/Totali",
					))));
					?>
				</div>
			</div>
		</div>
	</div>
</htmlpagefooter>

<sethtmlpagefooter name="myFooter1" value="on" show-this-page="1" />

<div class="corpo_righe_ordine">
	<?php foreach ($strutturaProdotti["righe"] as $riga) {
		if ($riga["id_riga_tipologia"] && $riga["id_riga_tipologia"] != 3)
			continue;
		
		if (isset($riga["riga_accessoria"]))
			continue;
	?>
	<div class="corpo_left">
		<div class="corpo_codice corpo_riga_dinamica">
			<?php echo $riga["codice"];?>
		</div>
		<div class="corpo_descrizione corpo_riga_dinamica">
			<?php echo $riga["titolo"];?>
			<?php if ($riga["attributi"]) { ?>
			<br /><?php echo $riga["attributi"];?>
			<?php } ?>
		</div>
		<div class="corpo_um" corpo_riga_dinamica>
			PZ
		</div>
		<div class="corpo_qta corpo_riga_dinamica">
			<?php echo $riga["quantity"];?>
		</div>
	</div>
	<div class="corpo_right corpo_riga_dinamica">
		<div class="text-right"><?php echo setPriceReverse($riga["prezzo_finale_ivato"]);?></div>
	</div>
	<?php } ?>
</div>
