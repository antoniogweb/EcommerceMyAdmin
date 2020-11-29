<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtext("Gentile cliente, le confermiamo che il pagamento dell' ordine #",false);?><?php echo $ordine["id_o"];?> <?php echo gtext("del");?> <?php echo smartDate($ordine["data_creazione"]);?> <?php echo gtext("è andato a buon fine e che l'ordine è entrato in lavorazione",false);?>.</p>

<p><?php echo gtext("Cordiali saluti",false);?><br /><?php echo ImpostazioniModel::$valori["nome_sito"];?></p>
