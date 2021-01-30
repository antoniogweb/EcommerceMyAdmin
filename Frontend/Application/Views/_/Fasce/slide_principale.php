<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (count($slide) > 0) { ?>
<div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1" uk-slideshow="animation: fade;ratio:<?php if (!User::$isMobile) { ?>1920:700<?php } else { ?>700:500<?php } ?>">

    <ul class="uk-slideshow-items">
		<?php foreach ($slide as $p) {
			$url = "";
			if ($p["pages"]["link_id_page"])
				$url = $this->baseUrl."/".getUrlAlias($p["pages"]["link_id_page"]);
			else if ($p["pages"]["link_id_c"])
				$url = $this->baseUrl."/".getCategoryUrlAlias($p["pages"]["link_id_c"]);
			else if ($p["pages"]["link_id_marchio"])
				$url = $this->baseUrl."/".getMarchioUrlAlias($p["pages"]["link_id_marchio"]);
			else if ($p["pages"]["link_id_tag"])
				$url = $this->baseUrl."/".TagModel::getUrlAlias($p["pages"]["link_id_tag"]);
			else if (field($p, "url"))
				$url = checkHttp(field($p, "url"));
		?>
        <li>
            <img src="<?php echo $this->baseUrlSrc."/thumb/slide/".$p["pages"]["immagine"];?>" alt="<?php echo encodeUrl(field($p, "title"));?>" uk-cover>
            <div class="uk-position-center uk-position-small uk-text-center">
                <h2 uk-slideshow-parallax="x: 100,-100"><?php echo field($p, "title");?></h2>
                <p uk-slideshow-parallax="x: 200,-200"><?php echo field($p, "sottotitolo");?></p>
                <?php if ($url) { ?>
                <a class="uk-button uk-button-default" href="<?php echo $url;?>"><?php echo gtext("Scopri");?></a>
                <?php } ?>
            </div>
        </li>
        <?php } ?>
    </ul>

    <a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slideshow-item="previous"></a>
    <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slideshow-item="next"></a>

</div>
<?php } ?>
