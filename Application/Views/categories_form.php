<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php include(ROOT."/Application/Views/categories_form_js.php");?>

<section class="content-header">
	<?php if (!isset($pageTitle)) { ?>
	<h1><?php if (strcmp($type,"update") === 0) { echo gtext("Gestione")." $tabella: ".$titoloPagina; } else { echo gtext("Inserimento nuova categoria");}?></h1>
	<?php } else { ?>
	<h1><?php echo $pageTitle;?></h1>
	<?php } ?>
</section>

<!-- Main content -->
<section class="content">
	<!-- show the top menù -->
	<?php if (!nobuttons()) { ?>
	<div class='mainMenu'>
		<?php echo $menu;?>
	</div>
	<?php } ?>
	
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
		<?php if (isset($urlPagina) && isset($dettagliCategoria) && $dettagliCategoria["attivo"] == "Y" && !$dettagliCategoria["bloccato"]) { ?>
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
					<?php echo gtext("Traduzioni");?>
				</div>
				<div class="panel-body">
					<?php include($this->viewPath("pages_traduzioni"));?>
				</div>
			</div>
		</div>
		<?php } ?>

		<?php if (v("attiva_richieste_ai")) { ?>
		<div class='col-md-4'>
			<div class="panel panel-info">
				<div class="panel-heading">
					<?php echo gtext("Assistente testi IA");?>
				</div>
				<div class="panel-body">
					<a href="<?php echo $this->baseUrl."/airichieste/form/insert/0?id_c=$id&partial=Y";?>" class="btn btn-info iframe"><?php echo gtext("Apri l'assistente")?></a>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
</section>
