<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border main help_resoconto">
					<?php if (isset($_SESSION['result'])) { ?>
					<div class="text text-danger"><?php echo $output;?></div>
					<?php } ?>
					<a class="btn btn-info make_spinner" href="<?php echo $this->baseUrl."/regusers/login"?>"><?php echo gtext("Torna al login");?> <i class="fa fa-arrow-right"></i></a>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
if ( isset($_SESSION['result']) ) unset($_SESSION['result']);
?>