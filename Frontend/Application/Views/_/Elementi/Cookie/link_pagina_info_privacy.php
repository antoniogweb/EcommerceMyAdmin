<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (isset(PagesModel::$tipiPaginaId["PRIVACY"])) { ?>
<h3><?php echo gtext("Informativa sul trattamento dei dati");?></h3>
<?php echo gtext("Leggi l'informativa sul trattamento dei tuoi dati alla pagina delle");?> <a href="<?php echo Url::getRoot().getUrlAlias(PagesModel::$tipiPaginaId["PRIVACY"]);?>"><?php echo gtext("condizioni di privacy");?></a>
<?php } ?>
