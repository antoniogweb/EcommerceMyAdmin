<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php $idCookie = PagineModel::gTipoPagina("COOKIE"); ?>
<?php if ($idCookie) { ?>
<?php echo gtext("Puoi sempre tornare indietro e modificare le tue impostazioni nella pagina delle"); ?> 
<a target="_blank" href="<?php echo Url::getRoot().getUrlAlias($idCookie);?>"><?php echo gtext("condizioni sui cookie."); ?></a>
<?php } else { ?>
<?php echo gtext("Puoi sempre tornare indietro e modificare le tue impostazioni nella pagina delle condizioni sui cookie."); ?>
<?php } ?>
<?php echo gtext("Nella stessa pagina troverai informazioni sul responsabile della gestione dei tuoi dati, il trattamento dei dati personali e le finalità di tale trattamento.")?>
