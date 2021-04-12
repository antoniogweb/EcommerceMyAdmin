<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php include($this->viewPath("ordina"));?>

<section class="content-header">
	<?php if (!isset($pageTitle)) { ?>
	<h1>Gestione <?php echo $tabella;?>: <?php echo $titoloPagina; ?></h1>
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

			<?php include($this->viewPath("steps"));?>
			
			<div class="box">
				<div class="box-header with-border main">
					<a class="btn btn-success iframe" href="<?php echo $this->baseUrl."/caratteristichevalori/main?id_page=$id_page&partial=Y"?>"><i class="fa fa-plus"></i> Aggiungi</a>
					<div class="notice_box">
						<?php echo $notice;?>
					</div>
					
					<br />
					<!-- show the table -->
					<div class='recordsBox'>
						<?php if ($numeroCaratteristicheVal > 0) { ?>
						<?php echo $main;?>
						<?php } else {  ?>
						<span class="empty_list">Non è stata associata alcuna caratteristica</span>
						<?php } ?>
					</div>
                </div>
			</div>
		</div>
	</div>
</section>
