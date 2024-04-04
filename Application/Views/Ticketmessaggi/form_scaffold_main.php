<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<script type="text/javascript" src="<?php echo $this->baseUrlSrc?>/Public/Js/tiny_mce/jquery.tinymce.js"></script>

<script type="text/javascript">
$().ready(function() {
	$('textarea').tinymce(tiny_editor_config);
});
</script>

<?php echo $main;?>

<!-- <br /><br /><br /> -->
