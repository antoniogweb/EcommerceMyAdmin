<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div id="slide_singolo_prodotto" uk-slideshow="animation: push;ratio: 1:1">
	<div class="uk-position-relative">
		<ul class="uk-slideshow-items slide_prodotto" uk-lightbox>
			<li data-image="<?php echo $p["pages"]["immagine"];?>">
				<a data-caption="<?php echo field($p, "title");?>" href="<?php echo $this->baseUrlSrc."/images/contents/".$p["pages"]["immagine"];?>"><img src="<?php echo $this->baseUrlSrc."/thumb/dettagliobig/".$p["pages"]["immagine"];?>" alt="<?php echo encodeUrl(field($p, "title"));?>" uk-cover></a>
			</li>
			<?php foreach ($altreImmagini as $imm) { ?>
			<li data-image="<?php echo $imm["immagine"];?>">
				<a data-caption="<?php echo field($p, "title");?>" href="<?php echo $this->baseUrlSrc."/images/contents/".$imm["immagine"];?>"><img src="<?php echo $this->baseUrlSrc."/thumb/dettagliobig/".$imm["immagine"];?>" alt="<?php echo encodeUrl(field($p, "title"));?>" uk-cover></a>
			</li>
			<?php } ?>
		</ul>
		
		<a class="uk-text-secondary uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slideshow-item="previous"></a>
		<a class="uk-text-secondary uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slideshow-item="next"></a>
	</div>
	
	<?php if (count($altreImmagini) > 0) { ?>
	<ul class="uk-thumbnav">
		<li class="uk-margin-small uk-margin-small-top" uk-slideshow-item="0"><a href="#"><img src="<?php echo $this->baseUrlSrc."/thumb/slidesottothumb/".$p["pages"]["immagine"];?>" width="100" alt=""></a></li>
		<?php
		$indiceSlide = 1;
		foreach ($altreImmagini as $imm) { ?>
		<li class="uk-margin-small" uk-slideshow-item="<?php echo $indiceSlide;?>"><a href="#"><img src="<?php echo $this->baseUrlSrc."/thumb/slidesottothumb/".$imm["immagine"];?>" width="100" alt=""></a></li>
		<?php $indiceSlide++;} ?>
	</ul>
	<?php } ?>
</div>
