<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (isset($isPage)) { ?>
	<?php foreach ($pages as $p) {
		$urlAlias = getUrlAlias($p["pages"]["id_page"]);
	?>
		<!-- for Facebook -->       
		<meta property="og:title" content="<?php echo F::meta(field($p, "title"),1000);?>" />
		<meta property="og:type" content="article" />
		<?php if (strcmp($p["pages"]["immagine"],"") !== 0) { ?>
		<meta property="og:image" content="<?php echo $this->baseUrlSrc."/thumb/dettaglio/".$p["pages"]["immagine"];?>" />
		<?php } ?>
		<meta property="og:url" content="<?php echo $this->baseUrl."/$urlAlias";?>" />
		<meta property="og:description" content="<?php echo F::meta(field($p, "description"),200);?>" />
		
		<!-- for Twitter -->          
		<meta name="twitter:card" content="summary" />
		<meta name="twitter:title" content="<?php echo F::meta(field($p, "title"),1000);?>" />
		<meta name="twitter:description" content="<?php echo F::meta(field($p, "description"),200);?>" />
		
		<?php if (strcmp($p["pages"]["immagine"],"") !== 0) { ?>
		<meta name="twitter:image" content="<?php echo $this->baseUrlSrc."/thumb/dettaglio/".$p["pages"]["immagine"];?>" />
		<?php } ?>
	<?php } ?>
<?php } ?>
