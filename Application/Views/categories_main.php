<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<section class="content-header">
	<?php if (!isset($pageTitle)) { ?>
	<h1>Gestione categorie</h1>
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
