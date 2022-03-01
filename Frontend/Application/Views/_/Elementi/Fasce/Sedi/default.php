<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$sedi = SediModel::getElementiFascia();

if (isset($sedi) && count($sedi) > 0) { ?>
<div class="uk-section">
	<div class="uk-container">
		<div class=" uk-margin-large-bottom">
			<h2 class="uk-text-center uk-text-bold uk-margin-remove-top uk-margin-remove-bottom"><span><?php echo gtext("Le nostre sedi"); ?></span></h2>
		</div>
		<div class="uk-card-small uk-grid-column uk-child-width-1-3@s uk-text-center" uk-grid>
			<?php foreach ($sedi as $p) {
				include(tpf("/Elementi/Categorie/sede.php"));
			} ?>
		</div>
	</div>
</div>
<?php } ?>
 
