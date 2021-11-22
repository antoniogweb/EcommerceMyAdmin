<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_gestione_fasce_frontend") && User::$adminLogged && isset($_SESSION["modalita_edit_fronted"])) { ?>
	
	<div style="display:none;" class="class_id_contenuto"><?php echo ContenutiModel::$idElementoCorrente;?></div>
	<div style="display:none;" class="class_tipo_elemento"><?php echo ContenutiModel::$tipoElementoCorrente;?></div>
	
	<?php if (v("attiva_elementi_tema")) {
		$struttura = ElementitemaModel::preparaStrutturaVarianti();
		
// 		echo "<pre>";
// 		print_r($struttura);
// 		echo "</pre>";
	?>
	<div style="display:none;" class="class_json_varianti"><?php echo json_encode($struttura);?></div>
	<?php } ?>
	
	<?php if (v("permetti_cambio_tema")) { ?>
	<div style="display:none;" class="class_tema_default"><?php echo v("theme_folder");?></div>
	<?php } ?>
<?php } ?>
