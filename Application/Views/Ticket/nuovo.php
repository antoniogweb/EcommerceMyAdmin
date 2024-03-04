<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<section class="content-header">
	<h1><?php echo gtext("Creazione nuovo ticket");?></h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border main help_resoconto">
					<div class="callout callout-info"><?php echo gtext("Crea un nuovo ticket partendo da un cliente esistente.");?><br /><?php echo gtext("Se non selezioni alcun cliente, verrai automaticamente diretto alla creazione di un nuovo cliente.");?></div>
					<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/".$this->action.$this->viewStatus;?>'  method='GET'>
						<span select2="/regusers/main?esporta_json&formato_json=select2"><?php echo Html_Form::select("id_user","",$clienti,"","","yes","style='min-width:400px;'");?></span>
						
						<button class="submit_file btn btn-success btn-sm make_spinner" type="submit" name="nuovoAction" value="Aggiungi"><?php echo gtext("Procedi");?> <i class="fa fa-arrow-right"></i></button>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
