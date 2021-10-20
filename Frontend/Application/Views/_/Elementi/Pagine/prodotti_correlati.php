<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (count($prodotti_correlati) > 0) { ?>
<div class="uk-section">
	<div class="uk-container">
		<div class="uk-text-uppercase uk-text-small"><?php echo gtext("Guarda anche"); ?></div>
		<h2 class="uk-text-uppercase uk-text-lead uk-margin-remove-top uk-margin-large-bottom"><?php echo gtext("I prodotti correlati"); ?></h2>
		<br />
		<div class="uk-slider-container-offset" uk-slider>

			<div class="uk-position-relative uk-visible-toggle" tabindex="-1">

				<ul class="uk-slider-items uk-child-width-1-4@s uk-grid">
					<?php foreach ($prodotti_correlati as $corr) { ?>
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
<?php } ?>
