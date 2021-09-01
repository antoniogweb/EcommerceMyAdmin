<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (isset($marchi) && count($marchi) > 0) { ?>
<div class="uk-section">
	<div class="uk-container">
		<div class=" uk-margin-large-bottom">
			<h2 class="uk-text-center uk-text-bold uk-margin-remove-top uk-margin-remove-bottom"><span><?php echo gtext("I nostri marchi"); ?></span></h2>
			<div class="uk-child-width-1-3@m uk-text-center" uk-grid>
				<div></div>
				<div>
					<div class="uk-text-small"><?php echo gtext("Marchi: Lorem ipsum dolor sit amet, consectetur adipiscing elit.");?></div>
				</div>
				<div></div>
			</div>
		</div>
		<div class="" uk-slider>

			<div class="uk-position-relative uk-visible-toggle" tabindex="-1">

				<ul class="uk-slider-items uk-child-width-1-4@s uk-grid">
					<?php foreach ($marchi as $m) { ?>
					<li>
						<div class="uk-card uk-card-default uk-card-small" style="box-shadow: none;">
							<div class="uk-card-media-top">
								<div class="uk-inline-clip uk-transition-toggle">
									<a href="<?php echo $this->baseUrl."/".getMarchioUrlAlias($m["marchi"]["id_marchio"]);?>" class="uk-link-muted uk-text-secondary"><img class="uk-transition-scale-up uk-transition-opaque" src="<?php echo $this->baseUrlSrc."/thumb/famiglia/".$m["marchi"]["immagine"];?>" alt="<?php echo urlencode(mfield($m, "titolo"));?>" /></a>
								</div>
							</div>
							<div class="uk-card-body uk-padding-remove-left">
								<h6 class="uk-margin-remove-bottom uk-text-bold"><a class="uk-button uk-button-text" href="<?php echo $this->baseUrl."/".getMarchioUrlAlias($m["marchi"]["id_marchio"]);?>" class="uk-link-muted uk-text-secondary"><?php echo mfield($m, "titolo");?></a></h6>
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
