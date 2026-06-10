<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php include(tpf(CaptchaModel::getModulo()->getHiddenFieldIncludeFile())); ?>
<?php include(tpf("Elementi/Pagine/campo-csrf.php", false, false)); ?>