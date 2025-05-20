<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php echo gtext("Gentile");?> [NOME_CREATORE_LISTA],<br />
<?php echo gtext("alcuni dei prodotti che hai inserito nella tua lista");?> <b>[TITOLO_LISTA]</b> <?php echo gtext("non sono più acquistabili.");?><br />
<a href="[LINK_LISTA]"><?php echo gtext("Verifica i prodotti della tua lista");?></a><br />
<br />
<?php echo gtext("Puoi gestire i prodotti della tua lista loggandoti nell'area riservata al seguente")?> <a href="[LINK_AREA_RISERVATA]"><?php echo gtext("indirizzo web");?></a><br />
<br />

<?php echo gtext("Ecco i prodotti non più acquistabili:");?><br />
[ELENCO_PRODOTTI]

<?php echo gtext("Per qualsiasi informazione o chiarimento non esitare a contattarci, il nostro staff è a tua completa disposizione.");?>
