<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php include($this->viewPath("editor_visuale"));?>

<script type="text/javascript">
$().ready(function() {
	editorVisuale('textarea');
});
</script>

<form class="formClass form_class_contenuto" method="POST" action="<?php echo $this->baseUrl."/".$this->controller."/form/$type/$id".$this->viewStatus;?>">
	<div class='row'>
		<div class='col-md-6'>
			<?php echo $form["id_page"] ?? "";?>
			
			<?php echo $form["autore"];?>
			
			<?php echo $form["data_feedback"];?>
			
			<?php echo $form["testo"];?>
			
			<?php echo $form["attivo"];?>
			
			<?php echo $form["voto"];?>
		</div>
	</div>
	
	<?php include($this->viewPath("form_submit_button"));?>
</form>
