<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-grid-column-large uk-child-width-1-2@s uk-grid" uk-grid>
	<div class="first_of_grid tr_ragione_sociale uk-margin uk-margin-remove-bottom">
		<div class="uk-text-small uk-text-primary uk-text-bold uk-margin-bottom-small"><?php echo gtext("Dettaglio della richiesta di assistenza");?></div>
		
		<div class="uk-width-1-1 uk-text-small">
			<?php echo gtext("Tipologia della richiesta");?>: <b><?php echo $tipologia["titolo"];?></b><br />
			<?php if ($ticket["id_o"]) { ?>
			<?php echo gtext("N° Ordine");?>: <b>#<?php echo $ticket["id_o"];?></b><br />
			<?php } ?>
			<?php if ($ticket["id_lista_regalo"]) { ?>
			<?php echo gtext("Lista regalo");?>: <?php echo TicketModel::getLabelLista($ticket["id_lista_regalo"]);?><br />
			<?php } ?>
			<br />
			<span class="uk-text-bold"><?php echo gtext("Oggetto della richiesta");?>:</span><br />
			<div class="uk-text-italic uk-text-secondary">
				<?php echo $ticket["oggetto"];?>
			</div>
			<span class="uk-text-bold"><?php echo gtext("Descrizione");?>:</span>
			
			<div class="uk-text-italic">
				<?php echo $ticket["descrizione"];?>
			</div>
			
			<div class="uk-text-muted uk-text-small uk-margin"><?php echo gtext("Scritto da") . " <span class='uk-text-secondary'>". TicketModel::getNominativo($cliente);?></span> <?php echo gtext("in data")?> <span class='uk-text-secondary'><?php echo date("d-m-Y H:i", strtotime($ticket["data_invio"]));?></span></div>
			
			<?php foreach ($messaggi as $m) { ?>
			<hr class="uk-divider-small">
			<div id="messaggi_<?php echo $m["ticket_messaggi"]["id_ticket_messaggio"];?>">
				<div class="uk-text-italic">
					<?php echo htmlentitydecode($m["ticket_messaggi"]["descrizione"]);?>
				</div>
				
				<div class="uk-text-muted uk-text-small uk-margin"><?php echo gtext("Scritto da") . " <span class='uk-text-secondary'>". TicketModel::getNominativo($cliente);?></span> <?php echo gtext("in data")?> <span class='uk-text-secondary'><?php echo date("d-m-Y H:i", strtotime($m["ticket_messaggi"]["data_creazione"]));?></span></div>
			</div>
			<?php } ?>
			
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
