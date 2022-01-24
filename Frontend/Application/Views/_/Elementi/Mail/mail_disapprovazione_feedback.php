<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtext("Gentile cliente, la valutazione da lei inserita relativamente al prodotto",false);?>

<a href="[LINK_PRODOTTO]">[NOME_PRODOTTO]</a>
<?php echo gtext("è stata rifiutata.",false);?>
</p>

<?php if (trim(strip_tags(htmlentitydecode(MailordiniModel::$variabiliTema["COMMENTO"])))) { ?>
<p><b><?php echo gtext("Ecco il commento del negozio");?>:</b></p>

</br>
<div style="padding:10px;background-color:#EEE;"><?php echo strip_tags(htmlentitydecode(MailordiniModel::$variabiliTema["COMMENTO"]));?></div>
<?php } ?>
<p><br /><?php echo gtext("Cordiali saluti", false);?>.</p>
