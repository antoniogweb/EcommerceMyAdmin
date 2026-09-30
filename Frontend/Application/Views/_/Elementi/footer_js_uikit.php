<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!isset($skipUikitIcons)) { ?>
<script <?php if (v("usa_defear")) { ?>defer<?php } ?> src="<?php echo $this->baseUrlSrc."/admin/Frontend/Public/Js/uikit/"?>uikit-icons.min.js"></script>
<?php } ?>