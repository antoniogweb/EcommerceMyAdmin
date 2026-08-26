<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtextPlain("Gentile cliente, l'ordine #",false);?><?php echo $ordine["id_o"];?> <?php echo gtextPlain("del");?> <?php echo smartDate($ordine["data_creazione"]);?> <?php echo gtextPlain("è stato annullato",false);?>.</p>

<p><?php echo gtextPlain("Cordiali saluti",false);?><br /><?php echo ImpostazioniModel::$valori["nome_sito"];?></p>
