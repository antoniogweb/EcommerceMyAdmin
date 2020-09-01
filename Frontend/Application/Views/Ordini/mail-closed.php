<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtext("Gentile cliente, i prodotti acquistati con l'ordine #",false);?><?php echo $ordine["id_o"];?> <?php echo gtext("del");?> <?php echo smartDate($ordine["data_creazione"]);?> <?php echo gtext("sono stati spediti all'indirizzo indicato",false);?>.</p>

<p><?php echo gtext("Cordiali saluti",false);?><br /><?php echo ImpostazioniModel::$valori["nome_sito"];?></p>
