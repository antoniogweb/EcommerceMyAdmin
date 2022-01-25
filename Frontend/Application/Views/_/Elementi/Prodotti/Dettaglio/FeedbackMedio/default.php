<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-margin">
	<?php
	$punteggio = PagesModel::punteggio($p["pages"]["id_page"]);
	
	if (PagesModel::hasFeedback($p["pages"]["id_page"]))
		include(tpf("/Elementi/feedback_stars.php"));
	?>
	<?php if (count($page_feedback) > 0) { ?>
	<a href="#tab-dettagli_pagina" class="uk-text-small uk-text-muted">(<?php echo count($page_feedback);?>) <?php echo singPlu(count($page_feedback), "valutazione", "valutazioni");?></a>
	<?php } ?>
</div> 
