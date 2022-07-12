<?php if (!defined('EG')) die('Direct access not allowed!');?>
<?php if (trim($testo["valore"])) { ?>
<?php
$t = strcmp($tags,"") !== 0 ? strip_tags(htmlentitydecode($testo["valore"]),$tags) : htmlentitydecode($testo["valore"]);

if ($testo["attributi"] && !$testo["tag_elemento"])
	$testo["tag_elemento"] = "div";

if ($testo["tag_elemento"])
	echo "<".$testo["tag_elemento"]." ".htmlentitydecode($testo["attributi"]).">";
	
echo $t;
	
if ($testo["tag_elemento"])
	echo "</".$testo["tag_elemento"].">";
?>
<?php } ?>
