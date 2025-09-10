<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
	gtext("Gestione notifiche") => "",
);

$titoloPagina = gtext("Gestione notifiche");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "notifiche";

include(tpf("/Elementi/Pagine/riservata_top.php")); ?>
<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
