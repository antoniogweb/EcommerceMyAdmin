<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_liste_regalo") && User::$logged && isset($liste_regalo) && count($liste_regalo) > 0 && (!isset($personalizzazioni) || count($personalizzazioni) === 0) && !idCarrelloEsistente() && !ProdottiModel::isGiftCart((int)$p["pages"]["id_page"])) { ?>
	<?php if ((int)count($liste_regalo) === 1) { ?>
		<?php echo Html_Form::hidden("id_lista",$liste_regalo[0]["id_lista_regalo"]);?>
		
		<?php include(tpf(ElementitemaModel::p("PULSANTE_AGGIUNGI_ALLA_LISTA","", array(
			"titolo"	=>	"Pulsante aggiungi alla lista",
			"percorso"	=>	"Elementi/Prodotti/Dettaglio/PulsanteAggiungiAllaLista",
		)))); ?>
	<?php } else { ?>
	
	<?php } ?>
<?php } ?>
