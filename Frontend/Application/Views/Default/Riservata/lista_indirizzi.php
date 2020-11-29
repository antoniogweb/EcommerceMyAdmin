<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div id="page-title-bar" class="page-title-bar">
	<div class="container">
		<div class="wrap w-100 d-flex align-items-center text-center">
			<div class="page-title-bar-inner d-flex align-self-center flex-column w-100 text-center">
				<div class="breadcrumb mb-0 w-100 order-last">
					<!-- Breadcrumb NavXT 6.3.0 -->
					<p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>"><?php echo gtext("Home");?></a> » <a href="<?php echo $this->baseUrl."/area-riservata";?>">Area riservata</a> » <?php echo gtext("Indirizzi di spedizione");?></p>
				</div>
				<div class="page-header  mb-2 w-100 order-first">
					<h1 class="page-title"><?php echo gtext("Indirizzi di spedizione");?></h1>
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
								$attiva = "indirizzi";
								include(tp()."/riservata-left.php");?>

								<div class="woocommerce-MyAccount-content">
									<div class="woocommerce-notices-wrapper"></div>
									<?php if (count($indirizzi) > 0) { ?>
									<table class="table table_2 table_responsive" cellspacing="0">
										<thead>
											<tr class="ordini_head">
												<th><?php echo gtext("Indirizzo");?></th>
												<th><?php echo gtext("Cap");?></th>
												<th><?php echo gtext("Nazione");?></th>
												<th><?php echo gtext("Città");?></th>
												<th><?php echo gtext("Provincia");?></th>
												<th><?php echo gtext("Telefono");?></th>
												<th width="1%"></th>
												<th width="1%"></th>
											</tr>
										</thead>
										<?php foreach ($indirizzi as $indirizzo) { ?>
										<tr class="">
											<td><?php echo $indirizzo["spedizioni"]["indirizzo_spedizione"];?></td>
											<td><?php echo $indirizzo["spedizioni"]["cap_spedizione"];?></td>
											<td><?php echo nomeNazione($indirizzo["spedizioni"]["nazione_spedizione"]);?></td>
											<td><?php echo $indirizzo["spedizioni"]["citta_spedizione"];?></td>
											<td><?php echo $indirizzo["spedizioni"]["nazione_spedizione"] == "IT" ? $indirizzo["spedizioni"]["provincia_spedizione"] : $indirizzo["spedizioni"]["dprovincia_spedizione"];?></td>
											<td><?php echo $indirizzo["spedizioni"]["telefono_spedizione"];?></td>
											<td><a title="<?php echo gtext("Modifica",false);?>" class="link_grigio" href="<?php echo $this->baseUrl."/gestisci-spedizione/".$indirizzo["spedizioni"]["id_spedizione"];?>"><?php echo gtext("Gestisci");?></a></td>
											<td><a title="<?php echo gtext("Elimina",false);?>" href="<?php echo $this->baseUrl."/riservata/indirizzi?del=".$indirizzo["spedizioni"]["id_spedizione"];?>">X</a></td>
										</tr>
										<?php } ?>
									</table>
									<?php } else { ?>
									<p><?php echo gtext("Non hai alcun indirizzo configurato");?></p>
									<?php } ?>
									
									<p><br /><a href="<?php echo $this->baseUrl."/gestisci-spedizione/0";?>">+ <?php echo gtext("Aggiungi indirizzo");?></a></p>
									
								</div>
							</div>
						</div><!-- .entry-content -->
					</article><!-- #post-## -->
				</main><!-- #main -->
			</div><!-- #primary -->
		</div><!-- .wrap -->
	</div><!-- #content -->
</div><!-- .site-content-contain -->
