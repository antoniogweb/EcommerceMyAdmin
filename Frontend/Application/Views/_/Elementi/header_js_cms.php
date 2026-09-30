<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php include(tpf("/Elementi/header_js_uikit.php"));?>

<?php if (v("codice_verifica_fbk")) {
	echo htmlentitydecode(v("codice_verifica_fbk"));
} ?>

<?php if (v("codice_js_ok_cookie")) {
	echo htmlentitydecode(v("codice_js_ok_cookie"));
} ?>
