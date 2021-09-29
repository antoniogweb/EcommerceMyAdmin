<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="uk-card-small uk-grid-column uk-child-width-1-4@s uk-text-center" uk-grid>
	<?php foreach ($pages as $p) {
		include(tpf("Elementi/Categorie/tag.php"));
	} ?>
</div>
