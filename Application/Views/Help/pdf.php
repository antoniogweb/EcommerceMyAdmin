<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<style>

@page {
	size: auto;
	footer: myFooter1;
}

.h1, .h2
{
	font-size:13px;
	padding:0.5mm;
	font-weight:bold;
	text-align:center;
	background-color:#EEE;
	display:block;
	border-top:0.1mm solid #333;
	border-bottom:0.1mm solid #333;
}

.h2
{
	font-size:13px;
}

img
{
	background-color:#EEE;
	padding:15px;
}
</style>


<htmlpagefooter name="myFooter1" style="display:none">
	<table width="100%">
		<tr>
			<td width="33%">
				<?php echo gtext("Scaricato il");?> <span style="font-weight: bold;o">{DATE d/m/Y}</span>
			</td>
			<td width="33%" align="center" style="font-weight: bold; font-style: italic;">
				{PAGENO}/{nbpg}
			</td>
			<td width="33%" style="text-align: right;">
				Guida ecommerce <b><?php echo Parametri::$nomeNegozio;?></b>
			</td>
		</tr>
	</table>
</htmlpagefooter>

<?php
$idHelp = 0;
$idCiclo = 0;
foreach ($elementi as $hdv) { ?>
	
	<?php if ($hdv["help"]["id_help"] != $idHelp) {
		$idHelp = $hdv["help"]["id_help"];
	?>
	<h1 style="margin-bottom:5mm;<?php if ($idCiclo) { echo "margin-top:10mm;";}?>"><?php echo htmlentitydecode($hdv["help"]["titolo"]);?></h1>
	<?php } else { ?>
	<h2><?php echo htmlentitydecode($hdv["help_item"]["titolo"]);?></h2>
	<?php } ?>
	<?php
		$desc = preg_replace('/(<[^>]+) style=".*?"/i', '$1', htmlentitydecode($hdv["help_item"]["descrizione"]));
	?>
	
	<div><?php echo $desc;?></div>
<?php $idCiclo++; } ?>
