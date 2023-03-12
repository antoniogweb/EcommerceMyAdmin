<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php include(tpf("Elementi/header_title_description_keywords.php")); ?>

<?php
$stringaCache = 'PagesModel::$IdCombinazione = '.(int)PagesModel::$IdCombinazione.';';
$stringaCache .= '$richSnippet = PagesModel::getRichSnippetPage('.(int)PagesModel::$currentIdPage.');';
include(tpf("Elementi/header_rich_snippet.php", false, false, $stringaCache));?>

<?php if (isset($tagCanonical)) { ?>
<?php echo $tagCanonical;?>
<?php } ?>

<?php if (isset($isPage)) { ?>
	<?php foreach ($pages as $p) {
		$urlAlias = getUrlAlias($p["pages"]["id_page"]);
	?>
		<!-- for Facebook -->       
		<meta property="og:title" content="<?php echo F::meta($p["pages"]["title"],1000);?>" />
		<meta property="og:type" content="article" />
		<?php if (strcmp($p["pages"]["immagine"],"") !== 0) { ?>
		<meta property="og:image" content="<?php echo $this->baseUrlSrc."/thumb/dettaglio/".$p["pages"]["immagine"];?>" />
		<?php } ?>
		<meta property="og:url" content="<?php echo $this->baseUrl."/$urlAlias";?>" />
		<meta property="og:description" content="<?php echo F::meta($p["pages"]["description"],200);?>" />
		
		<!-- for Twitter -->          
		<meta name="twitter:card" content="summary" />
		<meta name="twitter:title" content="<?php echo F::meta($p["pages"]["title"],1000);?>" />
		<meta name="twitter:description" content="<?php echo F::meta($p["pages"]["description"],200);?>" />
		
		<?php if (strcmp($p["pages"]["immagine"],"") !== 0) { ?>
		<meta name="twitter:image" content="<?php echo $this->baseUrlSrc."/thumb/dettaglio/".$p["pages"]["immagine"];?>" />
		<?php } ?>
	<?php } ?>
<?php } ?>
