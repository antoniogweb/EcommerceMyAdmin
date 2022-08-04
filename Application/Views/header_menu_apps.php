<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php
if (!isset($tipoMenu))
	$tipoMenu = "ecommerce";

if (defined("APPS")) {
	foreach (APPS as $app)
	{
		$path = ROOT."/Application/Apps/".ucfirst($app)."/Menu/$tipoMenu.php";
		
		if (file_exists($path))
			include($path);
	}
} ?>
