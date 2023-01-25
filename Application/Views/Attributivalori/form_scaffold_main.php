<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

			<div class='row'>
				<form class="formClass" method="POST" action="<?php echo $this->baseUrl."/".$this->controller."/form/$type/$id".$this->viewStatus;?>" enctype="multipart/form-data">
					<div class='col-md-8'>
						<?php echo $form["titolo"];?>
						
						<?php if (isset($form["alias"])) { ?>
						<?php echo $form["alias"];?>
						<?php } ?>
						
						<?php if (isset($form["immagine"])) { ?>
						<?php echo $form["immagine"];?>
						<?php } ?>
						
						<?php if (isset($form["colore"])) { ?>
						<?php echo $form["colore"];?>
						<?php } ?>
						
						<?php if ($type === "update") { ?>
						<input class="varchar_input form-control" type="hidden" value="<?php echo $id;?>" name="id_n">
						<?php } ?>
						
						<?php include($this->viewPath("form_submit_button"));?>
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
