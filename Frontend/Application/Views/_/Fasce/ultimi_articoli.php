<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (isset($ultimiArticoli) && count($ultimiArticoli) > 0) { ?>
<section class="uk-section uk-section-muted">
	<div class="uk-container">
		<h2 class="uk-text-bold uk-margin-medium"><?php echo gtext("Ultimi articoli");?></h2>
		<div data-uk-slider="velocity: 5" class=" uk-slider-container-offset uk-slider">
			<div class="uk-position-relative">
				<div class="uk-slider-container">
					<ul class="uk-slider-items uk-child-width-1-2@s uk-child-width-1-3@m uk-grid uk-grid-medium">
						<?php foreach ($ultimiArticoli as $p) { ?>
						<li tabindex="-1" class="" style="order: 1;">
							<?php include(tpf("/Elementi/Categorie/news.php"));?>
						</li>
						<?php } ?>
					</ul>
				</div>
				<ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>
				<div class="uk-visible@m">
					<a class="uk-position-center-left-out uk-position-small" href="#" data-uk-slidenav-previous data-uk-slider-item="previous"></a>
					<a class="uk-position-center-right-out uk-position-small" href="#" data-uk-slidenav-next data-uk-slider-item="next"></a>
				</div>
			</div>
			
		</div>
	</div>
</section>
<?php } ?>
