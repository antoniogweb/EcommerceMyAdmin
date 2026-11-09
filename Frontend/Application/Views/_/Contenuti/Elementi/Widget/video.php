<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if ($testo["immagine"]) { ?>
<a class="play" data-fancybox="gallery" href="<?php echo $urlLink;?>"><img alt="<?php echo !empty($testo["alt"]) ? $testo["alt"] : gtextAttr("Riproduci video");?>" src='<?php echo Url::getFileRoot()."thumb/widget/".$testo["id_t"]."/".$testo["immagine"];?>' <?php echo $alt;?>/></a>
<?php } else { ?>
<?php echo $testo["valore"]; ?>
<?php } ?>
