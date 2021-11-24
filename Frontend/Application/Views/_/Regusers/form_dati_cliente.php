<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="tipo_cliente class_tipo_cliente uk-margin">
<?php
if (v("solo_utenti_privati"))
	echo Html_Form::hidden("tipo_cliente",$values['tipo_cliente'],"privato");
else
{
	$divisoreTipoCliente = User::$isPhone ? "<div class='tipo_cliente_divisore'></div>" : "";
	
	if (count($tipiClienti) > 1)
	{
		$tipoCliente = array();
		
		foreach ($tipiClienti as $codiceTipo => $titoloTipo)
		{
			$tipoCliente["<span style='margin-left:8px;'></span>".gtext($titoloTipo)."<span style='margin-right:20px;'></span>$divisoreTipoCliente"] = $codiceTipo; 
		}
		
		echo Html_Form::radio("tipo_cliente",$values['tipo_cliente'],$tipoCliente,"radio_cliente");
	}
	else
	{
		foreach ($tipiClienti as $codiceTipo => $titoloTipo)
		{
			echo Html_Form::hidden("tipo_cliente",$codiceTipo,$codiceTipo);
		}
	}
}
?>
</div>
<div class="uk-grid-column-small uk-child-width-1-2@s" uk-grid>
	<div class="first_of_grid tr_ragione_sociale uk-margin uk-margin-remove-bottom">
		<label class="uk-form-label"><?php echo gtext("Ragione sociale");?> *</label>
		<div class="uk-form-controls">
			<?php echo Html_Form::input("ragione_sociale",$values['ragione_sociale'],"uk-input class_ragione_sociale",null,"placeholder='".gtext("Ragione sociale", false)."'");?>
		</div>
	</div>
	<div class="tr_nome uk-margin uk-margin-remove-bottom">
		<label class="uk-form-label"><?php echo gtext("Nome");?> *</label>
		<div class="uk-form-controls">
			<?php echo Html_Form::input("nome",$values['nome'],"uk-input class_nome",null,"placeholder='".gtext("Nome", false)."'");?>
		</div>
	</div>
	<div class="tr_cognome uk-margin uk-margin-remove-bottom">
		<label class="uk-form-label"><?php echo gtext("Cognome");?> *</label>
		<div class="uk-form-controls">
			<?php echo Html_Form::input("cognome",$values['cognome'],"uk-input class_cognome",null,"placeholder='".gtext("Cognome", false)."'");?>
		</div>
	</div>
	
	<div class="tr_p_iva uk-margin uk-margin-remove-bottom box_p_iva">
		<label class="uk-form-label"><?php echo gtext("Partita iva");?> *</label>
		<div class="uk-form-controls">
			<?php echo Html_Form::input("p_iva",$values['p_iva'],"uk-input class_p_iva",null,"placeholder='".gtext("Partita iva", false)."'");?>
		</div>
	</div>
	
	<?php if (v("abilita_codice_fiscale")) { ?>
	<div class="uk-margin uk-margin-remove-bottom nascondi_fuori_italia">
		<label class="uk-form-label"><?php echo gtext("Codice fiscale");?> *</label>
		<div class="uk-form-controls">
			<?php echo Html_Form::input("codice_fiscale",$values['codice_fiscale'],"uk-input class_codice_fiscale",null,"placeholder='".gtext("Codice fiscale", false)."'");?>
		</div>
	</div>
	<?php } ?>
	
	<?php if (count($selectNazioni) > 2) { ?>
	<div class="uk-margin uk-margin-remove-bottom">
		<label class="uk-form-label"><?php echo gtext("Nazione");?> *</label>
		<div class="uk-form-controls">
			<?php echo Html_Form::select("nazione",$values['nazione'],$selectNazioni,"uk-select class_nazione",null,"yes");?>
		</div>
	</div>
	<?php } else { ?>
		<?php echo Html_Form::hidden("nazione",$values['nazione']);?>
	<?php } ?>
	
	<div class="uk-margin uk-margin-remove-bottom select_id_provincia">
		<label class="uk-form-label"><?php echo gtext("Provincia");?> *</label>
		
		<div class="uk-form-controls">
			<div class="box_select_provincia">
				<?php echo Html_Form::select("provincia",$values['provincia'],$province,"uk-select class_provincia",null,"yes");?>
			</div>
			
			<div class="box_select_dprovincia">
				<?php echo Html_Form::input("dprovincia",$values['dprovincia'],"uk-input class_dprovincia",null,"placeholder='".gtext("Provincia", false)."'");?>
			</div>
		</div>
	</div>
	
	<?php include (tpf("Elementi/Pagine/campo-captcha-registrazione.php"));?>
	
	<div class="uk-margin uk-margin-remove-bottom">
		<label class="uk-form-label"><?php echo gtext("Città");?> *</label>
		<div class="uk-form-controls">
			<?php echo Html_Form::input("citta",$values['citta'],"uk-input class_citta",null,"placeholder='".gtext("Città", false)."'");?>
		</div>
	</div>
	<div class="uk-margin uk-margin-remove-bottom">
		<label class="uk-form-label"><?php echo gtext("Indirizzo");?> *</label>
		<div class="uk-form-controls">
			<?php echo Html_Form::input("indirizzo",$values['indirizzo'],"uk-input class_indirizzo",null,"placeholder='".gtext("Indirizzo", false)."'");?>
		</div>
	</div>
	<div class="uk-margin uk-margin-remove-bottom">
		<label class="uk-form-label"><?php echo gtext("Cap");?> <span class="nascondi_fuori_italia_inline">*</span></label>
		<div class="uk-form-controls">
			<?php echo Html_Form::input("cap",$values['cap'],"uk-input class_cap",null,"placeholder='".gtext("Cap", false)."'");?>
		</div>
	</div>
	
	<div class="uk-margin uk-margin-remove-bottom">
		<label class="uk-form-label"><?php echo gtext("Telefono");?> <?php echo CommonModel::asterisco("telefono", $this->controller, $tipoAzione);?></label>
		<div class="uk-form-controls">
			<?php echo Html_Form::input("telefono",$values['telefono'],"uk-input class_telefono",null,"placeholder='".gtext("Telefono", false)."'");?>
		</div>
	</div>

<!-- registrazione o modificadati -->
<?php if (strcmp($this->controller,"regusers") === 0) { ?>

	<div class="uk-margin uk-margin-remove-bottom">
		<label class="uk-form-label"><?php echo gtext("Email");?> *</label>
		<div class="uk-form-controls">
			<?php echo Html_Form::input("username",$values['username'],"uk-input class_username",null,"placeholder='".gtext("Email", false)."'");?>
		</div>
	</div>
	
	<?php if (strcmp($this->action,"add") === 0) { ?>
	
		<?php if (v("account_attiva_conferma_username")) { ?>
		<div class="uk-margin uk-margin-remove-bottom">
			<label class="uk-form-label"><?php echo gtext("Conferma email");?> *</label>
			<div class="uk-form-controls">
				<?php echo Html_Form::input("conferma_username",$values['conferma_username'],"uk-input class_conferma_username",null,"placeholder='".gtext("Conferma email", false)."'");?>
			</div>
		</div>
		<?php } ?>
		
		<?php if (!v("genera_e_invia_password")) { ?>
			<div class="uk-margin uk-margin-remove-bottom">
				<label class="uk-form-label"><?php echo gtext("Password");?> *</label>
				<div class="uk-form-controls">
					<?php echo Html_Form::password("password",$values['password'],"uk-input class_password",null,"autocomplete='new-password'  placeholder='".gtext("Password", false)."'");?>
				</div>
			</div>
			
			<?php if (v("account_attiva_conferma_password")) { ?>
			<div class="uk-margin uk-margin-remove-bottom">
				<label class="uk-form-label"><?php echo gtext("Conferma password");?> *</label>
				<div class="uk-form-controls">
					<?php echo Html_Form::password("confirmation",$values['confirmation'],"uk-input class_confirmation",null,"autocomplete='new-password'  placeholder='".gtext("Conferma password", false)."'");?>
				</div>
			</div>
			<?php } ?>
		<?php } ?>
	<?php } ?>
</div>

<!-- checkout ordine -->
<?php } else if (strcmp($this->controller,"ordini") === 0) { ?>

	<div class="uk-margin uk-margin-remove-bottom">
		<label class="uk-form-label"><?php echo gtext("Email");?> *</label>
		<div class="uk-form-controls">
			<?php echo Html_Form::input("email",$values['email'],"uk-input class_email",null,"autocomplete='new-password' placeholder='".gtext("Email", false)."'");?>
		</div>
	</div>
	<?php if (v("account_attiva_conferma_username")) { ?>
	<div class="uk-margin uk-margin-remove-bottom">
		<label class="uk-form-label"><?php echo gtext("Conferma email");?> *</label>
		<div class="uk-form-controls">
			<?php echo Html_Form::input("conferma_email",$values['conferma_email'],"uk-input class_conferma_email",null,"autocomplete='new-password' placeholder='".gtext("Conferma email", false)."'");?>
		</div>
	</div>
	<?php } ?>
	
	<?php if (!$islogged && !v("permetti_acquisto_anonimo") && !v("genera_e_invia_password")) { ?>
	<div class="first_of_grid uk-margin uk-margin-remove-bottom">
		<label class="uk-form-label"><?php echo gtext("Password");?> *</label>
		<div class="uk-form-controls">
			<?php echo Html_Form::password("password",$regusers_values['password'],"uk-input class_password",null,"autocomplete='new-password' placeholder='".gtext("Password", false)."'");?>
		</div>
	</div>
	<?php } ?>
</div>

	<?php if (!$islogged && v("permetti_acquisto_anonimo")) { ?>
		<h2 class="uk-text-emphasis uk-text-large"><?php echo gtext("Creazione account");?></h2>
		
		<div class="utente_registrato class_registrato">
			<div><?php echo Html_Form::radio("registrato",$values["registrato"],"N","radio_registrato","none");?> <?php echo gtext("Continua come utente ospite", false);?></div>
			<div style="margin-top:10px;"><?php echo Html_Form::radio("registrato",$values["registrato"],"Y","radio_registrato","none");?> <?php echo gtext("Crea account", false);?></div>
		</div>

		<?php if (!v("genera_e_invia_password")) { ?>
		<div class="table_password">
			<div class="uk-grid-column-small uk-child-width-1-2@s" uk-grid>
				<div class="first_of_grid uk-margin uk-margin-remove-bottom">
					<label class="uk-form-label"><?php echo gtext("Password");?> *</label>
					<div class="uk-form-controls">
						<?php echo Html_Form::password("password",$regusers_values['password'],"uk-input class_password",null,"autocomplete='new-password' placeholder='".gtext("Password", false)."'");?>
					</div>
				</div>
				<?php if (v("account_attiva_conferma_password")) { ?>
				<div class="uk-margin uk-margin-remove-bottom">
					<label class="uk-form-label"><?php echo gtext("Conferma password");?> *</label>
					<div class="uk-form-controls">
						<?php echo Html_Form::password("confirmation",$regusers_values['confirmation'],"uk-input class_confirmation",null,"autocomplete='new-password' placeholder='".gtext("Conferma password", false)."'");?>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
	<?php } ?>

<?php } ?>

<div class="clear"></div>

<div class="blocco_fatturazione_elettronica uk-margin">
	<h2 class="uk-margin-bottom uk-text-emphasis uk-text-large" style="margin:20px 0;"><?php echo gtext("Dati per la fatturazione elettronica",false)?></h2>
	
	<?php echo testo("testo_fatt_elettronica")?>
	
	<div class="uk-grid-column-small uk-child-width-1-2@s" uk-grid>
		<div class="first_of_grid uk-margin uk-margin-remove-bottom">
			<label class="uk-form-label"><?php echo gtext("Pec");?></label>
			<div class="uk-form-controls">
				<?php echo Html_Form::input("pec",$values['pec'],"uk-input class_pec",null,"placeholder='".gtext("Pec", false)."'");?>
			</div>
		</div>
		<div class="uk-margin uk-margin-remove-bottom">
			<label class="uk-form-label"><?php echo gtext("Codice destinatario");?></label>
			<div class="uk-form-controls">
				<?php echo Html_Form::input("codice_destinatario",$values['codice_destinatario'],"uk-input class_codice_destinatario",null,"placeholder='".gtext("Codice destinatario", false)."'");?>
			</div>
		</div>
	</div>
</div>
