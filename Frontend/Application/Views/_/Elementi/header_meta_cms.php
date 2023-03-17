<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php include(tpf("Elementi/header_title_description_keywords.php")); ?>

<?php
$stringaCache = 'PagesModel::$IdCombinazione = '.(int)PagesModel::$IdCombinazione.';';
$stringaCache .= '$richSnippet = PagesModel::getRichSnippetPage('.(int)PagesModel::$currentIdPage.');';
include(tpf("Elementi/header_rich_snippet.php", false, false, $stringaCache));?>

<?php if (isset($tagCanonical)) { ?>
<?php echo $tagCanonical;?>
<?php } ?>

<?php include(tpf("Elementi/header_meta_info_social.php")); ?>
<?php
if (v("includi_dati_per_social_categoria")) { 
	include(tpf("Elementi/header_meta_info_social_categoria.php"));
}
?>
