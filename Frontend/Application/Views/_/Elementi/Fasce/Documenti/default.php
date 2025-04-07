<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$pModel = new PagesModel();
$documenti = $pModel->getDocumenti(PagesModel::$currentIdPage); ?>

<?php if (count($documenti) > 0) { ?>
<div class="uk-container uk-margin-xlarge-top uk-margin-large-bottom">
	<div class="uk-grid-large uk-grid" uk-grid="">
		<div class="uk-width-1-3@s uk-width-1-1 uk-first-column">
			<h2 class="font-2 uk-margin-large-left"><?php echo t("Scarica la scheda informativa")?></h2>
		</div>

		<div class="uk-width-2-3@s uk-width-1-1">
			<div class="uk-margin-large-left uk-margin-large-right">
				<h3 class="uk-text-large"><?php echo t("Documenti scaricabili")?></h3>
				
				<?php foreach ($documenti as $d) { ?>
				<a class="uk-button uk-button-primary uk-inline uk-width-auto uk-margin-small" target="_blank" href="<?php echo $this->baseUrl."/contenuti/documento/".$d["documenti"]["id_doc"];?>"><?php echo dfield($d, "titolo");?> <span uk-icon="icon: arrow-right;ratio: 1.5;ratio: 1.5" class="uk-icon"><svg width="30" height="30" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="arrow-right"><polyline fill="none" stroke="#000" points="10 5 15 9.5 10 14"></polyline><line fill="none" stroke="#000" x1="4" y1="9.5" x2="15" y2="9.5"></line></svg></span></a>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php } ?>
 
