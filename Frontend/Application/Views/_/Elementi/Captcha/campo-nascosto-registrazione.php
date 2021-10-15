<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$params = CaptchaModel::getModulo()->getParams();
?>
<div class="uk-margin uk-margin-remove-bottom t">
	<label class="uk-form-label"><?php echo ucfirst($params["campo_nascosto_registrazione"]);?></label>
	<div class="uk-form-controls">
		<?php echo Html_Form::input($params["campo_nascosto_registrazione"], "", "uk-input ".$params["campo_nascosto_registrazione"], null, "placeholder='".ucfirst(gtext($params["campo_nascosto_registrazione"]."*"))."'");?>
	</div>
</div>
