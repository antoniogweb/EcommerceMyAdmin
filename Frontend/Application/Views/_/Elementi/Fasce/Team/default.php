<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php $pages = TeamModel::getElementiFascia();?>
<?php if (isset($pages) && count($pages) > 0) { ?>
<div class="uk-container uk-container-expand uk-margin-large">
    <div class="uk-container uk-container-xsmall">
    	<h2 class="uk-margin-large uk-text-center font-28 uk-text-bold"><?php echo t("Il nostro staff");?></h2>
    	<div class="uk-text-center uk-margin-large"><?php echo t("Testo descrittivo");?></div>

    </div>


	<div class="uk-container uk-container-small uk-margin-large">
    	<div class="uk-grid uk-grid-large uk-child-width-1-4@m uk-child-width-1-2" uk-grid>
			<?php foreach ($pages as $p) {
				include(tpf(ElementitemaModel::p("ELEMENTO_TEAM","", array(
					"titolo"	=>	"Elemento team",
					"percorso"	=>	"Elementi/Sezioni/Team/Elenco/Elemento",
				))));
			} ?>
		</div>
    </div>
</div>
<?php } ?>
