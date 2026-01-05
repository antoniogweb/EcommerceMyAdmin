<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<section class="content-header">
	<h1><?php echo gtext("Aggiunta listino");?></h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border main help_resoconto">
					<div class="row">
						<div class="col-lg-12">
							<form class="formClass" method="GET" action="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/main?"?>">
								<div class="col-md-4">
									<div class="form_input_text">
										<label class="entryLabel"><?php echo gtext("Nazione");?></label>
										<?php echo Html_Form::select("listino","",$listiniAttivabili,"form-control",null,"yes");?>
									</div>
									
									<div class="submit_entry">
										<a class="btn btn-info make_spinner" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/main".$this->viewStatus;?>"><i class="fa fa-arrow-left"></i> <?php echo gtext("Torna");?></a>
										<span class="submit_entry_Salva pull-right">
											<button id="gAction" class="btn btn-success make_spinner" name="gAction" type="submit">
												<?php echo gtext("Procedi");?> <i class="fa fa-arrow-right"></i>
											</button>
										</span>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>