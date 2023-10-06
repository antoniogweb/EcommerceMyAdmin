<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<p><?php echo gtext("Ecco l'elenco completo dei prodotti spediti. Se mancano dei prodotti, questi verranno inseriti in una nuova spedizione che verrà effettuata quanto prima.
");?></p>
<?php
$noPrezziProdottiMail = true;
$conLinkPerFeedback = true;
include(tpf("Elementi/Ordini/Resoconto/Prodotti/con_immagine_tabella.php"));?>
