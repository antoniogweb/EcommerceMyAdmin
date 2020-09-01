<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="site-content-contain">
	<div id="content" class="site-content">
		<div class="wrap">
			<div id="primary" class="content-area">
				<main id="main" class="site-main">
					<article id="post-10" class="post-10 page type-page status-publish hentry">
						<div class="">
							<div class="woocommerce">
								<div id="main" class="cart_container">
									
									<div style="height:30px;"></div>
									
									<?php if ($conclusa) { ?>
									
									<h1>Transazione effettuata con successo</h1>
									
									<p><br />Grazie per il suo acquisto!</p><p>La transazione dell'ordine #<?php echo $ordine["id_o"];?> è andata a buon fine. A breve le arriverà una mail con la conferma del pagamento e potrà scaricare la fattura dell'ordine all'interno della propria area personale, nella sezione ordini.</p>
									
									<?php } else if (strcmp($ordine["stato"],"completed") === 0) { ?>
									
									<h1>Transazione effettuata con successo</h1>
									
									<p><br />Grazie per il suo acquisto!</p><p>Il pagamento dell'ordine #<?php echo $ordine["id_o"];?> è andato a buon fine.</p>
									<?php } else { ?>
									
									<h1>Transazione in fase di verifica</h1>
									
									<p>Transazione in fase di verifica, controllare lo stato del pagamento dell'ordine tra quache minuto.</p>
									
									<?php } ?>
									
									<p><a href="<?php echo $this->baseUrl;?>"><?php echo gtext("Torna alla home");?></a></p>
									
								</div>
							</div>
						</div><!-- .entry-content -->
					</article><!-- #post-## -->
				</main><!-- #main -->
			</div><!-- #primary -->
		</div><!-- .wrap -->
	</div><!-- #content -->
</div><!-- .site-content-contain -->
