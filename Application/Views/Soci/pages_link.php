<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (isset($urlPagina)) { ?>
<a class="pull-right label label-info" target="_blank" href="<?php echo Domain::$name."/it/$urlPagina";?>"><?php echo gtextPlain("Vedi pagina");?> <i class="fa fa-arrow-right"></i></a>
<?php } ?>
