<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$params = CaptchaModel::getModulo()->getParams();
?>
<?php echo "<div class='".v("alert_error_class")."'>".gtext("Si prega di verificare il codice antispam (hCaptcha) mostrato sotto.")."</div>";?><span class='evidenzia'>h-captcha</span>

