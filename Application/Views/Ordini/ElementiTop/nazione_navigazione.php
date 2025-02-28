<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$nazioneRilevanteIva = OrdiniModel::getNazioneRilevanteIvaOrdine($ordine);

if ($ordine["lingua"] != v("lingua_default_frontend") || $ordine["nazione_navigazione"] != v("nazione_default") || ($nazioneRilevanteIva && $nazioneRilevanteIva != v("nazione_default"))) { ?>
<tr>
	<td><?php echo v("attiva_ip_location") ? gtext("Lingua / Nazione navigazione / Nazione spedizione") : gtext("Lingua / Nazione URL / Nazione spedizione");?>:</td>
	<td><b><?php echo LingueModel::getTitoloDaCodice($ordine["lingua"])." / ".findTitoloDaCodice($ordine["nazione_navigazione"])." / ".findTitoloDaCodice($nazioneRilevanteIva);?></b></td>
</tr>
<?php } ?>
