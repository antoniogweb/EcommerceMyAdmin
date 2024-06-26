<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$params = CaptchaModel::getModulo()->getParams(); ?>

<?php if (!User::$logged && !isset($_SESSION["ok_captcha"])) { ?>
<div style="margin-top:10px;" class="h-captcha" data-sitekey="<?php echo $params["secret_client"];?>"></div>

<?php CaptchaModel::getModulo()->setUsato(); ?>

<?php }
