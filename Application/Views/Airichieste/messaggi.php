<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

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

                </div>
			</div>

			<br />

			<div class="ai_chat_box">
				<?php include($this->viewPath("chat"));?>
			</div>
		</div>
	</div>
</section>
