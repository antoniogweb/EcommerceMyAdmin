<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="box_form_evidenzia">
	<div class="uk-text-small uk-text-emphasis"><?php echo gtext("Inserisci i dati della persona a cui vuoi inviare il codice del coupon.")?></div>
	
	<form class="form_invia_link uk-margin-medium" action="<?php echo $this->baseUrl."/promozioni/inviacodice/".$promozione["id_p"];?>" method="POST">
		<div class="invia_link_notice"></div>
		<div class="uk-grid-small uk-child-width-1-4@s uk-grid" uk-grid>
			<div class="uk-margin-bottom"><?php echo Html_Form::input("nome","","uk-input class_nome",null, 'placeholder="'.gtext("Nome").'*"');?></div>
			<div class="uk-margin-bottom"><?php echo Html_Form::input("cognome","","uk-input class_cognome",null, 'placeholder="'.gtext("Cognome").'"');?></div>
			<div class="uk-margin-bottom"><?php echo Html_Form::input("email","","uk-input class_email",null, 'placeholder="'.gtext("Email").'*"');?></div>
			<div class="uk-margin-bottom">
				<div class="uk-button uk-button-primary spinner uk-hidden" uk-spinner="ratio: .70"></div>
				<button class="invia_link_lista uk-button uk-button-primary btn_submit_form"><?php echo gtext("Invia");?></button>
			</div>
			<?php echo Html_Form::hidden("insertAction","1");?>
		</div>
	</form>
</div>

<div class="box_elenco_codici_inviati">
	<?php include(tpf("/Promozioni/codici_inviati.php"));?>
</div>
