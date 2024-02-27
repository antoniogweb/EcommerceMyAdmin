<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (User::$logged)
{
	$breadcrumb = array(
		gtext("Home") 		=> $this->baseUrl,
		gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
		gtext("Ticket assistenza") => $this->baseUrl."/ticket/",
		gtext("Dettaglio ticket") => "",
	);
}
else
{
	$breadcrumb = array(
		gtext("Home") 		=> $this->baseUrl,
		gtext("Dettaglio ticket") => "",
	);
}

$titoloPagina = gtext("Ticket assistenza");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "ticket";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>
<form class="form_registrazione box_form_evidenzia form_ticket" action="<?php echo $this->baseUrl."/".$this->controller."/".$this->action."/".(int)$ticket["id_ticket"]."/".$ticket["ticket_uid"];?>" method="POST" autocomplete="new-password">
	<div class="uk-text-center">
		<?php echo $notice; ?>
	</div>
	
	<div class="uk-grid-column-small uk-child-width-1-2@s uk-grid" uk-grid>
		<div class="first_of_grid tr_ragione_sociale uk-margin uk-margin-remove-bottom">
			<label class="uk-form-label"><?php echo gtext("Tipologia della richiesta di assistenza");?> *</label>
			<div class="uk-form-controls uk-margin-bottom">
				<?php echo Html_Form::select("id_ticket_tipologia",$values['id_ticket_tipologia'],$tipologie,"uk-select class_id_ticket_tipologia",null,"yes");?>
			</div>
			
			<label class="uk-form-label"><?php echo gtext("Oggetto della richiesta");?> *</label>
			<div class="uk-form-controls uk-margin-bottom">
				<?php echo Html_Form::input("oggetto",$values['oggetto'],"uk-input class_oggetto",null,"placeholder='".gtext("Oggetto della richiesta", false)."'");?>
			</div>
			
			<label class="uk-form-label"><?php echo gtext("Descrizione");?> *</label>
			<div class="uk-form-controls">
				<?php echo Html_Form::textarea("descrizione",$values['descrizione'],"uk-textarea class_descrizione",null,"placeholder='".gtext("Descrizione", false)."'");?>
			</div>
			
			<?php include (tpf("Elementi/Pagine/campo-captcha.php"));?>
			
			<div uk-grid class="uk-margin uk-grid-small uk-child-width-auto uk-grid condizioni_privacy_box class_accetto">
				<?php $idPrivacy = PagineModel::gTipoPagina("PRIVACY"); ?>
				<label><?php echo Html_Form::checkbox('accetto',$values['accetto'],'1','uk-checkbox');?><span class="uk-text-small uk-margin-left"><?php echo gtext("Ho letto e accetto le condizioni della");?> <a target="_blank" href="<?php echo $this->baseUrl."/".getUrlAlias($idPrivacy);?>"><?php echo gtext("privacy policy");?></a></span></label>
			</div>
			
			<div class="uk-margin">
				<div class="<?php echo v("classe_pulsanti_submit");?> uk-width-1-1 uk-width-auto@m spinner uk-hidden" uk-spinner="ratio: .70"></div>
				<button class="<?php echo v("classe_pulsanti_submit");?> btn_submit_ticket btn_submit_form uk-width-1-1 uk-width-auto@m" type="submit"><?php echo gtext("Invia richiesta", false);?></button>
			</div>
			
			<?php echo Html_Form::hidden("updateAction","updateAction","hidden_ticket_submit_action");?>
		</div>
		<div class="box_entry_dati uk-margin uk-margin-remove-bottom">
			
		</div>
	</div>
</form>

<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
