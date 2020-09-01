<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<section class="content-header">
	<?php if (!isset($pageTitle)) { ?>
	<h1>Gestione categoria: <?php echo $titoloPagina;?></h1>
	<?php } else { ?>
	<h1><?php echo $pageTitle;?></h1>
	<?php } ?>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- show the top menù -->
			<div class='mainMenu'>
				<?php echo $menu;?>
			</div>

			<?php include($this->viewPath("categories_steps"));?>
			
			<div class="box">
				<div class="box-header with-border main">
					<?php echo $notice;?>

					<div class="scaffold_form">
						<!-- show the table -->
						<div class='recordsBox'>
							<?php echo $main;?>
						</div>
					</div>
                </div>
			</div>
		</div>
	</div>
</section>
