<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (isset($form["descrizione"])) { ?>
<script type="text/javascript" src="<?php echo $this->baseUrlSrc?>/Public/Js/tiny_mce/jquery.tinymce.js"></script>

<script type="text/javascript">
$().ready(function() {
	$('textarea').tinymce(tiny_editor_config);
	//$(".display_none").css({ 'display' : 'none' });
});
</script>
<?php } ?>

<form class="formClass form_class_contenuto" method="POST" action="<?php echo $this->baseUrl."/".$this->controller."/form/$type/$id".$this->viewStatus;?>" enctype="multipart/form-data">
	<div class='row'>
		<div class='col-md-6'>
			<?php echo $form["titolo"];?>
			
			<?php if (isset($form["tipo_layer"])) { ?>
			<?php echo $form["tipo_layer"];?>
			<?php } ?>
			
			<?php if (isset($form["immagine_1"])) { ?>
			<?php echo $form["immagine_1"];?>
			<?php } ?>
			
			<?php if (isset($form["immagine_2"])) { ?>
			<?php echo $form["immagine_2"];?>
			<?php } ?>
			
			<?php if (isset($form["margine_superiore"])) { ?>
			<?php echo $form["margine_superiore"];?>
			<?php } ?>
			
			<?php if (isset($form["margine_inferiore"])) { ?>
			<?php echo $form["margine_inferiore"];?>
			<?php } ?>
			
			<?php if (isset($form["descrizione"])) { ?>
			<?php echo $form["descrizione"];?>
			<?php } ?>
			
			<?php if (isset($form["coordinate"])) { ?>
			<?php echo $form["coordinate"];?>
			<?php } ?>
		</div>
		
		<div class='col-md-6'>
			<?php if (isset($form["id_tipo"])) { ?>
			<?php echo $form["id_tipo"];?>
			<?php } ?>
			
			<?php echo $form["lingua"];?>
			
			<?php echo $form["attivo"];?>
			
			<?php if (isset($form["link_id_page"]) || isset($form["link_id_c"]) || isset($form["link_id_marchio"]) || isset($form["link_id_tag"])) { ?>
			<div class="well">
				<h3>Link a contenuto</h3>
				<div class='row'>
				<?php if (isset($form["link_id_page"])) { ?>
					<div class='col-md-6'>
						<?php echo $form["link_id_page"];?>
					</div>
				<?php } ?>
				
				<?php if (isset($form["link_id_c"])) { ?>
					<div class='col-md-6'>
						<?php echo $form["link_id_c"];?>
					</div>
				<?php } ?>
				
				<?php if (isset($form["link_id_marchio"])) { ?>
					<div class='col-md-6'>
						<?php echo $form["link_id_marchio"];?>
					</div>
				<?php } ?>
				
				<?php if (isset($form["link_id_tag"])) { ?>
					<div class='col-md-6'>
						<?php echo $form["link_id_tag"];?>
					</div>
				<?php } ?>
				</div>
			</div>
			<?php } ?>
			
			<?php if (isset($form["posizione"]) || isset($form["animazione"])) { ?>
			<div class="well">
				<h3>Posizione e animazione</h3>
				<div class='row'>
					<?php if (isset($form["posizione"])) { ?>
					<div class='col-md-6'>
						<?php echo $form["posizione"];?>
					</div>
					<?php } ?>
					<?php if (isset($form["animazione"])) { ?>
					<div class='col-md-6'>
						<?php echo $form["animazione"];?>
					</div>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
			
			<?php if (isset($form["posizione_s"]) || isset($form["visibile_s"])) { ?>
			<div class="well">
				<h3>Gestione responsive</h3>
				<?php if (isset($form["posizione_xs"]) || isset($form["visibile_xs"])) { ?>
				<div class='row'>
					<?php if (isset($form["posizione_xs"])) { ?>
					<div class='col-md-6'>
						<?php echo $form["posizione_xs"];?>
					</div>
					<?php } ?>
					<?php if (isset($form["visibile_xs"])) { ?>
					<div class='col-md-6'>
						<?php echo $form["visibile_xs"];?>
					</div>
					<?php } ?>
				</div>
				<?php } ?>
				
				<?php if (isset($form["posizione_s"]) || isset($form["visibile_s"])) { ?>
				<div class='row'>
					<?php if (isset($form["posizione_s"])) { ?>
					<div class='col-md-6'>
						<?php echo $form["posizione_s"];?>
					</div>
					<?php } ?>
					<?php if (isset($form["visibile_s"])) { ?>
					<div class='col-md-6'>
						<?php echo $form["visibile_s"];?>
					</div>
					<?php } ?>
				</div>
				<?php } ?>
				
				<?php if (isset($form["posizione_m"]) || isset($form["visibile_m"])) { ?>
				<div class='row'>
					<?php if (isset($form["posizione_m"])) { ?>
					<div class='col-md-6'>
						<?php echo $form["posizione_m"];?>
					</div>
					<?php } ?>
					<?php if (isset($form["visibile_m"])) { ?>
					<div class='col-md-6'>
						<?php echo $form["visibile_m"];?>
					</div>
					<?php } ?>
				</div>
				<?php } ?>
				
				<?php if (isset($form["posizione_l"]) || isset($form["visibile_l"])) { ?>
				<div class='row'>
					<?php if (isset($form["posizione_l"])) { ?>
					<div class='col-md-6'>
						<?php echo $form["posizione_l"];?>
					</div>
					<?php } ?>
					<?php if (isset($form["visibile_l"])) { ?>
					<div class='col-md-6'>
						<?php echo $form["visibile_l"];?>
					</div>
					<?php } ?>
				</div>
				<?php } ?>
				
				<?php if (isset($form["posizione_xl"]) || isset($form["visibile_xl"])) { ?>
				<div class='row'>
					<?php if (isset($form["posizione_xl"])) { ?>
					<div class='col-md-6'>
						<?php echo $form["posizione_xl"];?>
					</div>
					<?php } ?>
					<?php if (isset($form["visibile_xl"])) { ?>
					<div class='col-md-6'>
						<?php echo $form["visibile_xl"];?>
					</div>
					<?php } ?>
				</div>
				<?php } ?>
			</div>
			<?php } ?>
		</div>
	</div>
	
	<?php if ($type === "update") { ?>
	<input class="varchar_input form-control" type="hidden" value="<?php echo $id;?>" name="id_ct">
	<?php } ?>
	
	<div class="submit_entry">
		<span class="submit_entry_Salva">
			<button id="<?php echo $type;?>Action" class="btn btn-success" name="<?php echo $type;?>Action" type="submit">Salva</button>
			<input type="hidden" value="Salva" name="<?php echo $type;?>Action">
		</span>
	</div>
</form>
