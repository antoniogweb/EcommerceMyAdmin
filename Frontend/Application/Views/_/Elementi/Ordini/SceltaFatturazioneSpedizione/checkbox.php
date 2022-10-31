<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="spedizione_box uk-width-1-1">
	<div class="uk-flex uk-flex-middle">
		<div>
			<?php echo Html_Form::checkbox("spedizione_come_fatturazione",$values["spedisci_dati_fatturazione"],"Y","imposta_fatt","none");?>
		</div>
		<div class="uk-margin-left uk-text-small">
			<?php echo gtext("Coincide con i dati di fatturazione");?>
		</div>
	</div>
</div>

<?php echo Html_Form::hidden("spedisci_dati_fatturazione",$values["spedisci_dati_fatturazione"]);?>

<div class="blocco_spedizione_non_loggato uk-padding-remove-top">
	<?php include(tpf("Regusers/form_dati_spedizione.php"));?>
</div>
 
