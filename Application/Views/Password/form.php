<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<section class="content-header">
	<h1>Gestione password</h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border main">
					<?php echo $notice;?>

					<!-- show the table -->
					<div class='recordsBox'>
						<?php echo $form;?>
					</div>
                </div>
			</div>
		</div>
	</div>
</section>
