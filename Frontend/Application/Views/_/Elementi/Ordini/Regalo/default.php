<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (OpzioniModel::isAttiva("CAMPI_FORM_CHECKOUT", "regalo") && OpzioniModel::isAttiva("CAMPI_FORM_CHECKOUT", "dedica") && OpzioniModel::isAttiva("CAMPI_FORM_CHECKOUT", "firma")) { ?>
<div class="spedizione_box uk-width-1-1 uk-margin-bottom">
	<div class="uk-flex uk-flex-top">
		<div>
			<?php echo Html_Form::checkbox("regalo_check",$values["regalo"],1,"",null);?>
		</div>
		<div class="uk-margin-left uk-text-small">
			<?php echo gtext("Questo ordine è un REGALO - Selezione OBBLIGATORIA per LISTA NASCITA / REGALO");?>
		</div>
	</div>
</div>

<div class="box_regalo uk-margin-remove-top uk-margin-medium-bottom">
	<div class="uk-margin-remove-top uk-grid uk-grid-medium uk-flex uk-flex-top" uk-grid>
		<div class="uk-margin-remove-top first_of_grid uk-width-1-1 uk-width-2-3@m uk-margin uk-margin-remove-bottom">
			<label class="uk-form-label"><?php echo gtext("Dedica")?></label>
		
			<?php echo Html_Form::textarea("dedica",$values["dedica"],"uk-textarea class_dedica",null,"placeholder='".gtext("Scrivi qui la dedica..")."'");?>
		</div>
		<div class="uk-margin-remove-top uk-width-1-1 uk-width-1-3@m uk-margin uk-margin-remove-bottom">
			<label class="uk-form-label"><?php echo gtext("Firma");?></label>
			<div class="uk-form-controls">
				<?php echo Html_Form::input("firma",$values['firma'],"uk-input class_firma",null,"placeholder='".gtext("Scrivi qui la firma..")."'");?>
			</div>
		</div>
	</div>
</div>

<?php echo Html_Form::hidden("regalo",$values["regalo"]);?>
<?php } ?>
