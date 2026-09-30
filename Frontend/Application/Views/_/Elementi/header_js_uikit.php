<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!isset($skipUikitJs)) { ?>
<script <?php if (v("usa_defear")) { ?>defer<?php } ?> src="<?php echo $this->baseUrlSrc."/admin/Frontend/Public/Js/uikit/"?>uikit.min.js"></script>
<?php } ?>
