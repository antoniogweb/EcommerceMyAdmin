<?php if (!defined('EG')) die('Direct access not allowed!');

$titoloPagina = mfield($marchioCorrente, "titolo");

$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
);

if (isset($tipiPagina["MARCHI"]))
{
	$dettagliPagina = PagesModel::getPageDetails($tipiPagina["MARCHI"]);
	
	if ($dettagliPagina)
		$breadcrumb[field($dettagliPagina, "title")] = getUrlAlias($dettagliPagina["pages"]["id_page"]);
}

$breadcrumb[$titoloPagina] = "";

$noNumeroProdotti = true;
include(tpf("/Elementi/Pagine/page_top.php"));
?>
<div class="uk-text-left">
	<?php echo htmlentitydecode(attivaModuli(mfield($marchioCorrente, "descrizione")));?>
</div>
<?php
include(tpf("/Elementi/Pagine/page_bottom.php"));
?>
