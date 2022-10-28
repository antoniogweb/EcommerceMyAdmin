<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

Un cliente ha inserito una valutazione ad un prodotto<br />
<?php if (isset($pagina) && !empty($pagina)) { ?>
Prodotto: <a href="<?php echo Url::getRoot().getUrlAlias($pagina["id_page"],$valoriEmail["id_c"]);?>"><?php echo $pagina["title"];?></a><br /><br />
<?php } ?>
<b>La valutazione non è attiva ed è da approvare o rifiutare.</b><br />
Può approvare o rifiutare tale valutazione nel pannello admin, dalla pagina del prodotto in questione.<br />
Ecco i dati del cliente:<br /><br />

Valutazione (numero stelle): <?php echo $valoriEmail["voto"];?><br />
Nome: <?php echo $valoriEmail["autore"];?><br />
Email: <?php echo $valoriEmail["email"];?><br />
Messaggio: <?php echo $valoriEmail["testo"];?>
