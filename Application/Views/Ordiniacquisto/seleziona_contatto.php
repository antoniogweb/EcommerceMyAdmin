<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<section class="content-header">
	<h1><?php echo gtext("Seleziona il contatto a cui inviare l'ordine di acquisto");?></h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">	
			<div class="box">
				<div class="box-header with-border main help_storico">
					<div class="input-group">
						<?php echo Html_Form::select("id_fornitore_contatto","",$contatti,"form-control select_id_fornitore_contatto_invia_pdf",null,"yes");?>
						<span class="input-group-btn">
							<button
								type="button"
								class="btn btn-warning invia_pdf_ordine_acquisto_contatto"
								url-invia-pdf="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/inviapdf/".(int)$idOrdineAcquisto;?>"
								csrf="<?php echo User::$csrfToken;?>"
							>
								<i class="fa fa-envelope"></i> <?php echo gtext("Invia");?>
							</button>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
