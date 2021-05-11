<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if ($testo["immagine"]) { ?>
<a class="play" data-fancybox="gallery" href="<?php echo $urlLink;?>"><img alt="<?php echo $testo["alt"];?>" src='<?php echo Url::getFileRoot()."thumb/widget/".$testo["id_t"]."/".$testo["immagine"];?>' <?php echo $alt;?>/></a>
<?php } else { ?>
<?php echo $testo["valore"]; ?>
<?php } ?>
