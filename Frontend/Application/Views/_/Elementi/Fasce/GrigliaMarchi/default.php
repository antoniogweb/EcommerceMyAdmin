<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="uk-section">
	<div class="uk-container uk-container-expand">
		<div class="uk-card-small uk-grid-column uk-child-width-1-5@s uk-text-center" uk-grid>
			<?php foreach ($pages as $p) {
				include(tpf("Elementi/Categorie/marchio.php"));
			} ?>
		</div>
	</div>
</div>
 
