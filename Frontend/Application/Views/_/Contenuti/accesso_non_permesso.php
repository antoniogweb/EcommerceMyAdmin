<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtextPlain("Home") 		=> $this->baseUrl,
	gtextPlain("Accesso non permesso") => "",
);

$titoloPagina = gtextPlain("Accesso non permesso");

include(tpf("/Elementi/Pagine/page_top.php"));
?>
<p><b><?php echo gtextPlain("Siamo spiacenti, non può accedere alla pagina richiesta")?></b></a></p>

<?php
include(tpf("/Elementi/Pagine/page_bottom.php"));
