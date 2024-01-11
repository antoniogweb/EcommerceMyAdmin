<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
	gtext("Gestione crediti") => "",
);

$titoloPagina = gtext("Gestione crediti");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "crediti";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>

<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
