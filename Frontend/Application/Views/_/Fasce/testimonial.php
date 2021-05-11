<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (count($pages) > 0) { ?>
<div class="uk-container-expand uk-margin-medium-bottom uk-background-green" id="testimonial"> 
    <div class="uk-container uk-container-small">
        <div class="">
            <h2 class="uk-text-center uk-text-bold uk-text-uppercase uk-margin-remove"><?php echo gtext("Testimonial");?></h2>
            <h3 class="uk-text-center uk-text-bold uk-margin-small-top"><?php echo gtext("Sottotitolo");?></h3>
            
        <ul class="rslides">
			<?php foreach ($pages as $p) { ?>
            <li>
                <span class="uk-text-center">
					<?php echo htmlentitydecode(attivaModuli(field($p, "description")));?>
                </span>

                <div class="uk-flex uk-flex-center">
                    <img uk-img src="<?php echo $this->baseUrlSrc."/thumb/testimonial/".$p["pages"]["immagine"];?>" />
                    <i>@<?php echo field($p, "autore");?></i>
                </div>
            </li>
			<?php } ?>
        </ul>
        </div>
        <div class="clear"></div>
    </div>
</div>
<?php } ?>
