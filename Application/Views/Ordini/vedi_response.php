<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<section class="content-header">
	<h1>Risposte dal gateway di pagamento</h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">	
			<div class="box">
				<div class="box-header with-border main help_resoconto">
					<?php foreach ($responses as $r) { ?>
					<h2><?php echo $r["risultato_transazione"] ? gtext("Transazione avvenuta correttamente") : "<span class='text text-danger'>".gtext("Transazione NON avvenuta correttamente")."</span>";?></h2>
					<pre>
						<?php echo $r["response"];?>
					</pre>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</section>
