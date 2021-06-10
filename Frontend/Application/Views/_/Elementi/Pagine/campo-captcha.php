<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$campoCaptcha = v("campo_captcha_form");
?>
<div class="t">
	<?php echo Html_Form::input($campoCaptcha,"","uk-input ".$campoCaptcha,null,"placeholder='".ucfirst(gtext("$campoCaptcha*"))."'");?>
</div>
