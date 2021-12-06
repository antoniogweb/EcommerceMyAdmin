<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (file_exists(tpf("Public/Css/style.min.css"))) { ?>
<link rel="stylesheet" href="<?php echo tpf("Public/Css/style.min.css", true);?>?v=<?php echo rand(1,10000);?>" />
<?php } else { ?>
<link rel="stylesheet" href="<?php echo tpf("Public/Css/style.css", true);?>?v=<?php echo rand(1,10000);?>" />
<?php } ?>

<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrlSrc;?>/admin/Frontend/Public/Css/skins/minimal/minimal.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrlSrc;?>/admin/Frontend/Public/Js/image-picker/image-picker.css">
