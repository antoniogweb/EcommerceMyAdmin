<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="panel panel-info">
	<div class="panel-heading">
		<?php echo gtext("Visibilità");?>
	</div>
	<div class="panel-body">
		<?php echo $form["attivo"];?>
		
		<?php echo $form["in_evidenza"];?>
		
		<?php include($this->viewPath("pages_link"));?>
	</div>
</div>
