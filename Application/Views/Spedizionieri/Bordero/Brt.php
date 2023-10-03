<?php if (!defined('EG')) die('Direct access not allowed!');
$codiceCliente = $this->params["codice_cliente"];
$codiceBolla = 1;
$data = $record["data_elaborazione"];
?>
<html>
<head>
<style>
table thead th {
    text-align: left;
}
table.corpo {
    border-collapse: collapse;
}
table.corpo th, table.corpo td {
    font-size:12px;
    text-align: left;
    vertical-align:top;
    margin: 0px;
    padding-right:1mm;
}
table.corpo th
{
	 font-size:13px;
}
table.corpo td table td
{
	border: none;
	margin: 0px;
	padding:0px;
}

.header_tabella th
{
	padding-bottom:3mm;
}
.header_tabella_2 th
{
	border-bottom:0.1mm dashed 000;
	padding-bottom:3mm;
}
.riga_corpo_tabella td
{
	padding-top:3mm;
}
.riga_corpo_tabella_2 td
{
	border-bottom:0.1mm dashed 000;
	padding-top:3mm;
	padding-bottom:3mm;
}
.riga_corpo_tabella_totali td
{
	padding-bottom:5mm;
	padding-top:5mm;
}
</style>
</head>
<body>
	<table style="width:297mm;font-size:16px;">
		<tr>
			<td style="width:99mm;"><b><?php echo gtext("Mittente");?> <?php echo $codiceCliente;?> <?php echo $this->params["ragione_sociale_cliente"];?></b></td>
			<td style="width:99mm;text-align:center;"><b><?php echo gtext("Borderò BRT");?></b> <b><?php echo gtext("del");?></b> <?php echo date("d/m/Y H:i",strtotime($data));?> </td>
			<td style="width:99mm;text-align:right;"><b><?php echo gtext("Data stampa");?></b>&nbsp;&nbsp;&nbsp;<?php echo date("d/m/Y H:i");?></td>
		</tr>
	</table>
	<br />
	<hr />
	<table class="corpo" style="width:297mm;">
		<tr class="header_tabella">
			<th style="width:65mm;"><?php echo gtext("Destinatario");?></th>
			<th></th>
			<th style="width:65mm;">
				<?php echo gtext("Indirizzo");?>
			</th>
			<th>
				<?php echo gtext("Rif. Numerico");?>
			</th>
			<th align="center"><?php echo gtext("Cod");?></th>
			<th><?php echo gtext("Importo");?></th>
			<th><?php echo gtext("T.I.");?></th>
			<th><?php echo gtext("Importo");?></th>
			<th align="center"><?php echo gtext("Colli");?></th>
			<th align="center"><?php echo gtext("Peso");?></th>
			<th><?php echo gtext("MC Pallet");?></th>
			<th>
				<?php echo gtext("Segnacolli");?>
			</th>
		</tr>
		<tr class="header_tabella header_tabella_2">
			<th></th>
			<th></th>
			<th style="width:65mm;">
				<?php echo gtext("CAP");?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo gtext("Località");?>
			</th>
			<th>
				Riferimento
			</th>
			<th align="center">Bol</th>
			<th>Assic</th>
			<th>c/a</th>
			<th>C/Assegno</th>
			<th></th>
			<th></th>
			<th></th>
			<th>
				<?php echo gtext("Dal");?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo gtext("Al");?>
			</th>
		</tr>
		<?php
		$numeroColliTotali = 0;
		$pesoTotale = 0;
		$spnModel = new SpedizioninegozioModel();
		
		foreach ($spedizioni as $riga) {
			$spedizione = htmlentitydecodeDeep($riga["spedizioni_negozio"]);
			$titoloProvincia = ProvinceModel::g(false)->findTitoloDaCodice($spedizione["provincia"]);
			$codiceBolla = 1;
			
			list($fromSegnacolli, $toSegnacolli) = $this->getCodiciFromToSegnacolli($spedizione["id_spedizione_negozio"]);
			
			if ($titoloProvincia)
				$titoloProvincia = $titoloProvincia." (".$spedizione["provincia"].")";
			else
				$titoloProvincia = $spedizione["provincia"];
			
			$peso = $spnModel->peso([(int)$spedizione["id_spedizione_negozio"]]);
			$pesoTotale += $peso;
			
			$colli = $spnModel->getColli([(int)$spedizione["id_spedizione_negozio"]], true);
			$numeroColliTotali += $colli;
			
		?>
		<tr class="riga_corpo_tabella">
			<td>
				<?php echo $spedizione["ragione_sociale"];?> <?php echo $spedizione["ragione_sociale_2"];?>
			</td>
			
			<td></td>
			<td><?php echo $spedizione["indirizzo"];?></td>
			<td>
				<?php echo $spedizione["riferimento_mittente_numerico"];?>
			</td>
			<td align="center"><?php echo $codiceBolla;?></td>
			<td><?php echo $spedizione["importo_assicurazione"] > 0 ? number_format($record["importo_assicurazione"],2,",","") : "";?></td>
			<td></td>
			<td><?php echo $spedizione["contrassegno"] > 0 ? number_format($record["contrassegno"],2,",","") : "";?></td>
			<td align="center"><?php echo $colli;?></td>
			<td align="center"><?php echo number_format($peso,1,",",".");?></td>
			<td></td>
			<td>
				<?php echo $fromSegnacolli;?>
				<?php echo $toSegnacolli;?>
			</td>
		</tr>
		<tr class="riga_corpo_tabella riga_corpo_tabella_2">
			<td align="right">
				<?php echo gtext("Tipo Servizio");?> <b><?php echo $spedizione["tipo_servizio"] ? $spedizione["tipo_servizio"] : "C";?></b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</td>
			
			<td>Cod.Tar. <b><?php echo $spedizione["codice_tariffa"];?></b>&nbsp;&nbsp;&nbsp;</td>
			<td><?php echo $spedizione["cap"];?> <?php echo $spedizione["citta"];?> <?php echo $titoloProvincia;?> <?php echo $spedizione["nazione"];?></td>
			<td>
				<?php echo $spedizione["riferimento_mittente_alfa"];?>
			</td>
			<td></td>
			<td></td>
			<td></td>
			<td><?php echo $spedizione["contrassegno"] > 0 ? $spedizione["codice_pagamento_contrassegno"] : "";?></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<?php } ?>
		<tr class="riga_corpo_tabella_totali">
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td class="border_bottom"><b>Totali</b></td>
			<td class="border_bottom"></td>
			<td class="border_bottom"></td>
			<td class="border_bottom"></td>
			<td align="center" class="border_bottom"><b><?php echo $numeroColliTotali;?></b></td>
			<td align="center" class="border_bottom"><b><?php echo number_format($pesoTotale,1,",",".");?></b></td>
			<td class="border_bottom"></td>
			<td style="text-align:right;" class="border_bottom"><b><?php echo gtext("Spedizioni");?>: <?php echo count($spedizioni);?></b></td>
		</tr>
	</table>
	
</body>
</html>

