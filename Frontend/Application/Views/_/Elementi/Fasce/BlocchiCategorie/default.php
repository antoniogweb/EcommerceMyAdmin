<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (isset($elencoCategorieFull) && count($elencoCategorieFull) > 0) { ?>
<div class="uk-container uk-container-medium">
    <h2 class="uk-margin-medium-top uk-margin-medium-bottom uk-text-large uk-text-bold uk-text-uppercase uk-text-center"><?php echo gtext("Categorie prodotti"); ?></h2>

    <div class="uk-width-1-1 uk-margin-large-bottom">
       <div class="uk-child-width-1-2@s uk-child-width-1-4@m uk-grid-small uk-grid-match uk-text-center" uk-grid> 

			<?php foreach ($elencoCategorieFull as $c) { ?>
			<a href="<?php echo $this->baseUrl."/".getCategoryUrlAlias($c["categories"]["id_c"]);?>" class="uk-inline uk-transition-toggle">
				<img src="<?php echo $this->baseUrlSrc."/thumb/categoria/".$c["categories"]["immagine"];?>" alt="<?php echo encodeUrl(cfield($c, "title"));?>">
				<div class="uk-position-center uk-h4 uk-margin-remove uk-text-bold uk-text-uppercase uk-text-center"><?php echo cfield($c, "title");?></div>

				<div class="uk-transition-slide-bottom uk-position-bottom uk-overlay uk-overlay-default">
					<p class="uk-h4 uk-margin-remove uk-text-bold uk-text-uppercase"><?php echo gtext("Vai al dettaglio");?></p>
				</div>
			</a>
			<?php } ?>
        </div>
    </div>
</div>
<?php } ?>
 
