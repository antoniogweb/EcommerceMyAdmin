<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<script src="<?php echo $this->baseUrlSrc."/admin/Frontend/Public/Js/uikit/"?>uikit.min.js"></script>

<?php if (v("codice_js_ok_cookie")) {
	echo htmlentitydecode(v("codice_js_ok_cookie"));
} ?>
