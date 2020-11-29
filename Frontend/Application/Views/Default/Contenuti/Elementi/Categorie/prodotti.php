<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
	
<!-- 	<ul class="products columns-4"> -->
	<?php foreach ($pages as $p) {
		include(tp()."/Contenuti/Elementi/Categorie/prodotto.php");
	} ?>
<!-- 	</ul> -->
	
	<?php if (isset($rowNumber) && isset($elementsPerPage)) { ?>
		<?php if ($rowNumber > $elementsPerPage) { ?>
		<?php /*echo $pageList;*/?>
		<?php } ?>
	<?php } ?>
