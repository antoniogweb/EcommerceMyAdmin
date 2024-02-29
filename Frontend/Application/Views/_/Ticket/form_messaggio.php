<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<form class="form_registrazione box_form_evidenzia form_ticket" id-ticket="<?php echo $idTicket;?>" ticket-uid="<?php echo $ticketUid;?>" action="<?php echo $this->baseUrl."/".$this->controller."/".$this->action."/".(int)$ticket["id_ticket"]."/".$ticket["ticket_uid"];?>" method="POST" autocomplete="new-password">
	<div class="uk-text-center">
		
	</div>
	
	<label class="uk-form-label"><?php echo gtext("Descrizione");?> *</label>
	<div class="uk-form-controls">
		<?php echo Html_Form::textarea("descrizione","","uk-textarea class_descrizione",null,"placeholder='".gtext("Descrizione", false)."'");?>
	</div>
	
	<div uk-grid class="uk-margin uk-grid-small uk-child-width-auto uk-grid condizioni_privacy_box class_accetto">
		<?php $idPrivacy = PagineModel::gTipoPagina("PRIVACY"); ?>
		<label><?php echo Html_Form::checkbox('accetto',"",'1','uk-checkbox');?><span class="uk-text-small uk-margin-left"><?php echo gtext("Ho letto e accetto le condizioni della");?> <a target="_blank" href="<?php echo $this->baseUrl."/".getUrlAlias($idPrivacy);?>"><?php echo gtext("privacy policy");?></a></span></label>
	</div>
	
	<div class="uk-margin">
		<div class="<?php echo v("classe_pulsanti_submit");?> uk-width-1-1 uk-width-auto@m spinner uk-hidden" uk-spinner="ratio: .70"></div>
		<button class="<?php echo v("classe_pulsanti_submit");?> btn_submit_ticket btn_submit_form uk-width-1-1 uk-width-auto@m" type="submit"><?php echo gtext("Invia nuovo messaggio", false);?></button>
	</div>
	
	<?php echo Html_Form::hidden("updateAction","updateAction","hidden_ticket_submit_action");?>
</form>
