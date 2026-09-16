<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (file_exists(tpf("Public/Css/style.min.css"))) { ?>
<link rel="stylesheet" href="<?php echo tpf("Public/Css/style.min.css", true);?>?v=<?php echo v("usa_versione_random") ? rand(1,10000): v("js_version_number");?>" />
<?php } else { ?>
<link rel="stylesheet" href="<?php echo tpf("Public/Css/style.css", true);?>?v=<?php echo v("usa_versione_random") ? rand(1,10000): v("js_version_number");?>" />
<?php } ?>

<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrlSrc."/".v("checkbox_css_path");?>">
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrlSrc;?>/admin/Frontend/Public/Css/cms.css?v=<?php echo v("usa_versione_random") ? rand(1,10000): v("js_version_number");?>">

<?php if (isset($tipoPagina) && $tipoPagina == "FORM_FEEDBACK") { ?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrlSrc;?>/admin/Frontend/Public/Css/rating.css?v=<?php echo v("usa_versione_random") ? rand(1,10000): v("js_version_number");?>">
<?php } ?>

<?php if (v("filtro_prezzo_slider") || ($this->controller == "listeregalo" && $this->action == "modifica") || isset($loadJqueryUi)) { ?>
	<?php if (!isset($skipJqueryUi)) { ?>
	<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrlSrc;?>/admin/Frontend/Public/Js/vendor/jquery-ui/jquery-ui.min.css">
	<?php } ?>
<?php } ?>
