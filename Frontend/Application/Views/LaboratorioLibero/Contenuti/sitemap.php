<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php  echo '<?xml version="1.0" encoding="UTF-8"?>';?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

	<?php foreach ($sitemap as $r) { ?>
	<url>
		<loc><?php echo $this->baseUrl."/".getUrlAlias($r["pages"]["id_page"]);?></loc>
	</url>
	<?php } ?>
</urlset>