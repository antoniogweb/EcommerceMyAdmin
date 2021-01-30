<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<a <?php if ($testo["attributi"]) { ?><?php echo htmlentitydecode($testo["attributi"]);?><?php } else { ?>class="btn-line"<?php } ?> <?php echo $target;?> href="<?php echo $urlLink;?>"><?php echo $testo["testo_link"] ? $testo["testo_link"] : $testo["valore"];?></a>
