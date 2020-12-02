<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div id="page-title-bar" class="page-title-bar">
	<div class="container">
		<div class="wrap w-100 d-flex align-items-center text-center">
			<div class="page-title-bar-inner d-flex align-self-center flex-column w-100 text-center">
				<div class="breadcrumb mb-0 w-100 order-last">
					<!-- Breadcrumb NavXT 6.3.0 -->
					<p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>"><?php echo gtext("Home");?></a> » <a href="<?php echo $this->baseUrl."/area-riservata";?>"><?php echo gtext("Area riservata");?></a> » <?php echo gtext("Ordini effettuati");?></p>
				</div>
				<div class="page-header  mb-2 w-100 order-first">
					<h1 class="page-title"><?php echo gtext("Ordini effettuati");?></h1>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="site-content-contain">
	<div id="content" class="site-content">
		<div class="wrap">
			<div id="primary" class="content-area">
				<main id="main" class="site-main">
					<article id="post-10" class="post-10 page type-page status-publish hentry">
						<div class="">
							<div class="woocommerce">
								<?php
								$attiva = "ordini";
								include(tp()."/riservata-left.php");?>

								<div class="woocommerce-MyAccount-content">
									<?php if (count($ordini) > 0) { ?>
									<table class="table table_2 table_responsive" cellspacing="0">
										<thead>
											<tr class="ordini_head">
												<th><?php echo gtext("Ordine");?></th>
												<th><?php echo gtext("Data");?></th>
												<th><?php echo gtext("Stato");?></th>
												<th><?php echo gtext("Totale (€)");?></th>
												<th width="3%"><?php echo gtext("Fattura");?></th>
											</tr>
										</thead>
										<?php foreach ($ordini as $ordine) { ?>
										<tr class="ordini_table_row">
											<td><a href="<?php echo $this->baseUrl."/resoconto-acquisto/".$ordine["orders"]["id_o"]."/".$ordine["orders"]["cart_uid"];?>?n=y">#<?php echo $ordine["orders"]["id_o"];?></a></td>
											<td><?php echo smartDate($ordine["orders"]["data_creazione"]);?></td>
											<td><?php echo statoOrdine($ordine["orders"]["stato"]);?></td>
											<td><?php echo setPriceReverse($ordine["orders"]["total"]);?></td>
											<td><?php echo pulsanteFattura($ordine["orders"]["id_o"]);?></td>
										</tr>
										<?php } ?>
									</table>
									<?php } else { ?>
									<p><?php echo gtext("Non hai effettuato alcun ordine");?></p>
									<?php } ?>
								</div>
						</div><!-- .entry-content -->
					</article><!-- #post-## -->
				</main><!-- #main -->
			</div><!-- #primary -->
		</div><!-- .wrap -->
	</div><!-- #content -->
</div><!-- .site-content-contain -->
