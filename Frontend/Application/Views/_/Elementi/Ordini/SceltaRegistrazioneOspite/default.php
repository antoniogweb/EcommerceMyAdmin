<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<h2 class="uk-text-emphasis uk-text-large"><?php echo gtextPlain("Creazione account");?></h2>

<div class="utente_registrato class_registrato">
	<div><?php echo Html_Form::radio("registrato",$values["registrato"],"N","radio_registrato","none");?> <?php echo gtextPlain("Continua come utente ospite", false);?></div>
	<div style="margin-top:10px;"><?php echo Html_Form::radio("registrato",$values["registrato"],"Y","radio_registrato","none");?> <?php echo gtextPlain("Crea account", false);?></div>
</div>

<?php if (!v("genera_e_invia_password")) { ?>
<div class="table_password">
	<div class="uk-grid-column-small uk-child-width-1-2@s" uk-grid>
		<div class="first_of_grid uk-margin uk-margin-remove-bottom">
			<label class="uk-form-label"><?php echo gtextPlain("Password");?> *</label>
			<div class="uk-form-controls uk-position-relative">
				<?php echo Html_Form::password("password",$regusers_values['password'],"uk-input class_password ".VariabiliModel::classeHelpWizardPassword(),null,"autocomplete='new-password' placeholder='".gtextAttr("Password", false)."'");?>
				<?php include tpf("Elementi/mostra_nascondi_password.php")?>
			</div>
		</div>
		<?php if (v("account_attiva_conferma_password")) { ?>
		<div class="uk-margin uk-margin-remove-bottom">
			<label class="uk-form-label"><?php echo gtextPlain("Conferma password");?> *</label>
			<div class="uk-form-controls uk-position-relative">
				<?php echo Html_Form::password("confirmation",$regusers_values['confirmation'],"uk-input class_confirmation ".VariabiliModel::classeHelpWizardPassword(),null,"autocomplete='new-password' placeholder='".gtextAttr("Conferma password", false)."'");?>
				<?php include tpf("Elementi/mostra_nascondi_password.php")?>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
<?php } ?> 
