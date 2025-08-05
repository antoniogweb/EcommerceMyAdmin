<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="panel panel-info">
	<?php include($this->viewPath("pages_form_immagine"));?>
</div>

<?php if (v("immagine_2_in_progetti")) { ?>
<div class="panel panel-info">
	<?php
	$labelBlocco = "Immagine 2";
	$numeroImmagine = "2";
	include($this->viewPath("pages_form_immagine"));?>
</div>
<?php } ?>
