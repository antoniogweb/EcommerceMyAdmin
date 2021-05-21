<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class='row'>
	<form class="formClass" method="POST" action="<?php echo $this->baseUrl."/".$this->controller."/form/$type/$id".$this->viewStatus;?>" enctype="multipart/form-data">
		<div class='col-md-4'>
			<?php if ($this->viewArgs["procedi"]) { ?>
				<?php if (isset($form["peso"])) echo $form["peso"];?>
				<?php if (isset($form["prezzo"])) echo $form["prezzo"];?>
				<?php if (isset($form["prezzo_ivato"])) echo $form["prezzo_ivato"];?>
			<?php } else { ?>
				<?php if (isset($form["nazione"])) echo $form["nazione"];?>
				<input type="hidden" name="procedi" value="1" />
			<?php } ?>
			
			<?php if ($type === "update") { ?>
			<input class="varchar_input form-control" type="hidden" value="<?php echo $id;?>" name="id_n">
			<?php } ?>
			
			<?php if (!$this->viewArgs["procedi"]) {
				$type = "g";
			} ?>
			<div class="submit_entry">
				<span class="submit_entry_Salva">
					<button id="<?php echo $type;?>Action" class="btn btn-success" name="<?php echo $type;?>Action" type="submit">
						<?php if ($this->viewArgs["procedi"]) { ?>
						<?php echo gtext("Salva");?>
						<?php } else { ?>
						<?php echo gtext("Procedi");?> <i class="fa fa-arrow-right"></i>
						<?php } ?>
					</button>
					<input type="hidden" value="Salva" name="<?php echo $type;?>Action">
				</span>
			</div>
		</div>
	</form>
</div>
