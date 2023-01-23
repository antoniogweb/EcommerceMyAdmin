<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (!empty($record_evento)) { ?>
<section class="content-header">
	<h1><?php echo gtext("Script del Pixel")?> <?php echo $record_evento["pixel"]["titolo"];?></h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">	
			<div class="box">
				<div class="box-header with-border main help_resoconto">
					<h2><?php echo gtext("Codice script");?></h2>
					<pre>
						<?php echo $record_evento["pixel_eventi"]["codice_evento"];?></h1>
					</pre>
					
					<h2><?php echo gtext("Codice noscript");?></h2>
					<pre>
						<?php echo $record_evento["pixel_eventi"]["codice_evento_noscript"];?></h1>
					</pre>
					
				</div>
			</div>
		</div>
	</div>
</section>
<?php } ?>
