<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<style>
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

<?php
$idHelp = 0;
foreach ($elementi as $hdv) { ?>
	
	<?php if ($hdv["help"]["id_help"] != $idHelp) {
		$idHelp = $hdv["help"]["id_help"];
	?>
	<h1><?php echo htmlentitydecode($hdv["help"]["titolo"]);?></h1>
	<?php } else { ?>
	<h2><?php echo htmlentitydecode($hdv["help_item"]["titolo"]);?></h2>
	<?php } ?>
	<?php
		$desc = preg_replace('/(<[^>]+) style=".*?"/i', '$1', htmlentitydecode($hdv["help_item"]["descrizione"]));
	?>
	
	<div><?php echo $desc;?></div>
<?php } ?>
