<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtextPlain("Gentile cliente, le confermiamo che il pagamento dell' ordine #",false);?><?php echo $ordine["id_o"];?> <?php echo gtextPlain("del");?> <?php echo smartDate($ordine["data_creazione"]);?> <?php echo gtextPlain("è andato a buon fine e che l'ordine è entrato in lavorazione",false);?>.</p>

<p><?php echo gtextPlain("Cordiali saluti",false);?><br /><?php echo ImpostazioniModel::$valori["nome_sito"];?></p>
