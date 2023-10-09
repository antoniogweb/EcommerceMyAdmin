<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (!empty($record_evento)) { ?>
<section class="content-header">
	<h1><?php echo gtext("Dettaglio trasmissione")?> <?php echo $record_evento["spedizioni_negozio_info"]["codice_info"];?></h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">	
			<div class="box">
				<div class="box-header with-border main help_resoconto">
					<h2><?php echo $record_evento["spedizioni_negozio_info"]["codice_info"];?></h2>
					<pre>
						<?php echo $output;?></h1>
					</pre>				
				</div>
			</div>
		</div>
	</div>
</section>
<?php } ?>
