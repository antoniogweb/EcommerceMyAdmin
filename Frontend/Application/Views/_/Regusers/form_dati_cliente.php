<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php include(tpf("/Elementi/FormRegistrazioneCheckout/scelta_tipo.php")); ?>

<?php include(tpf("/Elementi/FormRegistrazioneCheckout/check_fattura.php")); ?>

<div class="uk-grid-column-small uk-child-width-1-2@s uk-grid" uk-grid>
	<div class="box_entry_dati first_of_grid tr_ragione_sociale uk-margin uk-margin-remove-bottom">
		<label for="ragione_sociale" class="uk-form-label"><?php echo gtextPlain("Ragione sociale");?> <span aria-hidden="true">*</span></label>
		<div class="uk-form-controls">
			<?php echo Html_Form::input("ragione_sociale",$values['ragione_sociale'],"uk-input class_ragione_sociale","ragione_sociale",'placeholder="'.gtextAttr("Ragione sociale", false).'" aria-required="true"');?>
		</div>
	</div>
	<div class="box_entry_dati tr_nome uk-margin uk-margin-remove-bottom">
		<label for="nome" class="uk-form-label"><?php echo gtextPlain("Nome");?> <span aria-hidden="true">*</span></label>
		<div class="uk-form-controls">
			<?php echo Html_Form::input("nome",$values['nome'],"uk-input class_nome","nome","placeholder='".gtextAttr("Nome", false)."' aria-required='true'");?>
		</div>
	</div>
	<div class="box_entry_dati tr_cognome uk-margin uk-margin-remove-bottom">
		<label for="cognome" class="uk-form-label"><?php echo gtextPlain("Cognome");?> <span aria-hidden="true">*</span></label>
		<div class="uk-form-controls">
			<?php echo Html_Form::input("cognome",$values['cognome'],"uk-input class_cognome","cognome","placeholder='".gtextAttr("Cognome", false)."' aria-required='true'");?>
		</div>
	</div>

	<div class="box_entry_dati tr_p_iva uk-margin uk-margin-remove-bottom box_p_iva">
		<label for="p_iva" class="uk-form-label"><?php echo gtextPlain("Partita iva");?> <span aria-hidden="true">*</span></label>
		<div class="uk-form-controls">
			<?php echo Html_Form::input("p_iva",$values['p_iva'],"uk-input class_p_iva","p_iva","placeholder='".gtextAttr("Partita iva", false)."' aria-required='true'");?>
		</div>
	</div>

	<?php if (v("abilita_codice_fiscale")) { ?>
	<div class="box_entry_dati uk-margin uk-margin-remove-bottom nascondi_fuori_italia campo_codice_fiscale">
		<label for="codice_fiscale" class="uk-form-label"><?php echo gtextPlain("Codice fiscale");?> <span aria-hidden="true">*</span></label>
		<div class="uk-form-controls">
			<?php echo Html_Form::input("codice_fiscale",$values['codice_fiscale'],"uk-input class_codice_fiscale","codice_fiscale","placeholder='".gtextAttr("Codice fiscale", false)."' aria-required='true'");?>
		</div>
	</div>
	<?php } ?>

	<?php if (strcmp($this->controller,"regusers") === 0 || !CartModel::soloProdottiSenzaSpedizione(null, true, false) || $mostraCampiIndirizzoFatturazione) { ?>
		<?php if (count($selectNazioni) > 2) { ?>
		<div class="box_entry_dati uk-margin uk-margin-remove-bottom">
			<label for="nazione" class="uk-form-label"><?php echo gtextPlain("Nazione");?> <span aria-hidden="true">*</span></label>
			<div class="uk-form-controls">
				<?php echo Html_Form::select("nazione",$values['nazione'],$selectNazioni,"uk-select class_nazione","nazione","yes","aria-required='true'");?>
			</div>
		</div>
		<?php } else { ?>
			<?php echo Html_Form::hidden("nazione",$values['nazione']);?>
		<?php } ?>

		<div class="box_entry_dati uk-margin uk-margin-remove-bottom select_id_provincia">
			<div class="uk-form-controls">
				<div class="box_select_provincia">
					<label for="provincia" class="uk-form-label"><?php echo gtextPlain("Provincia");?> <span aria-hidden="true">*</span></label>
					<?php echo Html_Form::select("provincia",$values['provincia'],$province,"uk-select class_provincia","provincia","yes","aria-required='true'");?>
				</div>

				<div class="box_select_dprovincia">
					<label for="dprovincia" class="uk-form-label"><?php echo gtextPlain("Provincia");?> <span aria-hidden="true">*</span></label>
					<?php echo Html_Form::input("dprovincia",$values['dprovincia'],"uk-input class_dprovincia","dprovincia","placeholder='".gtextAttr("Provincia", false)."' aria-required='true'");?>
				</div>
			</div>
		</div>

		<div class="box_entry_dati uk-margin uk-margin-remove-bottom">
			<label for="citta" class="uk-form-label"><?php echo gtextPlain("Città");?> <span aria-hidden="true">*</span></label>
			<div class="uk-form-controls">
			<?php echo Html_Form::input("citta",$values['citta'],"uk-input class_citta","citta","placeholder='".gtextAttr("Città", false)."' aria-required='true'");?>
			</div>
		</div>
		<div class="box_entry_dati uk-margin uk-margin-remove-bottom">
			<label for="indirizzo" class="uk-form-label"><?php echo gtextPlain("Indirizzo");?> <span aria-hidden="true">*</span></label>
			<div class="uk-form-controls">
			<?php echo Html_Form::input("indirizzo",$values['indirizzo'],"uk-input class_indirizzo","indirizzo","placeholder='".gtextAttr("Indirizzo", false)."' aria-required='true'");?>
			</div>
		</div>
		<div class="box_entry_dati uk-margin uk-margin-remove-bottom">
			<label for="cap" class="uk-form-label"><?php echo gtextPlain("Cap");?> <span class="nascondi_fuori_italia_inline" aria-hidden="true">*</span></label>
			<div class="uk-form-controls">
			<?php echo Html_Form::input("cap",$values['cap'],"uk-input class_cap","cap","placeholder='".gtextAttr("Cap", false)."' aria-required='true'");?>
			</div>
		</div>
	<?php } else { ?>
		<?php echo Html_Form::hidden("nazione",$values['nazione']);?>
		<?php echo Html_Form::hidden("provincia",$values['provincia']);?>
		<?php echo Html_Form::hidden("dprovincia",$values['dprovincia']);?>
		<?php echo Html_Form::hidden("citta",$values['citta']);?>
		<?php echo Html_Form::hidden("indirizzo",$values['indirizzo']);?>
		<?php echo Html_Form::hidden("cap",$values['cap']);?>
	<?php } ?>

	<div class="box_entry_dati uk-margin uk-margin-remove-bottom">
		<?php $telefonoObbligatorio = GenericModel::camboObbligatorio("telefono", $this->controller, $tipoAzione); ?>
		<label for="telefono" class="uk-form-label"><?php echo gtextPlain("Telefono");?><?php if ($telefonoObbligatorio) { ?> <span aria-hidden="true">*</span><?php } ?></label>
		<div class="uk-form-controls">
			<?php echo Html_Form::input("telefono",$values['telefono'],"uk-input class_telefono","telefono","placeholder='".gtextAttr("Telefono", false)."'".($telefonoObbligatorio ? " aria-required='true'" : ""));?>
		</div>
	</div>
	
	<?php include(tpf("/Elementi/FormRegistrazioneCheckout/pagamento.php")); ?>
	
	<?php include(tpf("/Elementi/FormRegistrazioneCheckout/custom.php")); ?>
	
<!-- registrazione o modificadati -->
<?php if (strcmp($this->controller,"regusers") === 0) { ?>

	<div class="box_entry_dati uk-margin uk-margin-remove-bottom">
			<label for="username" class="uk-form-label"><?php echo gtextPlain("Email");?> <span aria-hidden="true">*</span></label>
		<div class="uk-form-controls">
			<?php echo Html_Form::input("username",$values['username'],"uk-input class_username","username","placeholder='".gtextAttr("Email", false)."' aria-required='true'");?>
		</div>
	</div>

	<?php if (strcmp($this->action,"modify") !== 0) { ?>

		<?php if (v("account_attiva_conferma_username")) { ?>
		<div class="box_entry_dati uk-margin uk-margin-remove-bottom">
			<label for="conferma_username" class="uk-form-label"><?php echo gtextPlain("Conferma email");?> <span aria-hidden="true">*</span></label>
			<div class="uk-form-controls">
				<?php echo Html_Form::input("conferma_username",$values['conferma_username'],"uk-input class_conferma_username","conferma_username","placeholder='".gtextAttr("Conferma email", false)."' aria-required='true'");?>
			</div>
		</div>
		<?php } ?>

		<?php if (!v("genera_e_invia_password")) { ?>
			<div class="box_entry_dati uk-margin uk-margin-remove-bottom">
				<label for="password" class="uk-form-label"><?php echo gtextPlain("Password");?> <span aria-hidden="true">*</span></label>
				<div class="uk-form-controls uk-position-relative">
					<?php echo Html_Form::password("password",$values['password'],"uk-input class_password ".VariabiliModel::classeHelpWizardPassword(),"password","autocomplete='new-password'  placeholder='".gtextAttr("Password", false)."' aria-required='true'");?>
					<?php include tpf("Elementi/mostra_nascondi_password.php");?>
				</div>
			</div>

			<?php if (v("account_attiva_conferma_password")) { ?>
			<div class="box_entry_dati uk-margin uk-margin-remove-bottom">
				<label for="confirmation" class="uk-form-label"><?php echo gtextPlain("Conferma password");?> <span aria-hidden="true">*</span></label>
				<div class="uk-form-controls uk-position-relative">
					<?php echo Html_Form::password("confirmation",$values['confirmation'],"uk-input class_confirmation ".VariabiliModel::classeHelpWizardPassword(),"confirmation","autocomplete='new-password'  placeholder='".gtextAttr("Conferma password", false)."' aria-required='true'");?>
					<?php include tpf("Elementi/mostra_nascondi_password.php");?>
				</div>
			</div>
			<?php } ?>
		<?php } ?>
	<?php } ?>
</div>

<!-- checkout ordine -->
<?php } else if (strcmp($this->controller,"ordini") === 0) { ?>

	<div class="box_entry_dati uk-margin uk-margin-remove-bottom">
		<label for="email" class="uk-form-label"><?php echo gtextPlain("Email");?> <span aria-hidden="true">*</span></label>
		<div class="uk-form-controls">
			<?php echo Html_Form::input("email",$values['email'],"uk-input class_email","email","autocomplete='new-password' placeholder='".gtextAttr("Email", false)."' aria-required='true'");?>
		</div>
	</div>
	<?php if (v("account_attiva_conferma_username")) { ?>
	<div class="box_entry_dati uk-margin uk-margin-remove-bottom">
		<label for="conferma_email" class="uk-form-label"><?php echo gtextPlain("Conferma email");?> <span aria-hidden="true">*</span></label>
		<div class="uk-form-controls">
			<?php echo Html_Form::input("conferma_email",$values['conferma_email'],"uk-input class_conferma_email","conferma_email","autocomplete='new-password' placeholder='".gtextAttr("Conferma email", false)."' aria-required='true'");?>
		</div>
	</div>
	<?php } ?>

	<?php if (!$islogged && !v("permetti_acquisto_anonimo") && !v("genera_e_invia_password")) { ?>
	<div class="box_entry_dati first_of_grid uk-margin uk-margin-remove-bottom">
		<label for="password" class="uk-form-label"><?php echo gtextPlain("Password");?> <span aria-hidden="true">*</span></label>
		<div class="uk-form-controls uk-position-relative">
			<?php echo Html_Form::password("password",$regusers_values['password'],"uk-input class_password ".VariabiliModel::classeHelpWizardPassword(),"password","autocomplete='new-password' placeholder='".gtextAttr("Password", false)."' aria-required='true'");?>
			<?php include tpf("Elementi/mostra_nascondi_password.php");?>
		</div>
	</div>
	<?php } ?>
</div>

	<?php if (!$islogged && v("permetti_acquisto_anonimo")) { ?>
		<?php
		include(tpf(ElementitemaModel::p("CHECKOUT_REGISTRAZ_OSPITE","", array(
			"titolo"	=>	"Scelta crea account o utente ospite",
			"percorso"	=>	"Elementi/Ordini/SceltaRegistrazioneOspite",
		))));
		?>
	<?php } ?>

<?php } ?>

<div class="clear"></div>

<div class="blocco_fatturazione_elettronica uk-margin">
	<h2 class="uk-margin-bottom uk-text-emphasis uk-text-large" style="margin:20px 0;"><?php echo gtextPlain("Dati per la fatturazione elettronica",false)?></h2>

	<?php echo testo("testo_fatt_elettronica")?>

	<div class="uk-grid-column-small uk-child-width-1-2@s" uk-grid>
		<div class="box_entry_dati first_of_grid uk-margin uk-margin-remove-bottom">
			<label for="pec" class="uk-form-label"><?php echo gtextPlain("Pec");?></label>
			<div class="uk-form-controls">
				<?php echo Html_Form::input("pec",$values['pec'],"uk-input class_pec","pec","placeholder='".gtextAttr("Pec", false)."'");?>
			</div>
		</div>
		<div class="box_entry_dati uk-margin uk-margin-remove-bottom">
			<label for="codice_destinatario" class="uk-form-label"><?php echo gtextPlain("Codice destinatario");?></label>
			<div class="uk-form-controls">
				<?php echo Html_Form::input("codice_destinatario",$values['codice_destinatario'],"uk-input class_codice_destinatario","codice_destinatario","placeholder='".gtextAttr("Codice destinatario", false)."'");?>
			</div>
		</div>
	</div>
</div>
