<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!isset($skipUikitJs)) { ?>
<script <?php if (v("usa_defear")) { ?>defer<?php } ?> src="<?php echo tpf("Public/Js/uikit/uikit.min.js", true);?>"></script>
<?php } ?>