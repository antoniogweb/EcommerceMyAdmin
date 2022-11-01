<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$noLoginNotice = true;
$action = $this->baseUrl."/regusers/login?redirect=/checkout";
RegusersModel::$redirectQueryString = "redirect=checkout";
include(tpf("/Regusers/login_form.php")); 
