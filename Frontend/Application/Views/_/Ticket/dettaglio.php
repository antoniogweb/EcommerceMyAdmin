<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-grid-column-large uk-child-width-1-2@s uk-grid" uk-grid>
	<div class="first_of_grid tr_ragione_sociale uk-margin uk-margin-remove-bottom">
		<div class="uk-text-small uk-text-primary uk-text-bold uk-margin-bottom-small"><?php echo gtext("Dettaglio della richiesta di assistenza");?></div>
		
		<div class="uk-width-1-1 uk-text-small">
			<?php echo gtext("Tipologia della richiesta");?>: <?php echo $tipologia["titolo"];?><br />
			<?php if ($ticket["id_o"]) { ?>
			<?php echo gtext("N° Ordine");?>: <?php echo $ticket["id_o"];?><br />
			<?php } ?>
			<?php if ($ticket["id_lista_regalo"]) { ?>
			<?php echo gtext("Lista regalo");?>: <?php echo TicketModel::getLabelLista($ticket["id_lista_regalo"]);?><br />
			<?php } ?>
			<?php echo gtext("Oggetto della richiesta");?>: <?php echo $ticket["oggetto"];?><br />
			<?php echo gtext("Descrizione");?>:<br />
			
			<?php echo $ticket["descrizione"];?>
			
			<hr />
			
			<?php include(tpf("Ticket/form_messaggio.php"));?>
		</div>
		
	</div>
	<div class="box_entry_dati uk-margin uk-margin-remove-bottom">
		<div class="box_prodotti">
			<?php
			$mostra_tendina_prodotti = false;
			$eliminaButton = false;
			include (tpf("Ticket/prodotti.php"));?>
		</div>
	</div>
</div>
