<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (isset($elencoCategorieFull) && count($elencoCategorieFull) > 0) { ?>
<div class="uk-section">
	<div class="uk-container">
		<div class=" uk-margin-large-bottom">
			<h2 class="uk-text-center uk-text-bold uk-margin-remove-top uk-margin-remove-bottom"><span><?php echo gtext("Categorie prodotti"); ?></span></h2>
			<div class="uk-child-width-1-3@m uk-text-center" uk-grid>
				<div></div>
				<div>
					<div class="uk-text-small"><?php echo gtext("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.");?></div>
				</div>
				<div></div>
			</div>
		</div>
		<div class="uk-slider-container-offset" uk-slider>

			<div class="uk-position-relative uk-visible-toggle" tabindex="-1">

				<ul class="uk-slider-items uk-child-width-1-4@s uk-grid">
					<?php foreach ($elencoCategorieFull as $c) { ?>
					<li>
						<div class="uk-card uk-card-default uk-card-small" style="box-shadow: none;">
							<div class="uk-card-media-top">
								<div class="uk-inline-clip uk-transition-toggle">
									<a href="<?php echo $this->baseUrl."/".getCategoryUrlAlias($c["categories"]["id_c"]);?>" class="uk-link-muted uk-text-secondary"><img class="uk-transition-scale-up uk-transition-opaque" src="<?php echo $this->baseUrlSrc."/thumb/categoria/".$c["categories"]["immagine"];?>" /></a>
								</div>
							</div>
							<div class="uk-card-body uk-padding-remove-left">
								<h6 class="uk-margin-remove-bottom uk-text-bold"><a class="uk-button uk-button-text" href="<?php echo $this->baseUrl."/".getCategoryUrlAlias($c["categories"]["id_c"]);?>" class="uk-link-muted uk-text-secondary"><?php echo cfield($c, "title");?></a></h6>
							</div>
						</div>
					</li>
					<?php } ?>
				</ul>
				
				<div class="uk-visible@m">
					<a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
					<a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slider-item="next"></a>
				</div>
			</div>
			
			<ul class="uk-slider-nav uk-dotnav uk-flex-center"></ul>
		</div>
	</div>
</div>
<?php } ?>
