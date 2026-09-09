<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (!defined('EDITOR_VISUALE_ASSETS_INCLUDED')) { ?>
	<?php define('EDITOR_VISUALE_ASSETS_INCLUDED', true); ?>
	<link rel="stylesheet" href="<?php echo $this->baseUrlSrc;?>/Public/Js/vendor/jodit/jodit.min.css">
	<script src="<?php echo $this->baseUrlSrc;?>/Public/Js/vendor/jodit/jodit.min.js"></script>
	<script src="<?php echo $this->baseUrlSrc;?>/Public/Js/functions.visual-editor.js?v=<?php echo rand(1,100000);?>"></script>
<?php } ?>
