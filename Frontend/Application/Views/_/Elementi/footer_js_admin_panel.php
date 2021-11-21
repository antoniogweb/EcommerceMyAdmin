<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_gestione_fasce_frontend") && User::$adminLogged && isset($_SESSION["modalita_edit_fronted"])) { ?>
	
	<div style="display:none;" class="class_id_contenuto"><?php echo ContenutiModel::$idElementoCorrente;?></div>
	<div style="display:none;" class="class_tipo_elemento"><?php echo ContenutiModel::$tipoElementoCorrente;?></div>
	
<?php } ?>
