<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

			<div class='row'>
				<form class="formClass" method="POST" action="<?php echo $this->baseUrl."/".$this->controller."/form/$type/$id".$this->viewStatus;?>" enctype="multipart/form-data">
					<div class='col-md-8'>
						<?php if (isset($form["id_car"])) { ?>
						<?php echo $form["id_car"];?>
						<?php } ?>
						
						<?php echo $form["titolo"];?>
						
						<?php if (isset($form["immagine"])) { ?>
						<?php echo $form["immagine"];?>
						<?php } ?>
						
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
				</form>
			</div>
		</div>
	</div>
</div>

<div>
	<div>
		<div>
			<?php if (isset($contenutiTradotti) && count($contenutiTradotti) > 0 && count(BaseController::$traduzioni) > 0) { ?>
			<div class="panel panel-info">
				<div class="panel-heading">
					Traduzioni
				</div>
				<div class="panel-body">
					<?php
					$section = "-cv-";
					$nascondiLink = $nascondiAlias = true;
					include($this->viewPath("pages_traduzioni"));?>
				</div>
			</div>
			<?php } ?>
