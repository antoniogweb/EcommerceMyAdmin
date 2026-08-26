<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<li><a href="<?php echo $this->baseUrl."/".getCategoryUrlAlias($idShop);?>"><?php echo gtextPlain("Prodotti");?></a></li>
<?php if (isset($tipiPagina["AZIENDA"])) { ?>
<li><a href="<?php echo $this->baseUrl."/".getUrlAlias($tipiPagina["AZIENDA"]);?>"><?php echo gtextPlain("Chi siamo");?></a></li>
<?php } ?>
<?php if (isset($tipiPagina["CONTATTI"])) { ?>
<li><a href="<?php echo $this->baseUrl."/".getUrlAlias($tipiPagina["CONTATTI"]);?>"><?php echo gtextPlain("Contatti");?></a></li>
<?php } ?>
<?php if ($idBlog) { ?>
<li><a href="<?php echo $this->baseUrl."/".getCategoryUrlAlias($idBlog);?>"><?php echo gtextPlain("Blog");?></a></li>
<?php } ?>
<?php if (CategoriesModel::getIdCategoriaDaSezione("faq")) { ?>
<li><a href="<?php echo $this->baseUrl."/".getCategoryUrlAlias(CategoriesModel::getIdCategoriaDaSezione("faq"));?>"><?php echo gtextPlain("Faq");?></a></li>
<?php } ?>

