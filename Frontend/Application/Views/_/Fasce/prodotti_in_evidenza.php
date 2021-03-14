<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (count($prodottiInEvidenza) > 0) { ?>
<div class="uk-section uk-text-center">
	<div class="uk-container">
		<div class=" uk-margin-large-bottom">
			<h2 class="uk-text-bold uk-margin-remove-top uk-margin-remove-bottom"><span><?php echo gtext("Prodotti in evidenza"); ?></span></h2>
			<div class="uk-child-width-1-3@m uk-text-center" uk-grid>
				<div></div>
				<div>
					<div class="uk-text-small"><?php echo gtext("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.");?></div>
				</div>
				<div></div>
			</div>
		</div>
		<div class="" uk-slider>

			<div class="uk-position-relative uk-visible-toggle" tabindex="-1">

				<ul class="uk-slider-items uk-child-width-1-4@s uk-grid">
					<?php foreach ($prodottiInEvidenza as $p) { ?>
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
		<a class="uk-button uk-button-default uk-margin" href="<?php echo $this->baseUrl."/".getCategoryUrlAlias($idShop);?>"><?php echo gtext("Vedi tutti");?></a>
	</div>
</div>
<?php } ?>
