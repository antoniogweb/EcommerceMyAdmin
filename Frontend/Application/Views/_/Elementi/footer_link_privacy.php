<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (isset($tipiPagina["PRIVACY"])) { ?>
<li><a href="<?php echo $this->baseUrl."/".getUrlAlias($tipiPagina["PRIVACY"]);?>"><?php echo gtextPlain("Privacy");?></a></li>
<?php } ?>
<?php if (isset($tipiPagina["COOKIE"])) { ?>
<li><a href="<?php echo $this->baseUrl."/".getUrlAlias($tipiPagina["COOKIE"]);?>"><?php echo gtextPlain("Cookie");?></a></li>
<?php } ?>
<?php if (isset($tipiPagina["CONDIZIONI"])) { ?>
<li><a href="<?php echo $this->baseUrl."/".getUrlAlias($tipiPagina["CONDIZIONI"]);?>"><?php echo gtextPlain("Condizioni di vendita");?></a></li>
<?php } ?>
<?php if (isset($tipiPagina["RESI"])) { ?>
<li><a href="<?php echo $this->baseUrl."/".getUrlAlias($tipiPagina["RESI"]);?>"><?php echo gtextPlain("Spedizione e resi");?></a></li>
<?php } ?>
<?php if (isset($tipiPagina["FAQ"])) { ?>
<li><a href="<?php echo $this->baseUrl."/".getUrlAlias($tipiPagina["FAQ"]);?>"><?php echo gtextPlain("FAQ");?></a></li>
<?php } ?>
