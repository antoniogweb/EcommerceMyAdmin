<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if ($ordine["nazione_navigazione"] != v("nazione_default")) { ?>
<tr>
	<td><?php echo v("attiva_ip_location") ? gtext("Lingua / Nazione navigazione") : gtext("Lingua / Nazione URL");?>:</td>
	<td><b><?php echo LingueModel::getTitoloDaCodice($ordine["lingua"])." / ".findTitoloDaCodice($ordine["nazione_navigazione"]);?></b></td>
</tr>
<?php } ?>
