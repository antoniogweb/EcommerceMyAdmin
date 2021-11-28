<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-container uk-container-large uk-margin-large">
	<div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1" uk-slider="autoplay: true; autoplay-interval: 3000;velocity: 3">
	    <ul class="uk-slider-items uk-child-width-1-2 uk-child-width-1-3@s uk-child-width-1-4@m uk-child-width-1-6@l uk-grid">
			<?php foreach ($pages as $p) { ?>
	        <li>
	            <div class="uk-panel">
	                <img src="<?php echo $this->baseUrlSrc."/thumb/gallery/".$p["pages"]["immagine"];?>" alt="<?php echo altUrlencode(field($p, "title"));?>">
	            </div>
	        </li>
	        <?php } ?>
	    </ul>

	    <a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
	    <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slider-item="next"></a>

	</div>
</div> 
