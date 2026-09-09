<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<script type="text/javascript">
$(document).ready(function(){
	$("body").on("change", "[name='id_lista_tipo']", function(e){
		reloadPage();
	});
});
</script>

<?php if ($id) { ?>
<div class="well">
	<?php echo ListeregaloModel::specchietto($id, "<br />", false);?>
</div>
<?php } ?>

<?php echo $main;?>

