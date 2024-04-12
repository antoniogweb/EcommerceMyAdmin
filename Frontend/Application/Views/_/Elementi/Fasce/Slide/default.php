<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (count($slide) > 0) { ?>
<div class="uk-position-relative uk-visible-toggle uk-dark uk-slideshow" tabindex="-1" uk-slideshow="animation: fade;<?php if (!User::$isPhone) { ?>ratio:1920:700<?php } else { ?>min-height: 400; max-height: 400;<?php } ?>">
    <ul class="uk-slideshow-items">
		<?php foreach ($slide as $p) {
			$layers = ContenutiModel::getContenutiPagina($p["pages"]["id_page"], "GENERICO");
			
			$url = PagesModel::getUrlContenuto($p);
		?>
        <li id="<?php echo $p["pages"]["id_page"];?>">
			<div class="uk-section uk-padding-small uk-height-1-1">
				<img src="<?php echo $this->baseUrlSrc."/thumb/slide/".$p["pages"]["immagine"];?>" alt="<?php echo altUrlencode(field($p, "title"));?>" uk-cover>
				<div class="uk-container uk-position-relative uk-height-1-1 uk-padding-remove">
					<?php foreach ($layers as $layer) {?>
					<?php include(tpf("/Elementi/Slide/layer.php"));?>
					<?php } ?>
				</div>
			</div>
        </li>
        <?php } ?>
    </ul>

    <a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slideshow-item="previous"></a>
    <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slideshow-item="next"></a>
</div>
<?php } ?>
