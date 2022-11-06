<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (count($pages) > 0) { ?>
<div class="uk-section" uk-parallax="bgy: -200" style="background-image: url(<?php echo $this->baseUrlSrc."/thumb/sfondocategoria/".$pages[0]["categories"]["immagine"];?>);">
	<div class="uk-light uk-container-expand" id="testimonial"> 
		<div class="uk-container uk-container-xsmall">
			<div class="uk-overflow-hidden">
				<h2 class="uk-text-center uk-text-bold uk-margin-remove-top"><?php echo gtext("Testimonial");?></h2>
				
				<div class="uk-slider" uk-slider>

					<div class="uk-position-relative uk-visible-toggle" tabindex="-1">

						<ul class="uk-slider-items uk-child-width-1-1@s uk-grid">
							<?php foreach ($pages as $p) { ?>
							<li>
								<span class="uk-text-center">
									<?php echo htmlentitydecode(attivaModuli(field($p, "description")));?>
								</span>

								<div class="uk-text-center">
									<i>@<?php echo field($p, "autore");?></i>
								</div>
							</li>
							<?php } ?>
						</ul>

					</div>
					
					<ul class="uk-margin-top uk-slider-nav uk-dotnav uk-flex-center"></ul>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php } ?>
 
