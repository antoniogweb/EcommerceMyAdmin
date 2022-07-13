<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="panel panel-info">
	<div class="panel-heading">
		<?php echo gtext("Contatti + Social");?>
	</div>
	<div class="panel-body">
		<div class='row'>
			<div class='col-md-4'>
				<?php echo $form["email_contatto_evento"];?>
			</div>
			<div class='col-md-4'>
				<?php echo $form["telefono_contatto_evento"];?>
			</div>
			<div class='col-md-4'>
				<?php echo $form["indirizzo_localita_evento"];?>
			</div>
			<div class='col-md-4'>
				<?php echo $form["localita_evento"];?>
			</div>
			<div class='col-md-4'>
				<?php echo $form["id_regione"];?>
			</div>
			<div class='col-md-4'>
				<?php echo $form["url"];?>
			</div>
		</div>
		<div class='row'>
			<div class='col-md-4'>
				<?php echo $form["link_pagina_facebook"];?>
			</div>
			<div class='col-md-4'>
				<?php echo $form["link_pagina_twitter"];?>
			</div>
			<div class='col-md-4'>
				<?php echo $form["link_pagina_youtube"];?>
			</div>
		</div>
	</div>
</div>
