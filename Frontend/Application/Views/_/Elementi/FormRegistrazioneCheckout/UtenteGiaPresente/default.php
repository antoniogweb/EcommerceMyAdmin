<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$alertAnonimo = v("permetti_acquisto_anonimo") ? gtext("oppure decidere di completare l'acquisto come utente ospite.", false) : "";

echo gtext("La sua E-Mail è già presente nel nostro sistema, significa che è già registrato nel nostro sito web.",false)?> <br />
<?php echo gtext("Può eseguire il login (se non ricorda la password può impostarne una nuova al seguente",false);?> <a href='<?php echo Url::getRoot()."password-dimenticata";?>'><?php echo gtext("indirizzo web", false);?></a>) <?php echo $alertAnonimo;?>
<span class='evidenzia'>class_username</span>
<div class='evidenzia'>class_email</div>
<div class='evidenzia'>class_conferma_email</div>
