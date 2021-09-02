<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<section class="content-header">
	<h1><?php echo gtext("Scelta tema");?></h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<?php foreach ($elencoTemi as $tema) { ?>
		<div class="col-lg-4">
			<div class="box">
				<div class="box-header with-border main">
					<h3 class="box-title"><?php echo $tema["nome"];?></h3>
                </div>
                <div class="box-body box-body-tema">
					<img src="<?php echo Domain::$publicUrl."/Application/Views/".$tema["nome"]."/_Preview/preview.png"?>" />
                </div>
                <div class="box-footer clearfix">
					<?php if (v("theme_folder") == $tema["nome"]) { ?>
					<button href="<?php echo $this->baseUrl."/impostazioni/attivatema/".$tema["nome"];?>" type="button" class="pull-right btn btn-success ajlink" id="sendEmail">
						<i class="fa fa-check"></i>
						<?php echo gtext("Tema attivo");?>
					</button>
					<?php } else { ?>
					<button href="<?php echo $this->baseUrl."/impostazioni/attivatema/".$tema["nome"];?>" type="button" class="pull-right btn btn-default ajlink" id="sendEmail">
						<i class="fa fa-check"></i>
						<?php echo gtext("Attiva");?>
					</button>
					<?php } ?>
                </div>
			</div>
		</div>
		<?php } ?>
	</div>
</section>
