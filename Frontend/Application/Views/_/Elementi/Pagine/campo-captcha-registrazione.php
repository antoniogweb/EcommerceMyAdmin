<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php include(tpf(CaptchaModel::getModulo()->getHiddenFieldRegistrazioneIncludeFile())); ?>
<?php if (!isset($noCampoCsrf)) { ?>
<?php include(tpf("Elementi/Pagine/campo-csrf.php")); ?>
<?php } ?>
