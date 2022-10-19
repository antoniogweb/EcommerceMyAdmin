<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_liste_regalo") && User::$logged && isset($liste_regalo) && count($liste_regalo) > 0 && (!isset($personalizzazioni) || count($personalizzazioni) === 0) && !idCarrelloEsistente() && !ProdottiModel::isGiftCart((int)$p["pages"]["id_page"])) { ?>
	<?php if ((int)count($liste_regalo) === 1) { ?>
		<?php echo Html_Form::hidden("id_lista",key($liste_regalo));?>
		
		<?php
		$aggiuntaDiretta = true;
		include(tpf(ElementitemaModel::p("PULSANTE_AGGIUNGI_ALLA_LISTA","", array(
			"titolo"	=>	"Pulsante aggiungi alla lista",
			"percorso"	=>	"Elementi/Prodotti/Dettaglio/PulsanteAggiungiAllaLista",
		)))); ?>
	<?php } else {
		$aggiuntaDiretta = false;
		include(tpf(ElementitemaModel::p("PULSANTE_AGGIUNGI_ALLA_LISTA","", array(
			"titolo"	=>	"Pulsante aggiungi alla lista",
			"percorso"	=>	"Elementi/Prodotti/Dettaglio/PulsanteAggiungiAllaLista",
		))));
		
		include(tpf(ElementitemaModel::p("MODALE_SCELTA_LISTA","", array(
			"titolo"	=>	"Modale che si apre quando devi scegliere una lista",
			"percorso"	=>	"Elementi/Prodotti/Dettaglio/ModaleSceltaLista",
		))));
	} ?>

	<?php include(tpf(ElementitemaModel::p("MODALE_AGGIUNTO_LISTA","", array(
		"titolo"	=>	"Modale che si apre quando hai aggiunto alla lista",
		"percorso"	=>	"Elementi/Prodotti/Dettaglio/ModaleAggiuntoAllaLista",
	)))); ?>
<?php } ?>
