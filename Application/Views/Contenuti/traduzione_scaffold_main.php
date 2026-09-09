<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php include($this->viewPath("editor_visuale"));?>
<script type="text/javascript">
	$().ready(function() {
		editorVisuale('[name="descrizione"]');
		//$(".display_none").css({ 'display' : 'none' });
	});
</script>

<?php echo $main;?>
