<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<section class="content-header">
	<h1>Gestione fatture</h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border main">

					<?php if ($fattureOk and $idUltimaFattura > 0) { ?>
					<a class="btn btn-danger" href="<?php echo $this->baseUrl."/fatture/main?delete=$idUltimaFattura";?>"><i class="fa fa-trash-o"></i> Cancella fattura #<?php echo $ultimaFattura;?></a>
					<?php } ?>

					<div class="notice_box">
						<?php echo $notice;?>
					</div>

					<!-- show the table -->
					<div class='recordsBox'>
						<?php echo $main;?>
					</div>

					<!-- show the list of pages -->
					<div class="viewFooter">
						<div class="pageList">
							pagine: <?php echo $pageList;?>
						</div>
					</div>

                </div>
			</div>
		</div>
	</div>
</section>
