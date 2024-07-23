<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$nazioniFrontend = NazioniModel::g(false)->selectCodiciAttivi();
$nazioneUrlVediOrdine = in_array($ordine["nazione"], $nazioniFrontend) ? $ordine["nazione"] : v("nazione_default");
$linguaNazioneUrl = v("attiva_nazione_nell_url") ? $ordine["lingua"]."_".strtolower($nazioneUrlVediOrdine) : $ordine["lingua"];
?>
<tr>
	<td><?php echo gtext("N° Ordine");?>:</td>
	<td><b>#<?php echo $ordine["id_o"];?></b> 
		<?php if (!isset($nascondiVediLatoCliente)) { ?>
		<a <?php if (partial()) { ?>target="_blank"<?php } ?> class="<?php if (!partial()) { ?>iframe<?php } ?> pull-right help_ordine_lato_cliente" href="<?php echo Domain::$name."/".$linguaNazioneUrl."/resoconto-acquisto/".$ordine["id_o"]."/".$ordine["cart_uid"]."/".$ordine["admin_token"]."?n=y";?>"><i class="fa fa-eye"></i> <?php echo gtext("Vedi ordine lato cliente");?></a>
		<?php } ?>
	</td>
</tr>
