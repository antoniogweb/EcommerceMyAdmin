<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<form class="form_registrazione box_form_evidenzia form_ticket uk-position-relative" id-ticket="<?php echo $idTicket;?>" ticket-uid="<?php echo $ticketUid;?>" action="<?php echo $this->baseUrl."/".$this->controller."/".$this->action."/".(int)$ticket["id_ticket"]."/".$ticket["ticket_uid"];?>#ancora_form_ticket" method="POST">
	<div id="ancora_form_ticket" class="uk-position-absolute" style="margin-top:-100px;"></div>
	<div class="uk-text-center">
		<?php echo $notice; ?>
	</div>
	
	<div class="uk-grid-column-large uk-child-width-1-2@s uk-grid" uk-grid>
		<div class="first_of_grid tr_ragione_sociale uk-margin uk-margin-remove-bottom">
			<div class="uk-text-small uk-text-primary uk-text-bold uk-margin-bottom-small"><?php echo gtextPlain("Dettaglio della richiesta di assistenza");?></div>
			<label for="ticket-tipologia" class="uk-form-label"><?php echo gtextPlain("Tipologia della richiesta di assistenza");?> <span aria-hidden="true">*</span></label>
			<div class="uk-form-controls uk-margin-bottom">
				<?php echo Html_Form::select("id_ticket_tipologia",$values['id_ticket_tipologia'],$tipologie,"uk-select class_id_ticket_tipologia","ticket-tipologia","yes","aria-required='true'");?>
			</div>
			
			<?php if ($tipologia["tipo"] == "ORDINE") { ?>
			<label for="ticket-ordine" class="uk-form-label"><?php echo gtextPlain("Seleziona l'ordine per cui chiedi assistenza");?> <span aria-hidden="true">*</span></label>
			<div class="uk-form-controls uk-margin-bottom">
				<?php echo Html_Form::select("id_o",$values['id_o'],$ordini,"uk-select class_id_o","ticket-ordine","yes","aria-required='true'");?>
			</div>
			<?php } ?>
			
			<?php if ($tipologia["tipo"] == "LISTA REGALO") { ?>
			<label for="ticket-lista-regalo" class="uk-form-label"><?php echo gtextPlain("Seleziona la lista regalo per cui chiedi assistenza");?> <span aria-hidden="true">*</span></label>
			<div class="uk-form-controls uk-margin-bottom">
				<?php echo Html_Form::select("id_lista_regalo",$values['id_lista_regalo'],$listeRegalo,"uk-select class_id_lista_regalo","ticket-lista-regalo","yes","aria-required='true'");?>
			</div>
			<?php } ?>
			
			<label for="ticket-oggetto" class="uk-form-label"><?php echo gtextPlain("Oggetto della richiesta");?> <span aria-hidden="true">*</span></label>
			<div class="uk-form-controls uk-margin-bottom">
				<?php echo Html_Form::input("oggetto",$values['oggetto'],"uk-input class_oggetto","ticket-oggetto","aria-required='true' placeholder='".gtextAttr("Oggetto della richiesta", false)."'");?>
			</div>
			
			<label for="ticket-descrizione" class="uk-form-label"><?php echo gtextPlain("Descrizione");?> (<?php echo gtextPlain("massimo")." ".v("numero_massimo_caratteri_ticket")." ".gtextPlain("caratteri")?>) <span aria-hidden="true">*</span></label>
			<div class="uk-form-controls">
				<?php echo Html_Form::textarea("descrizione",$values['descrizione'],"uk-textarea class_descrizione","ticket-descrizione","aria-required='true' maxlength='".v("numero_massimo_caratteri_ticket")."' rows='4' placeholder='".gtextAttr("Descrizione", false)."'");?>
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
			<div class="uk-text-small uk-text-primary uk-text-bold uk-margin-bottom-small"><?php echo gtextPlain("Carica un'immagine (opzionale) - massimo")." ".(v("dimensioni_upload_immagine_ticket")/1000000)." MB";?></div>
			<div class="box_immagini box_immagini_immagine">
				<?php
				$files = $immagini;
				$tipo = "IMMAGINE";
				include (tpf("Ticket/immagini.php"));?>
			</div>
		</div>
		<div>
			<div class="uk-text-small uk-text-primary uk-text-bold uk-margin-bottom-small"><?php echo gtextPlain("Carica la foto dello scontrino (opzionale) - massimo")." ".(v("dimensioni_upload_immagine_ticket")/1000000)." MB";?></div>
			<div class="box_immagini box_immagini_scontrino">
				<?php
				$files = $scontrini;
				$tipo = "SCONTRINO";
				include (tpf("Ticket/immagini.php"));?>
			</div>
		</div>
	</div>
    <hr />
    <div class="uk-text-small uk-text-primary uk-text-bold uk-margin-bottom-small"><?php echo gtextPlain("Carica un video (opzionale) - massimo")." ".(v("dimensioni_upload_video_ticket")/1000000)." MB";?></div>
    <div class="box_immagini box_immagini_video">
		<?php
		$files = $video;
		$tipo = "VIDEO";
		include (tpf("Ticket/immagini.php"));?>
	</div>
	<hr />
	<div uk-grid class="uk-margin uk-grid-small uk-child-width-auto uk-grid condizioni_privacy_box class_accetto">
		<?php $idPrivacy = PagineModel::gTipoPagina("PRIVACY"); ?>
		<?php echo Html_Form::checkbox('accetto',$values['accetto'],'1','uk-checkbox',"ticket-accetto-privacy","aria-required='true'");?> <label for="ticket-accetto-privacy" class="uk-text-small uk-margin-left"><?php echo gtextPlain("Ho letto e accetto le condizioni della privacy policy.");?></label> <a target="_blank" href="<?php echo $this->baseUrl."/".getUrlAlias($idPrivacy);?>"><?php echo gtextPlain("Leggi la privacy policy");?></a>
	</div>
	
	<div class="uk-margin">
		<div class="<?php echo v("classe_pulsanti_submit");?> uk-button-large uk-width-1-1 uk-width-auto@m spinner uk-hidden" uk-spinner="ratio: .70"></div>
		<button class="<?php echo v("classe_pulsanti_submit");?> uk-button-large btn_submit_ticket btn_submit_form uk-width-1-1 uk-width-auto@m" type="submit"><?php echo gtextPlain("Invia la richiesta di assistenza", false);?></button>
	</div>
</form>
