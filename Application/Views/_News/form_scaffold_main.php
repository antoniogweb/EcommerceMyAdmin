<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class='row'>
	<form class="formClass" method="POST" action="<?php echo $this->baseUrl."/".$this->controller."/form/$type/$id".$this->viewStatus;?>" enctype="multipart/form-data">
		<div class='col-md-8'>
			<div class="panel panel-info">
				<div class="panel-heading">
					
				</div>
				<div class="panel-body">
					<?php echo $form["titolo"];?>
					<?php echo $form["alias"];?>
					
					<?php echo $form["data_news"];?>
					
					<?php echo $form["descrizione"];?>
					
					<?php echo $form["keywords"];?>
					<?php echo $form["meta_description"];?>
					
					<?php if ($type === "update") { ?>
					<input class="varchar_input form-control" type="hidden" value="<?php echo $id;?>" name="id_n">
					<?php } ?>
					
					<div class="submit_entry" style="display:none;">
						<span class="submit_entry_Salva">
							<button id="<?php echo $type;?>Action" class="btn btn-success" name="<?php echo $type;?>Action" type="submit">Salva</button>
							<input type="hidden" value="Salva" name="<?php echo $type;?>Action">
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class='col-md-4'>
			<div class="panel panel-info">
				<div class="panel-heading">
					Visibilità
				</div>
				<div class="panel-body">
					<?php echo $form["attivo"];?>
				</div>
			</div>
		</div>
		<div class='col-md-4'>
			<div class="panel panel-info">
				<div class="panel-heading">
					Immagine
				</div>
				<div class="panel-body">
					<?php echo $form["immagine"];?>
				</div>
			</div>
		</div>
		<div class='col-md-4'>
			<div class="panel panel-info">
				<div class="panel-heading">
					Documento
				</div>
				<div class="panel-body">
					<?php echo $form["documento"];?>
				</div>
			</div>
		</div>
	</form>
</div>