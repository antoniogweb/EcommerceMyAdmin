<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (PagesModel::$currentTipoPagina == "COOKIE")
	include(tpf("Elementi/Cookie/link_pagina_info_privacy.php"));
?>

<div style="bottom:0px !important;" class="<?php echo v("classe_ext_cookies_conf")?>" id="segnalazione_cookies_ext">
	<div id="segnalazione_cookies">
		<?php include(tpf("Elementi/Cookie/scelta_tipo_form.php"));?>
	</div>
</div>
