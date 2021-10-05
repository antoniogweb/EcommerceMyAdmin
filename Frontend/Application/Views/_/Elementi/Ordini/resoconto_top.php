<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (strcmp($tipoOutput,"mail_al_negozio") !== 0 and !isset($_GET["n"])) { ?>
<p class="uk-text-muted uk-text-small"><?php echo gtext("Grazie! Il suo ordine è stato ricevuto e verrà processato al più presto.");?></p>
<?php } ?>

<?php if (strcmp($tipoOutput,"mail_al_negozio") === 0 ) { ?>
<p class="uk-text-muted uk-text-small"><?php echo gtext("Può controllare l'ordine al", false); ?> <a href="<?php echo $baseUrl."resoconto-acquisto/".$ordine["id_o"]."/".$ordine["cart_uid"]."/".$ordine["admin_token"];?>?n=y"><?php echo gtext("seguente indirizzo web", false); ?></a>.</p>
<?php } ?>

<?php if (strcmp($tipoOutput,"mail_al_cliente") === 0 ) { ?>
<p class="uk-text-muted uk-text-small"><?php echo gtext("Può controllare in qualsiasi momento i dettagli dell'ordine al");?> <a href="<?php echo $baseUrl."resoconto-acquisto/".$ordine["id_o"]."/".$ordine["cart_uid"]."/token";?>?n=y"><?php echo gtext("seguente indirizzo web");?></a>.</p>
<?php } ?>

<?php if (strcmp($tipoOutput,"web") === 0 and !isset($_GET["n"])) { ?>
<p class="uk-text-muted uk-text-small"><?php echo gtext("Controlli la sua casella di posta elettronica, le è stata inviata una mail con il resoconto dell'ordine.");?></p>
<?php } ?>
