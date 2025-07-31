<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="panel panel-info">
	<?php include($this->viewPath("pages_form_immagine"));?>
</div>

<div class="panel panel-info">
	<?php
	$labelBlocco = "Immagine 2";
	$numeroImmagine = "2";
	include($this->viewPath("pages_form_immagine"));?>
</div>

<?php if (v("immagine_3_in_servizi")) { ?>
<div class="panel panel-info">
	<?php
	$labelBlocco = "Immagine 3";
	$numeroImmagine = "3";
	include($this->viewPath("pages_form_immagine"));?>
</div>
<?php } ?>