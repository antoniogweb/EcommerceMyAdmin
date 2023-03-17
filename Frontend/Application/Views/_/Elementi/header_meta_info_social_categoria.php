<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (isset($isCategory) && isset(CategoriesModel::$currentCategoryData)) {
	$cat = CategoriesModel::$currentCategoryData;
	$urlCategoryAlias = getCategoryUrlAlias($cat["categories"]["id_c"]);
?>
	<!-- for Facebook -->       
	<meta property="og:title" content="<?php echo F::meta(cfield($cat,"title"),1000);?>" />
	<?php if (strcmp($cat["categories"]["immagine"],"") !== 0) { ?>
	<meta property="og:image" content="<?php echo $this->baseUrlSrc."/thumb/categoria/".$cat["categories"]["immagine"];?>" />
	<?php } ?>
	<meta property="og:url" content="<?php echo $this->baseUrl."/$urlCategoryAlias";?>" />
	<meta property="og:description" content="<?php echo F::meta(cfield($cat,"description"),200);?>" />
	
	<!-- for Twitter -->          
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:title" content="<?php echo F::meta(cfield($cat,"title"),1000);?>" />
	<meta name="twitter:description" content="<?php echo F::meta(cfield($cat,"description"),200);?>" />
	
	<?php if (strcmp($cat["categories"]["immagine"],"") !== 0) { ?>
	<meta name="twitter:image" content="<?php echo $this->baseUrlSrc."/thumb/categoria/".$cat["categories"]["immagine"];?>" />
	<?php } ?>
<?php } ?>
