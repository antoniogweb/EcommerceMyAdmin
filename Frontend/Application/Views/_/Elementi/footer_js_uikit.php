<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!isset($skipUikitIcons)) { ?>
<script <?php if (v("usa_defear")) { ?>defer<?php } ?> src="<?php echo tpf("Public/Js/uikit/uikit-icons.min.js", true);?>"></script>
<?php } ?>