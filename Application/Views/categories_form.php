<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php include(ROOT."/Application/Views/categories_form_js.php");?>

<section class="content-header">
	<?php if (!isset($pageTitle)) { ?>
	<h1><?php if (strcmp($type,"update") === 0) { echo "Gestione categoria: ".$titoloPagina; } else { echo "Inserimento nuova categoria";}?></h1>
	<?php } else { ?>
	<h1><?php echo $pageTitle;?></h1>
	<?php } ?>
</section>

<!-- Main content -->
<section class="content">
	<!-- show the top menù -->
	<div class='mainMenu'>
		<?php echo $menu;?>
	</div>

	<?php include($this->viewPath("categories_steps"));?>
	
	<div class="row">
		<div class="col-md-8">
			
			<div class="box">
				<div class="box-header with-border main">
					<?php echo $notice;?>

					<!-- show the table -->
					<div class='scaffold_form'>
						<?php echo $main;?>
					</div>
                </div>
			</div>
		</div>
		<?php if (isset($urlPagina)) { ?>
		<div class='col-md-4'>
			<div class="panel panel-info">
				<div class="panel-body">
					<?php include($this->viewPath("pages_link"));?>
				</div>
			</div>
		</div>
		<?php } ?>
		<?php if (isset($contenutiTradotti) && count($contenutiTradotti) > 0 && count(BaseController::$traduzioni) > 0) { ?>
		<div class='col-md-4'>
			<div class="panel panel-info">
				<div class="panel-heading">
					Traduzioni
				</div>
				<div class="panel-body">
					<?php include($this->viewPath("pages_traduzioni"));?>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
</section>
