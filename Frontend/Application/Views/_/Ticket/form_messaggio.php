<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if ($okInvioNuovoMessaggio) { ?>
<div class="uk-text-small uk-text-primary uk-text-bold uk-margin"><?php echo gtext("Aggiungi un messaggio");?></div>
<form class="form_registrazione box_form_evidenzia form_messaggio_ticket" id-ticket="<?php echo $idTicket;?>" ticket-uid="<?php echo $ticketUid;?>" action="<?php echo $this->baseUrl."/".$this->controller."/aggiungimessaggio/".(int)$ticket["id_ticket"]."/".$ticket["ticket_uid"];?>" method="POST" autocomplete="new-password" enctype="multipart/form-data">
	<div class="uk-text-center notice_messaggio">
		
	</div>
	
<!-- 	<label class="uk-form-label"><?php echo gtext("Descrizione");?> *</label> -->
	<div class="uk-form-controls">
		<?php echo Html_Form::textarea("descrizione","","uk-textarea class_descrizione",null,"rows='4' placeholder='".gtext("Descrizione", false)."'");?>
	</div>
	
	<div class="uk-margin-top uk-text-italic uk-text-meta"><?php echo gtext("Carica un'immagine (opzionale)")?></div>
	<div class="" uk-margin>
		<div uk-form-custom="target: true" class="uk-margin-remove">
			<input type="file" aria-label="Custom controls" name="filename">
			<input class="uk-input uk-form-width-medium" type="text" placeholder="<?php echo gtext("Seleziona il file");?>" aria-label="Custom controls" disabled>
		</div>
	</div>
	
	<div uk-grid class="uk-margin uk-grid-small uk-child-width-auto uk-grid condizioni_privacy_box class_accetto">
		<?php $idPrivacy = PagineModel::gTipoPagina("PRIVACY"); ?>
		<label><?php echo Html_Form::checkbox('accetto',"",'1','uk-checkbox');?><span class="uk-text-small uk-margin-left"><?php echo gtext("Ho letto e accetto le condizioni della");?> <a target="_blank" href="<?php echo $this->baseUrl."/".getUrlAlias($idPrivacy);?>"><?php echo gtext("privacy policy");?></a></span></label>
	</div>
	
	<div class="uk-margin">
		<div class="<?php echo v("classe_pulsanti_submit");?> uk-width-1-1 uk-width-auto@m spinner uk-hidden" uk-spinner="ratio: .70"></div>
		<button class="<?php echo v("classe_pulsanti_submit");?> aggiungi_messaggio_al_ticket btn_submit_form uk-width-1-1 uk-width-auto@m" type="submit"><?php echo gtext("Invia nuovo messaggio", false);?></button>
	</div>
	
	<?php echo Html_Form::hidden("insertAction","insertAction","hidden_ticket_submit_action");?>
</form>
<?php } else { ?>
<div class="uk-alert uk-alert-primary"><?php echo gtext("Hai raggiunto il numero massimo di messaggi consecutivi inviabili, per poter inviare un nuovo messaggio devi aspettare che il negozio aggiunga una risposta al ticket.")?></div>
<?php } ?>
