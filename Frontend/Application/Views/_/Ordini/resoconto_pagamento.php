<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
// if (strcmp($ordine["stato"],"pending") === 0)
if (OrdiniModel::isStatoPending($ordine["stato"]))
{
	foreach (OrdiniModel::$pagamenti as $codPag => $descPag)
	{
		if ($ordine["pagamento"] != $codPag)
			continue;
		
		if (file_exists(tpf("Elementi/Pagamenti/".$codPag."_resoconto.php")))
			include(tpf("Elementi/Pagamenti/".$codPag."_resoconto.php"));
		else
			include(tpf("Elementi/Pagamenti/pagamento_generico_resoconto.php"));
	}
} ?>
