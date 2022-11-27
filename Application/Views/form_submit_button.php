<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="submit_entry">
	<span class="submit_entry_Salva">
		<button id="<?php echo $type;?>Action" class="btn btn-success make_spinner" name="<?php echo $type;?>Action" type="submit"><i class="fa fa-save"></i> <?php echo gtext("Salva");?></button>
		<input type="hidden" value="Salva" name="<?php echo $type;?>Action">
	</span>
</div>
