<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="uk-margin">
	<?php echo Html_Form::radio("spedisci_dati_fatturazione",$values["spedisci_dati_fatturazione"],"Y","imposta_fatt","none","spedizione-usa-fatturazione");?> <label for="spedizione-usa-fatturazione"><?php echo gtextPlain("Utilizza gli stessi dati per fatturazione e spedizione");?></label>
</div>
<div class="uk-margin">
	<?php echo Html_Form::radio("spedisci_dati_fatturazione",$values["spedisci_dati_fatturazione"],"N","imposta_fatt","none","spedizione-dati-diversi");?> <label for="spedizione-dati-diversi"><?php echo gtextPlain("Utilizza dati diversi per la spedizione");?></label>
</div>

<div class="blocco_spedizione_non_loggato">
	
	
	<div class="blocco_checkout">
		<?php include(tpf("Regusers/form_dati_spedizione.php"));?>
	</div>
</div>
 
