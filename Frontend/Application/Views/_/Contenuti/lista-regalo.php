<?php if (!defined('EG')) die('Direct access not allowed!');

$breadcrumb = array(
	gtext("Home") 						=> $this->baseUrl,
	gtext("Lista ").$lista["liste_regalo"]["titolo"] 	=> "",
);

$titoloPagina = gtext("Lista")." ".$lista["liste_regalo"]["titolo"];
$sottotitoloPagina = gtext("Lista")." ".gtext(strtolower($lista["liste_regalo_tipi"]["titolo"]));

include(tpf("/Elementi/Pagine/page_top.php")); ?>

<div class="uk-text-left" uk-grid>
	<div class="uk-width-1-1 uk-width-1-5@s uk-text-left">
		<dl class="uk-description-list">
			<dt><?php echo gtext("Dettagli lista");?></dt>
		<dl/>
		
		<div class="uk-grid uk-grid-small uk-child-width-1-2" uk-grid>
			<div class="uk-text-small"><?php echo gtext("Nome");?>:</div> <div class="uk-text-small uk-text-emphasis"><?php echo gtext($lista["liste_regalo"]["titolo"]);?></div>
		</div>
		<div class="uk-grid uk-margin-remove-top uk-grid-small uk-child-width-1-2" uk-grid>
			<div class="uk-text-small"><?php echo gtext("Tipo");?>:</div> <div class="uk-text-small uk-text-emphasis"><?php echo gtext($lista["liste_regalo_tipi"]["titolo"]);?></div>
		</div>
		<div class="uk-grid uk-margin-remove-top uk-grid-small uk-child-width-1-2" uk-grid>
			<div class="uk-text-small"><?php echo gtext("Codice");?>:</div> <div class="uk-text-small uk-text-emphasis"><?php echo gtext($lista["liste_regalo"]["codice"]);?></div>
		</div>
		<?php if(in_array($lista["liste_regalo_tipi"]["id_lista_tipo"], ListeregalotipiModel::campoPresenteInTipi("nome_bambino", ""))) { ?>
		<div class="uk-grid uk-margin-remove-top uk-grid-small uk-child-width-1-2" uk-grid>
			<div class="uk-text-small"><?php echo gtext("Nome bimbo/a");?>:</div> <div class="uk-text-small uk-text-emphasis"><?php echo gtext($lista["liste_regalo"]["nome_bambino"]);?></div>
		</div>
		<?php } ?>
		<?php if(in_array($lista["liste_regalo_tipi"]["id_lista_tipo"], ListeregalotipiModel::campoPresenteInTipi("genitore_1", ""))) { ?>
		<div class="uk-grid uk-margin-remove-top uk-grid-small uk-child-width-1-2" uk-grid>
			<div class="uk-text-small"><?php echo gtext("Genitore 1");?>:</div> <div class="uk-text-small uk-text-emphasis"><?php echo gtext($lista["liste_regalo"]["genitore_1"]);?></div>
		</div>
		<?php } ?>
		<?php if(in_array($lista["liste_regalo_tipi"]["id_lista_tipo"], ListeregalotipiModel::campoPresenteInTipi("genitore_2", ""))) { ?>
		<div class="uk-grid uk-margin-remove-top uk-grid-small uk-child-width-1-2" uk-grid>
			<div class="uk-text-small"><?php echo gtext("Genitore 2");?>:</div> <div class="uk-text-small uk-text-emphasis"><?php echo gtext($lista["liste_regalo"]["genitore_2"]);?></div>
		</div>
		<?php } ?>
	</div>
	<div class="uk-width-expand">
		<?php include(tpf(ElementitemaModel::p("LISTA_REGALO_PRODOTTI","", array(
			"titolo"	=>	"Prodotti da acquistare di una lista regalo",
			"percorso"	=>	"Elementi/ListaRegalo/ElencoProdotti",
		))));?>
	</div>
</div>

<?php include(tpf("/Elementi/Pagine/page_bottom.php"));
