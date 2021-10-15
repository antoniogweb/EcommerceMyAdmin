<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$params = CaptchaModel::getModulo()->getParams();
?>
<div class="t">
	<?php echo Html_Form::input($params["campo_nascosto"],"","uk-input ".$params["campo_nascosto"],null,"placeholder='".ucfirst(gtext($params["campo_nascosto"]."*"))."'");?>
</div>
