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

					<!-- show the table -->
					<div class='recordsBox scroll-x'>
						<?php echo $main;?>
					</div>
					
					<!-- show the list of pages -->
					<div class="btn-group pull-right">
						<ul class="pagination no_vertical_margin">
							<?php echo $pageList;?>
						</ul>
					</div>
                </div>
			</div>
		</div>
	</div>
</section>
