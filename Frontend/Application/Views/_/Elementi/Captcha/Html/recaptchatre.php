<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$params = CaptchaModel::getModulo()->getParams();

echo Html_Form::hidden($params["campo_nascosto"],"");

CaptchaModel::getModulo()->setUsato();
