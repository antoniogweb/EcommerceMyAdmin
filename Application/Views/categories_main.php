<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (count($elencoTraduzioniAttive) > 0) { ?>
<style>
<?php
$incrementoFisso = $section ? 3 : 2;

for ($i=0; $i<count($elencoTraduzioniAttive); $i++) { ?>
.table-scaffolding tr td:nth-last-child(<?php echo ($i+$incrementoFisso)?>), .table-scaffolding tr th:nth-last-child(<?php echo ($i+$incrementoFisso)?>)
{
	width:32px;
}
<?php } ?>
</style>
<?php } ?>

<?php include(ROOT."/Application/Views/categories_ordina_js.php"); ?>

<section class="content-header">
	<?php if (!isset($pageTitle)) { ?>
	<h1><?php echo gtext("Gestione");?> <?php echo gtext("categorie");?></h1>
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
			
			<div class="box">
				<div class="box-header with-border main">
					<!-- start the popup menù -->
					<!--<div class="verticalMenu">
						<?php echo $popup;?>
					</div>-->

<!-- 					<div class="notice_box"> -->
						<?php echo $notice;?>
<!-- 					</div> -->

					<!-- show the table -->
<!-- 					<div class='recordsBox'> -->
						<?php echo $main;?>
<!-- 					</div> -->

					<?php if (!$section) { ?>
					<!-- show the list of pages -->
					<div class="btn-group pull-right">
						<ul class="pagination no_vertical_margin">
							<?php echo $pageList;?>
						</ul>
					</div>
					<?php } ?>
                </div>
			</div>
		</div>
	</div>
</section>
