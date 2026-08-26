<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtextPlain("Gentile");?> <?php echo RegusersModel::getNominativo($agente);?>,<br />
<?php echo gtextPlain("Il cliente");?> <b><?php echo RegusersModel::getNominativo($ordine);?></b> <?php echo gtextPlain("ha eseguito un ordine nel nostro negozio online utilizzando il tuo codice coupon");?> <b><?php echo $ordine["codice_promozione"];?></b>.</p>

<p><?php echo gtextPlain("Ecco i dettagli dell'ordine");?>:</p>

<?php include(tpf("Elementi/Ordini/resoconto_acquisto_dettagli_generali.php"));?>

<br />
<p><?php echo gtextPlain("Puoi trovare il resoconto completo degli ordini legati ai tuoi coupon nella tua area riservata al")?> <a href="<?php echo Url::getRoot()."ordini-collegati/";?>"><?php echo gtextPlain("seguente indirizzo web");?></a>.</p>
