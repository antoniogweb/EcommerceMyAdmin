<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div id="modale-scelta-lista" class="uk-flex-top" uk-modal>
    <div class="uk-modal-dialog uk-margin-auto-vertical">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-body uk-text-center uk-padding">
			<h5><?php echo gtext("Seleziona la lista a cui aggiungere questo prodotto");?></h5>
			<div class="first_of_grid uk-margin uk-margin-remove-bottom">
				<div class="uk-form-controls">
					<?php echo Html_Form::select("id_lista","",array("0" => gtext("Seleziona la lista regalo")) + $liste_regalo,"uk-select class_id_lista_tipo",null,"yes");?>
				</div>
			</div>
			<?php
			$aggiuntaDiretta = true;
			$classePulsanteAggiungiAllaLista = "uk-width-1-1 uk-button uk-button-primary uk-margin-top";
			$classeDivPulsanteAggiungiAllaLista = "uk-margin-small uk-width-1-1";
			$TestoPulsanteAggiungiAllaLista = gtext("Aggiungi");
			
			include(tpf(ElementitemaModel::p("PULSANTE_AGGIUNGI_ALLA_LISTA","", array(
				"titolo"	=>	"Pulsante aggiungi alla lista",
				"percorso"	=>	"Elementi/Prodotti/Dettaglio/PulsanteAggiungiAllaLista",
			)))); ?>
        </div>
    </div>
</div>
