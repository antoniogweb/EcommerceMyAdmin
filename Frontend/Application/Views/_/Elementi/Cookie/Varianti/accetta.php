<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtext("Questo sito utilizza cookie per migliorare la tua esperienza di navigazione.");?><br /><?php echo gtext("Cliccando su ACCETTO o continuando a navigare ne consenti l'utilizzo.");?>

<?php if (isset($tipiPagina["COOKIE"])) { ?>
<a class="" href="<?php echo $this->baseUrl."/".getUrlAlias($tipiPagina["COOKIE"]);?>"><?php echo gtext("Ulteriori informazioni");?></a>
<?php } ?></p>

<div class="uk-margin"><a class="ok_cookies uk-button uk-button-primary" title="<?php echo gtext("accetto", false);?>" href="#"><?php echo gtext("Accetta");?></a></div>
