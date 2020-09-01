<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class='row'>
	<form class="formClass" method="POST" action="<?php echo $this->baseUrl."/".$this->controller."/form/$type/$id".$this->viewStatus;?>" enctype="multipart/form-data">
		<div class='col-md-6'>
			<div class="panel panel-default">
				<div class="panel-heading">
					Dettagli
				</div>
				<div class="panel-body">
					<div class='row'>
						<div class='col-md-6'>
							<?php echo $form["titolo"];?>
						</div>
						<div class='col-md-6'>
							<?php echo $form["animazione"];?>
						</div>
					</div>
					
					<?php echo $form["testo"];?>
					
					<?php echo $form["url"];?>
					
					<div class='row'>
						<div class='col-md-3'>
							<?php echo $form["larghezza_1"];?>
						</div>
						<div class='col-md-3'>
							<?php echo $form["larghezza_2"];?>
						</div>
						<div class='col-md-3'>
							<?php echo $form["larghezza_3"];?>
						</div>
						<div class='col-md-3'>
							<?php echo $form["larghezza_4"];?>
						</div>
					</div>
					
					<div class='row'>
						<div class='col-md-3'>
							<?php echo $form["x_1"];?>
						</div>
						<div class='col-md-3'>
							<?php echo $form["x_2"];?>
						</div>
						<div class='col-md-3'>
							<?php echo $form["x_3"];?>
						</div>
						<div class='col-md-3'>
							<?php echo $form["x_4"];?>
						</div>
					</div>
					
					<div class='row'>
						<div class='col-md-3'>
							<?php echo $form["y_1"];?>
						</div>
						<div class='col-md-3'>
							<?php echo $form["y_2"];?>
						</div>
						<div class='col-md-3'>
							<?php echo $form["y_3"];?>
						</div>
						<div class='col-md-3'>
							<?php echo $form["y_4"];?>
						</div>
					</div>
					
					<?php if ($type === "update") { ?>
					<input class="varchar_input form-control" type="hidden" value="<?php echo $id;?>" name="id_n">
					<?php } ?>
					
					<div class="submit_entry">
						<span class="submit_entry_Salva">
							<button id="<?php echo $type;?>Action" class="btn btn-success" name="<?php echo $type;?>Action" type="submit">Salva</button>
							<input type="hidden" value="Salva" name="<?php echo $type;?>Action">
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class='col-md-6'>
			<div class="panel panel-primary">
				<div class="panel-heading">
					Immagine
				</div>
				<div class="panel-body">
					<?php echo $form["immagine"];?>
				</div>
			</div>
		</div>
	</form>
</div>
