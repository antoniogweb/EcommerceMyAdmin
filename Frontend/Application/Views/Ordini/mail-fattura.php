<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtext("Gentile cliente, in allegato la fattura relativa all' ordine #",false);?><?php echo $ordine["id_o"];?> <?php echo gtext("del",false);?> <?php echo smartDate($ordine["data_creazione"]);?>.</p>

<p><?php echo gtext("Cordiali saluti",false);?><br /><?php echo ImpostazioniModel::$valori["nome_sito"];?></p>
