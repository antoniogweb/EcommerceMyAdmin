<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Pagina non trovata") => "",
);

$titoloPagina = gtext("Pagina non trovata");

include(tpf("/Elementi/Pagine/page_top.php"));
?>
<p><a href="<?php echo $this->baseUrl;?>"><b><?php echo gtext("Vai alla home")?></b></a></p>

<?php
include(tpf("/Elementi/Pagine/page_bottom.php"));
