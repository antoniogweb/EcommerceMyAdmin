<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtextPlain("Home") 		=> $this->baseUrl,
	gtextPlain("Pagina non trovata") => "",
);

$titoloPagina = gtextPlain("Pagina non trovata");

include(tpf("/Elementi/Pagine/page_top.php"));
?>
<p><a href="<?php echo $this->baseUrl;?>"><b><?php echo gtextPlain("Vai alla home")?></b></a></p>

<?php
include(tpf("/Elementi/Pagine/page_bottom.php"));
