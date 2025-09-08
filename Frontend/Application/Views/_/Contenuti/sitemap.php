<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php  echo '<?xml version="1.0" encoding="UTF-8"?>';?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

	<?php foreach ($nodi as $n) {
		$n = isset($n["aggregate"]) ? $n["aggregate"] : $n;
	?>
	<url>
		<loc><![CDATA[
			<?php if ($n["id_page"]) { ?>
			<?php echo $this->baseUrl."/".getUrlAlias($n["id_page"]);?>
			<?php } else if ($n["id_c"]) { ?>
			<?php echo $this->baseUrl."/".getCategoryUrlAlias($n["id_c"]);?>
			<?php } else if ($n["url"]) { ?>
			<?php echo $n["url"];?>
			<?php } else { ?>
			<?php echo $this->baseUrl;?>
			<?php } ?>
		]]></loc>
		<lastmod><?php echo date('c', strtotime($n["ultima_modifica"]));?></lastmod>
		<priority><?php echo number_format($n["priorita"],2,".","");?></priority>
	</url>
	<?php } ?>
</urlset>
