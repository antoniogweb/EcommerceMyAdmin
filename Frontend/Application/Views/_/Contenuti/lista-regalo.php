<?php if (!defined('EG')) die('Direct access not allowed!');

$breadcrumb = array(
	gtext("Home") 						=> $this->baseUrl,
	gtext("Lista ").$lista["liste_regalo"]["titolo"] 	=> "",
);

$titoloPagina = gtext("Lista")." ".$lista["liste_regalo"]["titolo"];
$sottotitoloPagina = gtext("Lista")." ".gtext(strtolower($lista["liste_regalo_tipi"]["titolo"]));

include(tpf("/Elementi/Pagine/page_top.php")); ?>

<div class="uk-text-left" uk-grid>
	<div class="uk-width-1-1 uk-width-1-5@s">
		aa
	</div>
	<div class="uk-width-expand">
		<?php include(tpf(ElementitemaModel::p("LISTA_REGALO_PRODOTTI","", array(
			"titolo"	=>	"Prodotti da acquistare di una lista regalo",
			"percorso"	=>	"Elementi/ListaRegalo/ElencoProdotti",
		))));?>
	</div>
</div>

<?php include(tpf("/Elementi/Pagine/page_bottom.php"));
