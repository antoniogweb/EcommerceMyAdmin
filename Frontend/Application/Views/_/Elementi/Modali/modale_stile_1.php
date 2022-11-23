<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
 
<?php
$url = PagesModel::getUrlContenuto($p);
?>
 
 <div id="modale_<?php echo $p["pages"]["id_page"];?>" class="uk-modal-container" uk-modal>
    <div class="uk-modal-dialog uk-modal-body uk-padding-remove">
        <div class="uk-grid-collapse uk-child-width-1-2@s" uk-grid>
            <div class="uk-cover-container ">
				<canvas width="100%" height="300px"></canvas>
				<img src="<?php echo $this->baseUrlSrc."/thumb/modale/".$p["pages"]["immagine"];?>" alt="<?php echo altUrlencode(field($p, "title"));?>" uk-cover>
            </div>
            <div class="uk-padding-large uk-text-center">
				<button class="uk-modal-close-full uk-close-large" type="button" uk-close></button>
				<?php if ($p["pages"]["immagine_2"]) { ?>
				<img src="<?php echo $this->baseUrlSrc."/thumb/modalepiccola/".$p["pages"]["immagine_2"];?>" alt="<?php echo altUrlencode(field($p, "title"));?>">
				<?php } ?>
				
                <h1 class="uk-padding-small-bottom"><?php echo field($p, "title");?></h1>
                
                <?php if ($p["pages"]["sottotitolo"]) { ?>
				<h4 class="uk-margin-remove uk-text-lead"><?php echo field($p, "sottotitolo");?></h4>
				<?php } ?>
                <div class="uk-margin uk-text-meta">
					<?php echo htmlentitydecode(field($p, "description"));?>
                </div>
                <?php if ($url) { ?>
                <a  <?php if ($p["pages"]["go_to"]) { ?>uk-scroll<?php } ?> class="<?php if ($p["pages"]["go_to"]) { ?>uk-modal-close<?php } ?> <?php echo v("classe_pulsanti_submit");?>" href="<?php echo $url;?>">
					<?php echo field($p, "testo_link");?>
                </a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
