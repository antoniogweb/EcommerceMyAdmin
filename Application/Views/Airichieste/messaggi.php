<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php include($this->viewPath("ordina"));?>

<section class="content-header">
	<h1><?php echo gtext("Gestione");?> <?php echo gtext($tabella);?>: <?php echo $titoloRecord;?></h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<?php if (!nobuttons()) { ?>
				<div class='mainMenu'>
					<?php echo $menu;?>
				</div>
			<?php } ?>

			<?php if (isset($stepsAssociato)) { ?>
			<?php include($this->viewPath($stepsAssociato));?>
			<?php } else { ?>
			<?php include($this->viewPath("steps"));?>
			<?php } ?>

			<div class="box">
				<div class="box-header with-border main">
					<div class="notice_box">
						<?php $flash = flash("notice");?>
						<?php echo $flash;?>
						<?php if (!$flash) echo $notice;?>
					</div>

					<?php include($this->viewPath("gestisci_associato"));?>

					<div class="row">
						<div class="col-md-12">
							<?php echo Html_Form::textarea("messaggio", "", "form-control testo_nuovo_messaggio_ai", null, "placeholder='".gtext("Scrivi qui la tua richiesta..")."'");?>

							<button id-richiesta="<?php echo $id;?>" style="margin-top:10px;" class="btn btn-success btn-block invia_nuovo_messaggio_ai">
								<i class="fa fa-send"></i>
								<?php echo gtext("Invia");?>
							</button>
						</div>
					</div>
                </div>
			</div>
		</div>
	</div>
</section>
