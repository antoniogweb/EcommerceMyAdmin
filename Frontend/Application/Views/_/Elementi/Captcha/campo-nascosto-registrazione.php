<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$params = CaptchaModel::getModulo()->getParams();
?>
<div class="uk-margin uk-margin-remove-bottom t" aria-hidden="true">
	<div class="uk-form-controls">
		<?php echo Html_Form::input($params["campo_nascosto_registrazione"], "", "uk-input ".$params["campo_nascosto_registrazione"], null, "tabindex='-1' autocomplete='off' placeholder='".ucfirst(gtextAttr($params["campo_nascosto_registrazione"]."*"))."'");?>
	</div>
</div>
