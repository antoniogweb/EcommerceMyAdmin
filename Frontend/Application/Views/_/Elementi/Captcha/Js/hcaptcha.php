<?php if (!defined('EG')) die('Direct access not allowed!');
$params = CaptchaModel::getModulo()->getParams();
?>
<?php if (!User::$logged && !isset($_SESSION["ok_captcha"])) { ?>
<script src="https://js.hcaptcha.com/1/api.js" async defer></script>
<?php } ?>
