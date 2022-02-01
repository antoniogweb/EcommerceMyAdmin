<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (file_exists(tpf("Public/Css/style.min.css"))) { ?>
<link rel="stylesheet" href="<?php echo tpf("Public/Css/style.min.css", true);?>?v=<?php echo rand(1,10000);?>" />
<?php } else { ?>
<link rel="stylesheet" href="<?php echo tpf("Public/Css/style.css", true);?>?v=<?php echo rand(1,10000);?>" />
<?php } ?>

<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrlSrc."/".v("checkbox_css_path");?>">
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrlSrc;?>/admin/Frontend/Public/Js/image-picker/image-picker.css">

<?php if (isset($tipoPagina) && $tipoPagina == "FORM_FEEDBACK") { ?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrlSrc;?>/admin/Frontend/Public/Js/star-rating-svg-master/src/css/star-rating-svg.css">
<?php } ?>
