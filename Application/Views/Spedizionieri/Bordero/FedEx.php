<?php if (!defined('EG')) die('Direct access not allowed!');
$nomeCliente = $this->getParam("ragione_sociale_cliente");
$codiceContratto = $this->getParam("codice_contratto");
$data = $record["data_elaborazione"] ?? date("Y-m-d H:i:s");
?>
<html>
<head>
<style>
body {
    font-family: Serif,sans-serif;

}
table thead th {
    text-align: left;
}
table.corpo {
    border-collapse: collapse;
/*     font-size:10px; */
}
table.corpo th, table.corpo td {
    font-size:12px;
    text-align: left;
/*     border: 1px solid #444; */
/*     border-bottom:3px solid #444; */
    vertical-align:top;
    margin: 0px;padding:1mm;
}
table.corpo td table td
{
	border: none;
	margin: 0px;
	padding:0px;
}

.header_tabella th
{
	font-weight:normal;
	border-bottom:0.1mm solid 000;
	padding-bottom:2mm;
}
.riga_tabella td
{
/* 	border-bottom:0.1mm solid 000; */
	padding-bottom:2mm;
	padding-top:2mm;
}
.riga_tabella_totali td
{
	padding-bottom:5mm;
	padding-top:5mm;
}
</style>
</head>
<body>
	<div style="font-size:22px;"><?php echo gtext("General Logistic Systems Italy S.p.A.");?>
		<p style="text-align:center;"><?php echo gtext("Distinta spedizioni del");?> <?php echo date("d/m/Y H:i:s",strtotime($data));?></p>
		<div>
			<?php echo gtext("Sede di appartenenza");?>: <?php echo $this->getParam("codice_sede");?><br />
			<?php echo gtext("Cliente");?>: <?php echo $nomeCliente;?>
		</div>
	</div>
	<p style="font-size:16px;"><?php echo gtext("Codici cliente");?>: <?php echo $codiceContratto;?></p>
	
	<table class="corpo" style="width:297mm;">
		<tr class="header_tabella">
			<th><?php echo gtext("Data");?></th>
			<th><?php echo gtext("N° Sped.");?></th>
			<th><?php echo gtext("Destinatario");?></th>
			<th><?php echo gtext("Località");?></th>
			<th><?php echo gtext("Indirizzo");?></th>
			<th><?php echo gtext("Prov");?></th>
			<th><?php echo gtext("ZipCode");?></th>
			<th><?php echo gtext("Colli");?></th>
			<th><?php echo gtext("Peso");?></th>
			<th><?php echo gtext("Codice cliente");?></th>
		</tr>
		<?php
		$spnModel = new SpedizioninegozioModel();
		$spnsModel = new SpedizioninegozioserviziModel();
		
		$numeroSpedizioni = 0;
		$numeroColliTotali = 0;
		$pesoTotale = 0;
		$numeroContrassegno = 0;
		$totaleContrassegno = 0;
		$numeroAssicurazione1010 = 0;
		$numeroAssicurazioneALLIN = 0;
		$totaleAssicurazione1010 = 0;
		$totaleAssicurazioneALLIN = 0;
		$presentiAccessori = false;
		
		foreach ($spedizioni as $riga) {
			$spedizione = htmlentitydecodeDeep($riga["spedizioni_negozio"]);
			$titoloProvincia = ProvinceModel::g(false)->findTitoloDaCodice($spedizione["provincia"]);
			
			$serviziAccessori = $spnsModel->gServiziSpedizione((int)$spedizione["id_spedizione_negozio"]);
			
			$peso = $spnModel->peso([(int)$spedizione["id_spedizione_negozio"]]);
			$pesoTotale += $peso;
			
			$colli = $spnModel->getColli([(int)$spedizione["id_spedizione_negozio"]], true);
			$numeroColliTotali += $colli;
			
			$numeroSpedizioni++;
			
			if ($spedizione["contrassegno"] > 0)
			{
				$numeroContrassegno++;
				$totaleContrassegno += $spedizione["contrassegno"];
			}
			
			if ($spedizione["assicurazione_integrativa"] == "A")
			{
				$numeroAssicurazioneALLIN++;
				$totaleAssicurazioneALLIN += $spedizione["importo_assicurazione"];
			}
			
			if ($spedizione["assicurazione_integrativa"] == "F")
			{
				$numeroAssicurazione1010++;
				$totaleAssicurazione1010 += $spedizione["importo_assicurazione"];
			}
			
			if (count($serviziAccessori) > 0)
				$presentiAccessori = true;
			?>
			<tr class="riga_tabella">
				<td><?php echo date("Y/m/d", strtotime($spedizione["data_spedizione"]));?></td>
				<td><?php echo $this->getParam("codice_sede").$spedizione["numero_spedizione"];?></td>
				<td><?php echo $spedizione["ragione_sociale"];?></td>
				<td><?php echo $spedizione["citta"];?></td>
				<td><?php echo $spedizione["indirizzo"];?></td>
				<td><?php echo $spedizione["provincia"];?></td>
				<td><?php echo $spedizione["cap"];?></td>
				<td><?php echo $colli;?></td>
				<td><?php echo number_format($peso,1,",",".");?></td>
				<td><?php echo $spedizione["codice_cliente"];?></td>
			</tr>
		<?php } ?>
	</table>
	
	<div style="font-size:18px;">
	<br />
	<?php echo gtext("Totale spedizioni");?>: <?php echo $numeroSpedizioni;?><br />
	<?php echo gtext("Totale colli");?>: <?php echo $numeroColliTotali;?><br />
	<?php echo gtext("Totale peso reale");?>: <?php echo number_format($pesoTotale,1,",",".");?><br />
	</div>
</body>
</html>
