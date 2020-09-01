<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<section class="content-header">
	<h1><?php if (strcmp($type,"update") === 0) { echo "Gestione amministratore: ".$titoloPagina; } else { echo "Inserimento nuovo amministratore";}?></h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border main">

					<!-- show the top menù -->
					<div class='mainMenu'>
						<?php echo $menu;?>
					</div>

					<?php include(ROOT."/Application/Views/Users/steps.php");?>

					<?php echo $notice;?>

					<!-- show the table -->
					<div class='scaffold_form'>
						<?php echo $main;?>
					</div>
                </div>
			</div>
		</div>
	</div>
</section>