<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<style>

/*@page {
	margin-top: 50mm;
	margin-bottom: 20mm;
	margin-left: 6mm;
	margin-right: 6mm;
	header-name: html_myHeader;
}*/

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

.dati_aziendali
{
	border-bottom:0.1mm solid #333;
	border-right:0.1mm solid #333;
}

.dati_aziendali td
{
	border-left:0.1mm solid #333;
	border-top:0.1mm solid #333;
	padding:1mm;
}
.td_titolo
{
	background-color:#EEE;
}
table
{
	border-collapse: collapse;
}
p:last-of-type, .last { margin-bottom: 0 !important; padding-bottom: 0 !important;} 

<!--
/*.table
{
	width:100%;
}*/
td
{
	padding:5px;
}
-->
</style>

<?php foreach ($pages as $p) { ?>
	<h2><?php echo htmlentitydecode(cfield($p, "title"));?></h2>
	<h1><?php echo htmlentitydecode(field($p, "title"));?></h1>
	
	<?php if (strcmp($p["pages"]["immagine"],"") !== 0) { ?>
	<img src="<?php echo ROOT."/images/contents/".$p["pages"]["immagine"];?>" />
	<br /><br />
	<?php } ?>

	<?php
		$desc = preg_replace('/(<[^>]+) style=".*?"/i', '$1', htmlentitydecode(field($p, "description")));
	?>
	<br />
	<div><?php echo $desc;?></div>
<?php } ?>
