<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtextPlain("Gentile cliente, in allegato la fattura relativa all' ordine #",false);?><?php echo $ordine["id_o"];?> <?php echo gtextPlain("del",false);?> <?php echo smartDate($ordine["data_creazione"]);?>.</p>

<p><?php echo gtextPlain("Cordiali saluti",false);?><br /><?php echo ImpostazioniModel::$valori["nome_sito"];?></p>
