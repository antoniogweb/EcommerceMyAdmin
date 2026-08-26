<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (isset($ordine) && strcmp($ordine["stato"],"completed") === 0)
{
	$titoloPagina = gtextPlain("Transazione effettuata con successo");
	$statoOrdine = "OkEcommerce";
}
else if (isset($ordine) && $conclusa)
{
	$titoloPagina = gtextPlain("Transazione effettuata con successo");
	$statoOrdine = "OkPaypal";
}
else
{
	$titoloPagina = gtextPlain("Transazione in fase di verifica");
	$statoOrdine = "Attesa";
}
$noNumeroProdotti = true;
include(tpf("/Elementi/Pagine/page_top.php"));
?>
<div class="uk-text-left">
	<?php if ($statoOrdine == "OkEcommerce") { ?>
	
	<p><?php echo gtextPlain("Grazie per il suo acquisto!")?></p>
	
	<p><?php echo gtextPlain("Il pagamento dell'ordine")?> #<?php echo $ordine["id_o"];?> <?php echo gtextPlain("è andato a buon fine")?>.</p>
	<?php } else if ($statoOrdine == "OkPaypal") { ?>
	
	<p><?php echo gtextPlain("Grazie per il suo acquisto!")?></p>

	<p><?php echo gtextPlain("La transazione dell'ordine")?> #<?php echo $ordine["id_o"];?> <?php echo gtextPlain("è andata a buon fine. A breve le arriverà una mail con la conferma del pagamento.")?></p>
	
	<?php } else { ?>
	
	<p><?php echo gtextPlain("Transazione in fase di verifica, riaggiorni la pagina tra quache minuto per controllare lo stato del pagamento dell'ordine.")?></p>
	
	<?php } ?>
	
	<p><a class="uk-button uk-button-text" href="<?php echo $this->baseUrl;?>"><?php echo gtextPlain("Torna alla home");?></a></p>
</div>
<?php
include(tpf("/Elementi/Pagine/page_bottom.php"));
