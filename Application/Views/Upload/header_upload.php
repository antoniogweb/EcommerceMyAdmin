<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html dir="LTR" lang="it">
<head>

<title>Admin zone</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrl.'/Public/Css/filesystem.css';?>">

<?php if ($this->viewArgs['use_flash']) { ?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrl?>/Public/Js/uploadify_3_2_1/uploadify.css" />
<?php } ?>

</head>

<body>

	