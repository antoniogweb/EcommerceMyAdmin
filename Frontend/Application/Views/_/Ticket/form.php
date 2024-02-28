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
	
	<div class="uk-grid-column-large uk-child-width-1-2@s uk-grid" uk-grid>
		<div class="first_of_grid tr_ragione_sociale uk-margin uk-margin-remove-bottom">
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
			<?php if ($mostra_tendina_prodotti && $numeroProdotti < v("numero_massimo_prodotti_ticket")) { ?>
			<div class="uk-text-small uk-text-primary uk-text-bold uk-margin-bottom-small"><?php echo gtext("Seleziona e aggiugni un prodotto di cui chiedi assistenza");?></div>
			<div class="uk-form-controls">
				<div class="uk-grid-collapse uk-grid uk-flex uk-flex-bottom" uk-grid>
					<div class="uk-width-3-4">
						<label class="uk-form-label"><?php echo gtext("Seleziona il prodotto");?> *</label>
						<div class="uk-form-controls uk-margin-bottom"><?php echo Html_Form::select("id_page",0,$prodotti,"uk-select class_id_page",null,"yes");?></div>
						
						<label class="uk-form-label"><?php echo gtext("Scrivi il numero seriale del prodotto");?></label>
						<div class="uk-form-controls"><?php echo Html_Form::input("numero_seriale","","uk-input class_numero_seriale",null,"placeholder='".gtext("Numero seriale", false)."'");?></div>
					</div>
					<div class="uk-width-1-4 uk-text-right">
						<div class="uk-button uk-button-primary spinner uk-hidden" uk-spinner="ratio: .70"></div>
						<a href="<?php echo $this->baseUrl."/ticket/aggiungiprodotto/$idTicket/$ticketUid"?>" title="<?php echo gtext("Aggiungi il prodotto al ticket")?>" class="uk-button uk-button-primary aggiungi_al_ticket"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/plus.svg");?></span></a>
					</div>
				</div>
			</div>
			<hr />
			<?php } ?>
			
			<?php if (count($prodottiInseriti) > 0) { ?>
			<div class="uk-text-small uk-text-primary uk-text-bold uk-margin-bottom-small uk-margin-top"><?php echo gtext("Prodotti aggiunti al ticket");?></div>
			<div class="uk-overflow-auto">
				<table class="uk-table uk-table-divider uk-table-hover uk-table-small" cellspacing="0">
					<?php foreach ($prodottiInseriti as $p) { ?>
					<tr class="ordini_table_row uk-text-small">
						<td><img style="max-width:50px;" src="<?php echo $this->baseUrlSrc."/thumb/carrello/".$p["pages"]["immagine"];?>" alt="<?php echo altUrlencode(field($p, "title"));?>"></td>
						<td><?php echo field($p, "title");?></td>
						<td class="uk-text-right">
							<div class="uk-margin-left uk-text-bold td_edit uk-text-danger spinner uk-hidden" uk-spinner="ratio: .70"></div>
							<a id-page="<?php echo (int)$p["pages"]["id_page"];?>" class="btn_submit_form elimina_dal_tiket uk-margin-left uk-text-bold td_edit uk-text-danger" title="<?php echo gtext("Elimina il prodotto dal ticket",false);?>" href="<?php echo $this->baseUrl."/ticket/rimuoviprodotto/$idTicket/$ticketUid";?>"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/trash.svg");?></span></a>
						</td>
					</tr>
					<?php } ?>
				</table>
			</div>
			<?php } ?>
		</div>
	</div>
</form>

<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
