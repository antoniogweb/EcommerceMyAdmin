<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
User-agent: *
Disallow:<?php if (v("piattaforma_in_sviluppo")) { echo " / "; } echo "\n";?>
<?php if (!v("piattaforma_in_sviluppo")) { echo "Allow: /"; } echo "\n";?>
Sitemap: <?php echo $this->baseUrlSrc;?>/sitemap.xml 
