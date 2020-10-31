<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (defined("APPS")) {
	foreach (APPS as $app)
	{
		$path = ROOT."/Application/Apps/".ucfirst($app)."/Views/".ucfirst($section)."/";
		
		if (file_exists($path."form.php"))
			include($path."form.php");
	}
} ?>
