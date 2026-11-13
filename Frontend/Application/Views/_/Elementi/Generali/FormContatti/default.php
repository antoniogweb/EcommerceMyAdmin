<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="box_form_evidenzia">
	<div class="box_notice">
		<?php
		Form::$tipo = "C";
		echo Form::gNotice();
		?>
	</div>
	<form action="<?php echo Domain::$currentUrl;?><?php echo F::partial();?>#<?php echo v("fragment_form_contatti");?>" method="post" novalidate="novalidate">
		<fieldset class="uk-fieldset">
			<div uk-grid class="uk-margin uk-grid"> 
				<div class="uk-width-1-2@m uk-width-1-2@s">
				<label for="contatti-nome" class="accessibility-visually-hidden"><?php echo gtextPlain("Nome");?> <span aria-hidden="true">*</span></label>
				<?php echo Html_Form::input("nome",Form::gValue("nome"),"uk-input class_nome","contatti-nome","autocomplete='given-name' aria-required='true' placeholder='".gtextAttr("Nome*")."'");?>
				</div>
				
				<div class="uk-width-1-2@m uk-width-1-2@s">
				<label for="contatti-email" class="accessibility-visually-hidden"><?php echo gtextPlain("Email");?> <span aria-hidden="true">*</span></label>
				<?php echo Html_Form::input("email",Form::gValue("email"),"uk-input class_email","contatti-email","autocomplete='email' aria-required='true' placeholder='".gtextAttr("Email*")."'");?>
				</div>
			</div>

			<div class="uk-margin">
				<label for="contatti-messaggio" class="accessibility-visually-hidden"><?php echo gtextPlain("Messaggio");?> <span aria-hidden="true">*</span></label>
				<?php echo Html_Form::textarea("messaggio",Form::gValue("messaggio"),"uk-textarea class_messaggio","contatti-messaggio","rows='5' aria-required='true' placeholder='".gtextAttr("Messaggio*")."'");?>
			</div>
			
			<?php include (tpf("Elementi/Pagine/campo-captcha.php"));?>
			
			<div uk-grid class="uk-margin uk-grid-small uk-child-width-auto uk-grid condizioni_privacy_box class_accetto" data-field-name="<?php echo gtextAttr("privacy policy");?>">
				<?php $idPrivacy = PagineModel::gTipoPagina("PRIVACY"); ?>
				<?php echo Html_Form::checkbox('accetto',Form::gValue("accetto"),'1','uk-checkbox',"contatti-accetto","aria-required='true'");?> <label class="uk-text-small uk-margin-left" for="contatti-accetto"><?php echo gtextPlain("Ho letto e accetto le condizioni della privacy policy.");?></label> <a target="_blank" href="<?php echo $this->baseUrl."/".getUrlAlias($idPrivacy);?>"><?php echo gtextPlain("Leggi la privacy policy");?></a>
			</div>
			
			<?php echo Html_Form::hidden("invia","invia");?>
			<?php
			if (v("contatti_ajax_submit"))
				echo Html_Form::hidden("ajaxsubmit","ajaxsubmit");?>
			
			<div class="uk-margin">
				<div class="uk-button uk-button-secondary spinner uk-hidden" uk-spinner="ratio: .70"></div>
				<button type='submit' class="uk-button uk-button-secondary btn_submit_form"><?php echo gtextPlain('Invia',false);?></button>
			</div>
		</fieldset>
	</form>
</div> 
