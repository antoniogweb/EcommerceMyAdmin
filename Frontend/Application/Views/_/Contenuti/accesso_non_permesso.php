<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Accesso non permesso") => "",
);

$titoloPagina = gtext("Accesso non permesso");

include(tpf("/Elementi/Pagine/page_top.php"));
?>
<p><b><?php echo gtext("Siamo spiacenti, non può accedere alla pagina richiesta")?></b></a></p>

<?php
include(tpf("/Elementi/Pagine/page_bottom.php"));
