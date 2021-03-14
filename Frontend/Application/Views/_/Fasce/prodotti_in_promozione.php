<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (count($inPromozione) > 0) { ?>
<div class="uk-section uk-section-muted">
	<div class="uk-container">
<!-- 		<div class="uk-text-center uk-text-uppercase uk-text-lead uk-text-small"><?php echo gtext("Prodotti in"); ?></div> -->
		<h2 class="uk-text-bold uk-text-center uk-margin-remove-top uk-margin-remove-bottom"><span><?php echo gtext("Promozione"); ?></span></h2>
		<div class="uk-child-width-1-3@m uk-text-center" uk-grid>
			<div></div>
			<div>
				<div class="uk-text-small"><?php echo gtext("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.");?></div>
			</div>
			<div></div>
		</div>
	
		<div class="uk-card-small uk-grid-column-medium uk-grid uk-text-left" uk-grid>
			<div class="uk-width-1-1 uk-width-1-2@m">
				<div class="uk-inline-clip uk-transition-toggle">
					<?php echo i("promozione");?>
					<div class="uk-text-center uk-position-small uk-position-top uk-overlay uk-overlay-default">
						<div class="uk-text-small"><?php echo t("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.")?></div>
						<a class="uk-button uk-button-secondary uk-margin" href="<?php echo $this->baseUrl."/prodotti-in-promozione.html";?>"><?php echo gtext("Vedi tutti");?></a>
					</div>
				</div>
			</div>
			<div class="uk-width-expand">
				<div class="" uk-slider>

					<div class="uk-position-relative uk-visible-toggle" tabindex="-1">

						<ul class="uk-text-center uk-slider-items uk-child-width-1-2@s uk-grid uk-grid-column-small">
							<?php foreach ($inPromozione as $p) { ?>
							<li>
								<?php include(tpf("/Elementi/Categorie/prodotto.php")); ?>
							</li>
							<?php } ?>
						</ul>

						<a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
						<a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slider-item="next"></a>

					</div>

					<ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>

				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>
