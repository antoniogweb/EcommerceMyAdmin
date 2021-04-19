<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<section class="content-header">
	<h1><?php echo $titoloMenu;?></h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class='mainMenu'>
				<?php if (count($elencoLingue) > 1) { ?>
					<?php foreach ($elencoLingue as $codice => $lingua) { ?>
					<a style="margin-left:10px;" class="btn btn-<?php echo MenuModel::$lingua == $codice ? "primary" : "default"?> pull-right" href="<?php echo $this->baseUrl."/menu/main?lingua=".$codice;?>">Menù <?php echo $lingua;?></a>
					<?php } ?>
				<?php } ?>
				
				<?php echo $menu;?>
			</div>
			
			<div class="box">
				<div class="box-header with-border main">
					<?php echo $notice;?>
					
					<?php echo $main;?>
                </div>
			</div>
		</div>
	</div>
</section>
