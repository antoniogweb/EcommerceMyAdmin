<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (isset($ordine) && strcmp($ordine["stato"],"completed") === 0)
{
	$titoloPagina = gtext("Transazione effettuata con successo");
	$statoOrdine = "OkEcommerce";
}
else if (isset($ordine) && $conclusa)
{
	$titoloPagina = gtext("Transazione effettuata con successo");
	$statoOrdine = "OkPaypal";
}
else
{
	$titoloPagina = gtext("Transazione in fase di verifica");
	$statoOrdine = "Attesa";
}
$noNumeroProdotti = true;
include(tpf("/Elementi/Pagine/page_top.php"));
?>
<div class="uk-text-left">
	<?php if ($statoOrdine == "OkEcommerce") { ?>
	
	<p><?php echo gtext("Grazie per il suo acquisto!")?></p>
	
	<p><?php echo gtext("Il pagamento dell'ordine")?> #<?php echo $ordine["id_o"];?> <?php echo gtext("è andato a buon fine")?>.</p>
	<?php } else if ($statoOrdine == "OkPaypal") { ?>
	
	<p><?php echo gtext("Grazie per il suo acquisto!")?></p>

	<p><?php echo gtext("La transazione dell'ordine")?> #<?php echo $ordine["id_o"];?> <?php echo gtext("è andata a buon fine. A breve le arriverà una mail con la conferma del pagamento.")?></p>
	
	<?php } else { ?>
	
	<p><?php echo gtext("Transazione in fase di verifica, lo stato del pagamento dell'ordine verrà aggiornato a breve.")?></p>
	
	<?php } ?>
	
	<p><a class="uk-button uk-button-text" href="<?php echo $this->baseUrl;?>"><?php echo gtext("Torna alla home");?></a></p>
</div>
<?php
include(tpf("/Elementi/Pagine/page_bottom.php"));
