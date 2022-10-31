<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php $ospite = ($values["registrato"] == "Y") ? "N" : "Y";?>

<h2 class="<?php echo v("classi_titoli_checkout");?>"><span uk-icon="icon:user;ratio:1.2" class="uk-margin-right"></span><?php echo gtext("Creazione account");?></h2>

	<div class="uk-margin-top uk-width-1-1">
		<div class="uk-flex uk-flex-middle">
			<div>
				<?php echo Html_Form::checkbox("registrato_checkbox",$ospite,"Y","checkbox_registrato","none");?>
			</div>
			<div class="uk-margin-left uk-text-small uk-text-emphasis">
				<?php echo gtext("Continua come ospite (non creare un account)");?>
			</div>
		</div>
	</div>

	<?php echo Html_Form::hidden("registrato",$values["registrato"]);?>

	<div class="uk-grid-column-small uk-child-width-1-2@s" uk-grid>
		<?php if (!v("genera_e_invia_password")) { ?>
			<div class="table_password first_of_grid uk-margin uk-margin-remove-bottom">
				<label class="uk-form-label"><?php echo gtext("Password");?> *</label>
				<div class="uk-form-controls">
					<?php echo Html_Form::password("password",$regusers_values['password'],"uk-input class_password",null,"autocomplete='new-password' placeholder='".gtext("Password", false)."'");?>
				</div>
			</div>
			<?php if (v("account_attiva_conferma_password")) { ?>
			<div class="table_password uk-margin uk-margin-remove-bottom">
				<label class="uk-form-label"><?php echo gtext("Conferma password");?> *</label>
				<div class="uk-form-controls">
					<?php echo Html_Form::password("confirmation",$regusers_values['confirmation'],"uk-input class_confirmation",null,"autocomplete='new-password' placeholder='".gtext("Conferma password", false)."'");?>
				</div>
			</div>
			<?php } ?>
		<?php } ?>
	</div>

