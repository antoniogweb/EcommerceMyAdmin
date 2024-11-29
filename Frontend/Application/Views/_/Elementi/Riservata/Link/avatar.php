<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_gestione_immagine_utente")) { ?>
<li class="<?php if ($attiva == "immagine") { ?>uk-active<?php } ?>">
	<a href="<?php echo $this->baseUrl."/immagine-profilo";?>" title="<?php echo gtext("Immagine profilo", false);?>"><?php echo gtext("Immagine profilo");?></a>
</li>
<?php } ?>
