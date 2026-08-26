<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtextPlain("Gentile cliente, i prodotti acquistati con l'ordine #",false);?><?php echo $ordine["id_o"];?> <?php echo gtextPlain("del");?> <?php echo smartDate($ordine["data_creazione"]);?> <?php echo gtextPlain("sono stati spediti all'indirizzo indicato",false);?>.</p>

<p><?php echo gtextPlain("Cordiali saluti",false);?><br /><?php echo ImpostazioniModel::$valori["nome_sito"];?></p>
