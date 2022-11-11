<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<script <?php if (v("usa_defear")) { ?>defer<?php } ?> src="<?php echo $this->baseUrlSrc."/admin/Frontend/Public/Js/uikit/"?>uikit.min.js"></script>

<?php if (v("codice_verifica_fbk")) {
	echo htmlentitydecode(v("codice_verifica_fbk"));
} ?>

<?php if (v("codice_js_ok_cookie")) {
	echo htmlentitydecode(v("codice_js_ok_cookie"));
} ?>
