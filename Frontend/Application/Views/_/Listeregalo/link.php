<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="box_form_evidenzia">
	<div class="uk-text-small uk-text-emphasis"><?php echo gtextPlain("Inserisci i dati della persona a cui vuoi inviare il link.")?></div>
	
	<form class="form_invia_link uk-margin-medium" action="<?php echo $this->baseUrl."/listeregalo/invialink/".$lista["id_lista_regalo"];?>" method="POST">
		<div class="invia_link_notice"></div>
		<div class="uk-grid-small uk-child-width-1-4@s uk-grid" uk-grid>
			<div class="uk-margin-bottom">
				<label for="listeregalo-link-nome" class="accessibility-visually-hidden"><?php echo gtextPlain("Nome");?> <span aria-hidden="true">*</span></label>
				<?php echo Html_Form::input("nome","","uk-input class_nome","listeregalo-link-nome", 'placeholder="'.gtextAttr("Nome").'*" aria-required="true"');?>
			</div>
			<div class="uk-margin-bottom">
				<label for="listeregalo-link-cognome" class="accessibility-visually-hidden"><?php echo gtextPlain("Cognome");?></label>
				<?php echo Html_Form::input("cognome","","uk-input class_cognome","listeregalo-link-cognome", 'placeholder="'.gtextAttr("Cognome").'"');?>
			</div>
			<div class="uk-margin-bottom">
				<label for="listeregalo-link-email" class="accessibility-visually-hidden"><?php echo gtextPlain("Email");?> <span aria-hidden="true">*</span></label>
				<?php echo Html_Form::input("email","","uk-input class_email","listeregalo-link-email", 'placeholder="'.gtextAttr("Email").'*" aria-required="true"');?>
			</div>
			<div class="uk-margin-bottom">
				<div class="uk-button uk-button-primary spinner uk-hidden" uk-spinner="ratio: .70"></div>
				<button class="invia_link_lista uk-button uk-button-primary btn_submit_form"><?php echo gtextPlain("Invia");?></button>
			</div>
			<?php echo Html_Form::hidden("insertAction","1");?>
			<?php include(tpf("Elementi/Pagine/campo-csrf.php")); ?>
		</div>
	</form>
</div>

<div class="box_elenco_link_inviati">
<?php include(tpf("/Listeregalo/link_inviati.php"));?>
</div>
