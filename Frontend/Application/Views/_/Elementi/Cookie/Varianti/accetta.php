<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtextPlain("Questo sito utilizza cookie per migliorare la tua esperienza di navigazione.");?><br /><?php echo gtextPlain("Cliccando su ACCETTO o continuando a navigare ne consenti l'utilizzo.");?>

<?php if (isset($tipiPagina["COOKIE"])) { ?>
<a class="" href="<?php echo $this->baseUrl."/".getUrlAlias($tipiPagina["COOKIE"]);?>"><?php echo gtextPlain("Ulteriori informazioni");?></a>
<?php } ?></p>

<div class="uk-margin"><a class="ok_cookies uk-button uk-button-primary" title="<?php echo gtextAttr("accetto", false);?>" href="#"><?php echo gtext("Accetta");?></a></div>
