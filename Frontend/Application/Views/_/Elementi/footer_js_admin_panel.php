<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_gestione_fasce_frontend") && User::$adminLogged && isset($_GET[v("token_edit_frontend")]) && !User::$isPhone && !isset($_GET["em_edit_frontend"])) { ?>
	
	<div style="display:none;" class="class_id_contenuto"><?php echo ContenutiModel::$idElementoCorrente;?></div>
	<div style="display:none;" class="class_tipo_elemento"><?php echo ContenutiModel::$tipoElementoCorrente;?></div>
	<div style="display:none;" class="class_request_uri"><?php echo sanitizeAll($_SERVER['REQUEST_URI']);?></div>
	
<?php } ?>
