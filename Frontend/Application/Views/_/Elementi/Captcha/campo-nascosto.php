<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$params = CaptchaModel::getModulo()->getParams();
?>
<div class="t" aria-hidden="true">
	<?php echo Html_Form::input($params["campo_nascosto"],"","uk-input ".$params["campo_nascosto"],null,"tabindex='-1' autocomplete='off' placeholder='".ucfirst(gtextAttr($params["campo_nascosto"]."*"))."'");?>
</div>
