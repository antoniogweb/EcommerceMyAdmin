<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php $idCookie = PagineModel::gTipoPagina("COOKIE"); ?>
<?php if ($idCookie) { ?>
<?php echo gtextPlain("Puoi sempre tornare indietro e modificare le tue impostazioni nella pagina delle"); ?> 
<a target="_blank" href="<?php echo Url::getRoot().getUrlAlias($idCookie);?>"><?php echo gtextPlain("condizioni sui cookie."); ?></a>
<?php } else { ?>
<?php echo gtextPlain("Puoi sempre tornare indietro e modificare le tue impostazioni nella pagina delle condizioni sui cookie."); ?>
<?php } ?>
<?php echo gtextPlain("Nella stessa pagina troverai informazioni sul responsabile della gestione dei tuoi dati, il trattamento dei dati personali e le finalità di tale trattamento.")?>
