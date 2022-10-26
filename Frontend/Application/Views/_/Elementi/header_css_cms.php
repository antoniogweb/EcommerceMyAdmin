<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (file_exists(tpf("Public/Css/style.min.css"))) { ?>
<link rel="stylesheet" href="<?php echo tpf("Public/Css/style.min.css", true);?>" />
<?php } else { ?>
<link rel="stylesheet" href="<?php echo tpf("Public/Css/style.css", true);?>" />
<?php } ?>

<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrlSrc."/".v("checkbox_css_path");?>">
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrlSrc;?>/admin/Frontend/Public/Js/image-picker/image-picker.css">

<?php if (isset($tipoPagina) && $tipoPagina == "FORM_FEEDBACK") { ?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrlSrc;?>/admin/Frontend/Public/Js/star-rating-svg-master/src/css/star-rating-svg.css">
<?php } ?>

<?php if (v("filtro_prezzo_slider")) { ?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrlSrc;?>/admin/Frontend/Public/Js/jquery-nstslider-master/dist/jquery.nstSlider.min.css">
<?php } ?>

<?php if ($this->controller == "listeregalo" && $this->action == "modifica") { ?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrlSrc;?>/admin/Frontend/Public/Js/jquery-ui-1.13.2.custom/jquery-ui.min.css">
<?php } ?>
