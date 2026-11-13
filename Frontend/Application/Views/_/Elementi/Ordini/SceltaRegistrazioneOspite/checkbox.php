<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php $ospite = ($values["registrato"] == "Y") ? "N" : "Y";?>
<div class="uk-grid uk-grid-small uk-child-width-1-1 uk-margin-top" uk-grid>
	<div class="uk-width-1-1 uk-width-1-2@m ">
	<!-- 	<h2 class="<?php echo v("classi_titoli_checkout");?>"><span uk-icon="icon:user;ratio:1.2" class="uk-margin-right"></span><?php echo gtextPlain("Creazione account");?></h2> -->

		<div class=" uk-width-1-1 ">
			<div class="uk-flex uk-flex-middle">
				<div>
					<?php echo Html_Form::checkbox("registrato_checkbox",$ospite,"Y","checkbox_registrato","checkout-continua-ospite");?>
				</div>
				<div class="uk-margin-left uk-text-small uk-text-emphasis">
					<label for="checkout-continua-ospite"><?php echo gtextPlain("Continua come ospite (non creare un account)");?></label>
				</div>
			</div>
		</div>

		<?php echo Html_Form::hidden("registrato",$values["registrato"]);?>

		<div class="uk-grid uk-grid-large uk-child-width-1-1" uk-grid>
			<?php if (!v("genera_e_invia_password")) { ?>
				<div class="table_password first_of_grid uk-margin uk-margin-remove-bottom">
					<label for="checkout-password" class="uk-form-label"><?php echo gtextPlain("Password");?> <span aria-hidden="true">*</span></label>
					<div class="uk-form-controls uk-position-relative">
						<?php echo Html_Form::password("password",$regusers_values['password'],"uk-input class_password ".VariabiliModel::classeHelpWizardPassword(),"checkout-password","autocomplete='new-password' aria-required='true' placeholder='".gtextAttr("Password", false)."'");?>
						<?php include tpf("Elementi/mostra_nascondi_password.php")?>
					</div>
				</div>
				<?php if (v("account_attiva_conferma_password")) { ?>
				<div class="table_password uk-margin uk-margin-remove-bottom">
					<label for="checkout-confirmation" class="uk-form-label"><?php echo gtextPlain("Conferma password");?> <span aria-hidden="true">*</span></label>
					<div class="uk-form-controls uk-position-relative">
						<?php echo Html_Form::password("confirmation",$regusers_values['confirmation'],"uk-input class_confirmation ".VariabiliModel::classeHelpWizardPassword(),"checkout-confirmation","autocomplete='new-password' aria-required='true' placeholder='".gtextAttr("Conferma password", false)."'");?>
						<?php include tpf("Elementi/mostra_nascondi_password.php")?>
					</div>
				</div>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
</div>
