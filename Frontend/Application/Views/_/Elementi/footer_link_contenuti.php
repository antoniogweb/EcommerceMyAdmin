<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<li><a href="<?php echo $this->baseUrl."/".getCategoryUrlAlias($idShop);?>"><?php echo gtext("Prodotti");?></a></li>
<?php if (isset($tipiPagina["AZIENDA"])) { ?>
<li><a href="<?php echo $this->baseUrl."/".getUrlAlias($tipiPagina["AZIENDA"]);?>"><?php echo gtext("Chi siamo");?></a></li>
<?php } ?>
<?php if (isset($tipiPagina["CONTATTI"])) { ?>
<li><a href="<?php echo $this->baseUrl."/".getUrlAlias($tipiPagina["CONTATTI"]);?>"><?php echo gtext("Contatti");?></a></li>
<?php } ?>
<?php if ($idBlog) { ?>
<li><a href="<?php echo $this->baseUrl."/".getCategoryUrlAlias($idBlog);?>"><?php echo gtext("Blog");?></a></li>
<?php } ?>
<?php if (CategoriesModel::getIdCategoriaDaSezione("faq")) { ?>
<li><a href="<?php echo $this->baseUrl."/".getCategoryUrlAlias(CategoriesModel::getIdCategoriaDaSezione("faq"));?>"><?php echo gtext("Faq");?></a></li>
<?php } ?>

