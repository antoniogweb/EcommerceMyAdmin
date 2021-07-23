<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="uk-card-small uk-grid-column uk-child-width-1-5@s uk-text-center" uk-grid>
	<?php foreach ($elencoMarchiNuoviFull as $p) {
		include(tpf("Elementi/Categorie/marchio.php"));
	} ?>
</div>
