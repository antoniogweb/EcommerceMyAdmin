<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if ($ordine["lingua"] != v("lingua_default_frontend") || $ordine["nazione_navigazione"] != v("nazione_default") || $ordine["nazione_spedizione"] != v("nazione_default")) { ?>
<tr>
	<td><?php echo v("attiva_ip_location") ? gtext("Lingua / Nazione navigazione / Nazione spedizione") : gtext("Lingua / Nazione URL / Nazione spedizione");?>:</td>
	<td><b><?php echo LingueModel::getTitoloDaCodice($ordine["lingua"])." / ".findTitoloDaCodice($ordine["nazione_navigazione"])." / ".findTitoloDaCodice($ordine["nazione_spedizione"]);?></b></td>
</tr>
<?php } ?>
