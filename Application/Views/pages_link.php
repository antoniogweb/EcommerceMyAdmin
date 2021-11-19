<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (isset($urlPagina)) { ?>
<a class="pull-right label label-info" target="_blank" href="<?php echo Domain::$name."/it/$urlPagina";?>"><?php echo gtext("Vedi pagina");?> <i class="fa fa-arrow-right"></i></a>
<?php } ?>
<?php if (isset($urlPaginaEditFrontend)) { ?>
<a class="label label-success" target="_blank" href="<?php echo Domain::$name."/it/$urlPaginaEditFrontend";?>"><?php echo gtext("Edit pagina frontend");?> <i class="fa fa-pencil"></i></a>
<?php } ?>
