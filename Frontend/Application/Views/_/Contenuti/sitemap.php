<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php  echo '<?xml version="1.0" encoding="UTF-8"?>';?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
	
	<url>
		<loc><?php echo $this->baseUrl;?></loc>
		<lastmod><?php echo date('Y-m-dTH:i:sP', $dataModificaHome);?></lastmod>
		<priority>1.00</priority>
	</url>
	
	<?php foreach ($sitemapCat as $r) { ?>
	<url>
		<loc><?php echo $this->baseUrl."/".getCategoryUrlAlias($r["categories"]["id_c"]);?></loc>
		<lastmod><?php echo date('Y-m-dTH:i:sP', strtotime($r["aggregate"]["ultima_modifica"]));?></lastmod>
		<priority><?php echo number_format($r["categories"]["priorita_sitemap"],2,".","");?></priority>
	</url>
	<?php } ?>
	
	<?php foreach ($sitemap as $r) { ?>
	<url>
		<loc><?php echo $this->baseUrl."/".getUrlAlias($r["pages"]["id_page"]);?></loc>
		<lastmod><?php echo date('Y-m-dTH:i:sP', strtotime($r["aggregate"]["ultima_modifica"]));?></lastmod>
		<priority><?php echo number_format($r["pages"]["priorita_sitemap"],2,".","");?></priority>
	</url>
	<?php } ?>
</urlset>
