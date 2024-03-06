<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<form class="form_registrazione box_form_evidenzia form_ticket" id-ticket="<?php echo $idTicket;?>" ticket-uid="<?php echo $ticketUid;?>" action="<?php echo $this->baseUrl."/".$this->controller."/".$this->action."/".(int)$ticket["id_ticket"]."/".$ticket["ticket_uid"];?>" method="POST" autocomplete="new-password">
	<div class="uk-text-center">
		<?php echo $notice; ?>
	</div>
	
	<div class="uk-grid-column-large uk-child-width-1-2@s uk-grid" uk-grid>
		<div class="first_of_grid tr_ragione_sociale uk-margin uk-margin-remove-bottom">
			<div class="uk-text-small uk-text-primary uk-text-bold uk-margin-bottom-small"><?php echo gtext("Dettaglio della richiesta di assistenza");?></div>
			<label class="uk-form-label"><?php echo gtext("Tipologia della richiesta di assistenza");?> *</label>
			<div class="uk-form-controls uk-margin-bottom">
				<?php echo Html_Form::select("id_ticket_tipologia",$values['id_ticket_tipologia'],$tipologie,"uk-select class_id_ticket_tipologia",null,"yes");?>
			</div>
			
			<?php if ($tipologia["tipo"] == "ORDINE") { ?>
			<label class="uk-form-label"><?php echo gtext("Seleziona l'ordine per cui chiedi assistenza");?> *</label>
			<div class="uk-form-controls uk-margin-bottom">
				<?php echo Html_Form::select("id_o",$values['id_o'],$ordini,"uk-select class_id_o",null,"yes");?>
			</div>
			<?php } ?>
			
			<?php if ($tipologia["tipo"] == "LISTA REGALO") { ?>
			<label class="uk-form-label"><?php echo gtext("Seleziona la lista regalo per cui chiedi assistenza");?> *</label>
			<div class="uk-form-controls uk-margin-bottom">
				<?php echo Html_Form::select("id_lista_regalo",$values['id_lista_regalo'],$listeRegalo,"uk-select class_id_lista_regalo",null,"yes");?>
			</div>
			<?php } ?>
			
			<label class="uk-form-label"><?php echo gtext("Oggetto della richiesta");?> *</label>
			<div class="uk-form-controls uk-margin-bottom">
				<?php echo Html_Form::input("oggetto",$values['oggetto'],"uk-input class_oggetto",null,"placeholder='".gtext("Oggetto della richiesta", false)."'");?>
			</div>
			
			<label class="uk-form-label"><?php echo gtext("Descrizione");?> *</label>
			<div class="uk-form-controls">
				<?php echo Html_Form::textarea("descrizione",$values['descrizione'],"uk-textarea class_descrizione",null,"placeholder='".gtext("Descrizione", false)."'");?>
			</div>
			
			<?php /*include (tpf("Elementi/Pagine/campo-captcha.php"));*/?>
			
			<?php echo Html_Form::hidden("updateAction","updateAction","hidden_ticket_submit_action");?>
		</div>
		<div class="box_entry_dati uk-margin uk-margin-remove-bottom">
			<div class="box_prodotti">
				<?php include (tpf("Ticket/prodotti.php"));?>
			</div>
		</div>
	</div>
	
	<hr />
	<div class="uk-grid-column-large uk-child-width-1-2@s uk-grid uk-margin-bottom-remove" uk-grid>
		<div>
			<div class="uk-text-small uk-text-primary uk-text-bold uk-margin-bottom-small"><?php echo gtext("Carica un'immagine (opzionale)");?></div>
			<div class="box_immagini box_immagini_immagine">
				<?php
				$files = $immagini;
				$tipo = "IMMAGINE";
				include (tpf("Ticket/immagini.php"));?>
			</div>
			
		</div>
		<div>
			<div class="uk-text-small uk-text-primary uk-text-bold uk-margin-bottom-small"><?php echo gtext("Carica la foto dello scontrino (opzionale)");?></div>
			<div class="box_immagini box_immagini_scontrino">
				<?php
				$files = $scontrini;
				$tipo = "SCONTRINO";
				include (tpf("Ticket/immagini.php"));?>
			</div>
		</div>
	</div>
    <hr />
    <div class="uk-text-small uk-text-primary uk-text-bold uk-margin-bottom-small"><?php echo gtext("Carica un video (opzionale)");?></div>
    <div class="box_immagini box_immagini_video">
		<?php
		$files = $video;
		$tipo = "VIDEO";
		include (tpf("Ticket/immagini.php"));?>
	</div>
	<hr />
	<div uk-grid class="uk-margin uk-grid-small uk-child-width-auto uk-grid condizioni_privacy_box class_accetto">
		<?php $idPrivacy = PagineModel::gTipoPagina("PRIVACY"); ?>
		<label><?php echo Html_Form::checkbox('accetto',$values['accetto'],'1','uk-checkbox');?><span class="uk-text-small uk-margin-left"><?php echo gtext("Ho letto e accetto le condizioni della");?> <a target="_blank" href="<?php echo $this->baseUrl."/".getUrlAlias($idPrivacy);?>"><?php echo gtext("privacy policy");?></a></span></label>
	</div>
	
	<div class="uk-margin">
		<div class="<?php echo v("classe_pulsanti_submit");?> uk-button-large uk-width-1-1 uk-width-auto@m spinner uk-hidden" uk-spinner="ratio: .70"></div>
		<button class="<?php echo v("classe_pulsanti_submit");?> uk-button-large btn_submit_ticket btn_submit_form uk-width-1-1 uk-width-auto@m" type="submit"><?php echo gtext("Invia la richiesta di assistenza", false);?></button>
	</div>
</form>
